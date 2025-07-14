<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class InvoiceService extends BaseAccountingService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $totals = $this->calculateInvoiceTotals($data['line_items']);

            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'company_id' => $data['company_id'],
                'shipment_id' => $data['shipment_id'] ?? null,
                'type' => $data['type'],
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'subtotal' => $totals['subtotal'],
                'tax_amount' => $totals['tax_amount'],
                'total_amount' => $totals['total_amount'],
                'balance_due' => $totals['total_amount'],
                'line_items' => $data['line_items'],
                'notes' => $data['notes'] ?? null,
            ]);

            $this->createJournalEntry($invoice, 'invoice_created');

            return $invoice;
        });
    }

    public function update(Invoice $invoice, array $data)
    {
        if (!$invoice->isDraft()) {
            throw new \Exception('Only draft invoices can be edited.');
        }

        return DB::transaction(function () use ($invoice, $data) {
            $totals = $this->calculateInvoiceTotals($data['line_items']);

            $invoice->update([
                'company_id' => $data['company_id'],
                'shipment_id' => $data['shipment_id'] ?? null,
                'type' => $data['type'],
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'subtotal' => $totals['subtotal'],
                'tax_amount' => $totals['tax_amount'],
                'total_amount' => $totals['total_amount'],
                'balance_due' => $totals['total_amount'] - $invoice->paid_amount,
                'line_items' => $data['line_items'],
                'notes' => $data['notes'] ?? null,
            ]);

            return $invoice;
        });
    }

    public function delete(Invoice $invoice)
    {
        if ($invoice->payments()->exists()) {
            throw new \Exception('Cannot delete invoice with recorded payments.');
        }

        return DB::transaction(function () use ($invoice) {
            $this->deleteRelatedJournalEntries($invoice);
            $invoice->delete();
        });
    }

    public function sendToClient(Invoice $invoice)
    {
        // Implementation for sending invoice to client
        $invoice->update(['status' => 'sent']);

        // Add email notification logic here

        return $invoice;
    }

    private function calculateInvoiceTotals(array $lineItems)
    {
        $subtotal = collect($lineItems)->sum(fn($item) => $item['quantity'] * $item['rate']);
        $taxAmount = $subtotal * (config('accounting.tax.default_rate') / 100);

        return [
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $subtotal + $taxAmount,
        ];
    }
}
