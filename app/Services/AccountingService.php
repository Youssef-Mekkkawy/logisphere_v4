<?php

namespace App\Services;

use App\Models\{Invoice, Payment, Expense, EmployeeAdvance, JobAssignment, Account, JournalEntry, JournalEntryLine};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    public function createInvoice(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Calculate totals
            $subtotal = 0;
            foreach ($data['line_items'] as $item) {
                $subtotal += $item['quantity'] * $item['rate'];
            }

            $taxAmount = $subtotal * 0.14; // 14% tax (adjust as needed)
            $totalAmount = $subtotal + $taxAmount;

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'company_id' => $data['company_id'],
                'shipment_id' => $data['shipment_id'] ?? null,
                'type' => $data['type'],
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'balance_due' => $totalAmount,
                'line_items' => $data['line_items'],
                'notes' => $data['notes'] ?? null
            ]);

            // Create journal entry
            $this->createInvoiceJournalEntry($invoice);

            return $invoice;
        });
    }

    public function recordPayment(array $data)
    {
        return DB::transaction(function () use ($data) {
            $invoice = Invoice::findOrFail($data['invoice_id']);

            $payment = Payment::create([
                'payment_number' => $this->generatePaymentNumber(),
                'invoice_id' => $invoice->id,
                'company_id' => $invoice->company_id,
                'type' => $invoice->type === 'receivable' ? 'received' : 'sent',
                'method' => $data['method'],
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'],
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null
            ]);

            // Update invoice balance
            $invoice->updateBalanceDue();

            // Create journal entry
            $this->createPaymentJournalEntry($payment);

            return $payment;
        });
    }

    public function createExpense(array $data)
    {
        return DB::transaction(function () use ($data) {
            $expense = Expense::create([
                'expense_number' => $this->generateExpenseNumber(),
                'employee_id' => $data['employee_id'] ?? null,
                'shipment_id' => $data['shipment_id'] ?? null,
                'account_id' => $data['account_id'],
                'category' => $data['category'],
                'description' => $data['description'],
                'amount' => $data['amount'],
                'expense_date' => $data['expense_date'],
                'notes' => $data['notes'] ?? null
            ]);

            // Handle file upload if present
            if (isset($data['receipt'])) {
                $path = $data['receipt']->store('receipts', 'public');
                $expense->update(['receipt_path' => $path]);
            }

            // Create journal entry if approved
            if ($expense->status === 'approved') {
                $this->createExpenseJournalEntry($expense);
            }

            return $expense;
        });
    }

    public function createAdvance(array $data)
    {
        return DB::transaction(function () use ($data) {
            $advance = EmployeeAdvance::create([
                'advance_number' => $this->generateAdvanceNumber(),
                'employee_id' => $data['employee_id'],
                'type' => $data['type'],
                'amount' => $data['amount'],
                'balance' => $data['amount'],
                'issued_date' => now(),
                'status' => 'issued',
                'reason' => $data['reason'],
                'notes' => $data['notes'] ?? null
            ]);

            // Create journal entry
            $this->createAdvanceJournalEntry($advance);

            return $advance;
        });
    }

    public function generateReport(string $type, string $period)
    {
        switch ($type) {
            case 'profit_loss':
                return $this->generateProfitLossReport($period);
            case 'balance_sheet':
                return $this->generateBalanceSheetReport($period);
            case 'cash_flow':
                return $this->generateCashFlowReport($period);
            case 'aging_report':
                return $this->generateAgingReport();
            default:
                return [];
        }
    }

    private function generateProfitLossReport(string $period)
    {
        $dateRange = $this->getDateRange($period);

        $revenue = Invoice::where('type', 'receivable')
            ->where('status', 'paid')
            ->whereBetween('invoice_date', $dateRange)
            ->sum('total_amount');

        $expenses = Expense::where('status', 'approved')
            ->whereBetween('expense_date', $dateRange)
            ->sum('amount');

        return [
            'period' => $period,
            'revenue' => $revenue,
            'expenses' => $expenses,
            'net_profit' => $revenue - $expenses,
            'profit_margin' => $revenue > 0 ? (($revenue - $expenses) / $revenue) * 100 : 0
        ];
    }

    private function generateBalanceSheetReport(string $period)
    {
        $assets = Account::where('type', 'asset')->sum('balance');
        $liabilities = Account::where('type', 'liability')->sum('balance');
        $equity = Account::where('type', 'equity')->sum('balance');

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'total_equity_liabilities' => $liabilities + $equity
        ];
    }

    private function createInvoiceJournalEntry(Invoice $invoice)
    {
        $entry = JournalEntry::create([
            'entry_number' => $this->generateJournalEntryNumber(),
            'entry_date' => $invoice->invoice_date,
            'reference_type' => 'invoice',
            'reference_id' => $invoice->id,
            'description' => "Invoice {$invoice->invoice_number}",
            'total_debit' => $invoice->total_amount,
            'total_credit' => $invoice->total_amount
        ]);

        if ($invoice->type === 'receivable') {
            // Debit: Accounts Receivable, Credit: Revenue
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => Account::where('code', '1200')->first()->id, // Accounts Receivable
                'debit_amount' => $invoice->total_amount,
                'description' => 'Accounts Receivable'
            ]);

            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => Account::where('code', '4000')->first()->id, // Revenue
                'credit_amount' => $invoice->total_amount,
                'description' => 'Service Revenue'
            ]);
        }
    }

    private function createPaymentJournalEntry(Payment $payment)
    {
        $entry = JournalEntry::create([
            'entry_number' => $this->generateJournalEntryNumber(),
            'entry_date' => $payment->payment_date,
            'reference_type' => 'payment',
            'reference_id' => $payment->id,
            'description' => "Payment {$payment->payment_number}",
            'total_debit' => $payment->amount,
            'total_credit' => $payment->amount
        ]);

        if ($payment->type === 'received') {
            // Debit: Cash, Credit: Accounts Receivable
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => Account::where('code', '1000')->first()->id, // Cash
                'debit_amount' => $payment->amount,
                'description' => 'Cash received'
            ]);

            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => Account::where('code', '1200')->first()->id, // Accounts Receivable
                'credit_amount' => $payment->amount,
                'description' => 'Payment received'
            ]);
        }
    }

    private function generateInvoiceNumber()
    {
        $lastInvoice = Invoice::latest()->first();
        $number = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -4) + 1 : 1;
        return 'INV-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    private function generatePaymentNumber()
    {
        $lastPayment = Payment::latest()->first();
        $number = $lastPayment ? (int) substr($lastPayment->payment_number, -4) + 1 : 1;
        return 'PAY-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    private function generateExpenseNumber()
    {
        $lastExpense = Expense::latest()->first();
        $number = $lastExpense ? (int) substr($lastExpense->expense_number, -4) + 1 : 1;
        return 'EXP-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    private function generateAdvanceNumber()
    {
        $lastAdvance = EmployeeAdvance::latest()->first();
        $number = $lastAdvance ? (int) substr($lastAdvance->advance_number, -4) + 1 : 1;
        return 'ADV-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    private function generateJournalEntryNumber()
    {
        $lastEntry = JournalEntry::latest()->first();
        $number = $lastEntry ? (int) substr($lastEntry->entry_number, -4) + 1 : 1;
        return 'JE-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    private function getDateRange(string $period)
    {
        switch ($period) {
            case 'this_month':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'last_month':
                return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];
            case 'this_quarter':
                return [now()->startOfQuarter(), now()->endOfQuarter()];
            case 'this_year':
                return [now()->startOfYear(), now()->endOfYear()];
            default:
                return [now()->startOfMonth(), now()->endOfMonth()];
        }
    }
}
