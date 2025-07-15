<?php

namespace App\Services;


use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Company;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class InvoiceService
{
    /**
     * Create invoice from template
     */
    public function createInvoiceFromTemplate(array $invoiceData, array $templateItems): Invoice
    {
        $invoice = Invoice::create(array_merge($invoiceData, [
            'invoice_number' => Invoice::generateInvoiceNumber($invoiceData['invoice_type'] ?? 'sales'),
            'status' => 'Draft',
            'created_by' => Auth::id()
        ]));

        foreach ($templateItems as $index => $item) {
            $this->addInvoiceDetail($invoice, array_merge($item, [
                'line_number' => $index + 1
            ]));
        }

        $invoice->calculateTotals();
        return $invoice;
    }

    /**
     * Add invoice detail with account linking
     */
    private function addInvoiceDetail(Invoice $invoice, array $detailData): InvoiceDetail
    {
        // Auto-link to account based on item type
        if (empty($detailData['account_id']) && !empty($detailData['item_type'])) {
            $detailData['account_id'] = $this->getAccountForItemType($detailData['item_type']);
        }

        $detail = $invoice->details()->create($detailData);
        $detail->calculateTotals();

        return $detail;
    }

    /**
     * Get appropriate account for item type
     */
    private function getAccountForItemType(string $itemType): ?int
    {
        $accountMappings = [
            'freight' => '4000', // Freight Revenue
            'customs' => '4100', // Customs Clearance Revenue
            'documentation' => '4200', // Documentation Fees
            'service' => '4000', // Default to Freight Revenue
        ];

        $accountCode = $accountMappings[$itemType] ?? '4000';

        return Account::where('account_code', $accountCode)->first()?->id;
    }

    /**
     * Generate aging report
     */
    public function generateAgingReport(): array
    {
        $invoices = Invoice::with('company')
            ->where('status', '!=', 'Paid')
            ->get();

        $aging = [
            'current' => 0,
            '1-30_days' => 0,
            '31-60_days' => 0,
            '61-90_days' => 0,
            'over_90_days' => 0
        ];

        foreach ($invoices as $invoice) {
            $daysOverdue = $invoice->due_date ? now()->diffInDays($invoice->due_date) : 0;

            if ($daysOverdue <= 0) {
                $aging['current'] += $invoice->balance_amount;
            } elseif ($daysOverdue <= 30) {
                $aging['1-30_days'] += $invoice->balance_amount;
            } elseif ($daysOverdue <= 60) {
                $aging['31-60_days'] += $invoice->balance_amount;
            } elseif ($daysOverdue <= 90) {
                $aging['61-90_days'] += $invoice->balance_amount;
            } else {
                $aging['over_90_days'] += $invoice->balance_amount;
            }
        }

        return $aging;
    }

    /**
     * Get revenue analytics
     */
    public function getRevenueAnalytics(array $filters = []): array
    {
        $query = Invoice::where('invoice_type', 'sales');

        if (!empty($filters['date_from'])) {
            $query->where('invoice_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('invoice_date', '<=', $filters['date_to']);
        }

        $invoices = $query->get();

        return [
            'total_revenue' => $invoices->sum('total_amount'),
            'paid_revenue' => $invoices->where('status', 'Paid')->sum('total_amount'),
            'outstanding_revenue' => $invoices->whereIn('status', ['Sent', 'Overdue'])->sum('balance_amount'),
            'by_month' => $this->getMonthlyRevenue($invoices),
            'by_service_type' => $this->getRevenueByServiceType($invoices),
            'top_customers' => $this->getTopCustomersByRevenue($invoices)
        ];
    }

    /**
     * Get monthly revenue breakdown
     */
    private function getMonthlyRevenue($invoices): array
    {
        return $invoices->groupBy(fn($invoice) => $invoice->invoice_date->format('Y-m'))
            ->map(fn($group) => [
                'month' => $group->first()->invoice_date->format('M Y'),
                'total_amount' => $group->sum('total_amount'),
                'paid_amount' => $group->where('status', 'Paid')->sum('total_amount')
            ])
            ->values()
            ->toArray();
    }

    /**
     * Get revenue by service type
     */
    private function getRevenueByServiceType($invoices): array
    {
        return $invoices->flatMap->details
            ->groupBy('item_type')
            ->map(fn($group) => $group->sum('final_amount'))
            ->toArray();
    }

    /**
     * Get top customers by revenue
     */
    private function getTopCustomersByRevenue($invoices): array
    {
        return $invoices->groupBy('company_id')
            ->map(fn($group) => [
                'company' => $group->first()->company,
                'total_revenue' => $group->sum('total_amount'),
                'invoice_count' => $group->count()
            ])
            ->sortByDesc('total_revenue')
            ->take(10)
            ->values()
            ->toArray();
    }
}
