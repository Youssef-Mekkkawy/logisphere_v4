<?php

namespace App\Services;

use App\Models\{Payment, Invoice};
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentService extends BaseAccountingService
{
    /**
     * Record a new payment
     */
    public function record(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Get the invoice
            $invoice = Invoice::findOrFail($data['invoice_id']);

            // Validate payment amount
            $this->validatePaymentAmount($invoice, $data['amount']);

            // Create payment record
            $payment = Payment::create([
                'payment_number' => $this->generatePaymentNumber(),
                'invoice_id' => $invoice->id,
                'company_id' => $invoice->company_id,
                'type' => $invoice->type === 'receivable' ? 'received' : 'sent',
                'method' => $data['method'],
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'],
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Update invoice balance
            $this->updateInvoiceBalance($invoice);

            // Create journal entry for double-entry bookkeeping
            $this->createPaymentJournalEntry($payment);

            return $payment;
        });
    }

    /**
     * Update an existing payment
     */
    public function update(Payment $payment, array $data)
    {
        return DB::transaction(function () use ($payment, $data) {
            $oldAmount = $payment->amount;
            $invoice = $payment->invoice;

            // Validate new payment amount
            $availableBalance = $invoice->balance_due + $oldAmount; // Add back old amount
            if ($data['amount'] > $availableBalance) {
                throw new Exception("Payment amount cannot exceed available balance of $" . number_format($availableBalance, 2));
            }

            // Update payment
            $payment->update([
                'method' => $data['method'],
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'],
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Update invoice balance
            $this->updateInvoiceBalance($invoice);

            // Update journal entries
            $this->updatePaymentJournalEntry($payment, $oldAmount);

            return $payment;
        });
    }

    /**
     * Delete a payment
     */
    public function delete(Payment $payment)
    {
        return DB::transaction(function () use ($payment) {
            $invoice = $payment->invoice;

            // Remove journal entries
            $this->deletePaymentJournalEntries($payment);

            // Delete payment
            $payment->delete();

            // Update invoice balance
            $this->updateInvoiceBalance($invoice);

            return true;
        });
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats($period = 'this_month')
    {
        $dateRange = $this->getDateRange($period);

        return [
            'total_payments' => Payment::whereBetween('payment_date', $dateRange)->count(),
            'total_amount' => Payment::whereBetween('payment_date', $dateRange)->sum('amount'),
            'received_amount' => Payment::where('type', 'received')
                ->whereBetween('payment_date', $dateRange)
                ->sum('amount'),
            'sent_amount' => Payment::where('type', 'sent')
                ->whereBetween('payment_date', $dateRange)
                ->sum('amount'),
            'by_method' => Payment::whereBetween('payment_date', $dateRange)
                ->selectRaw('method, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('method')
                ->get(),
        ];
    }

    /**
     * Get overdue payments (for payables)
     */
    public function getOverduePayments()
    {
        return Payment::where('type', 'sent')
            ->whereHas('invoice', function ($query) {
                $query->where('due_date', '<', now())
                    ->where('balance_due', '>', 0);
            })
            ->with(['invoice.company'])
            ->get();
    }

    /**
     * Validate payment amount against invoice balance
     */
    private function validatePaymentAmount(Invoice $invoice, $amount)
    {
        if ($amount <= 0) {
            throw new Exception('Payment amount must be greater than zero.');
        }

        if ($amount > $invoice->balance_due) {
            throw new Exception(
                "Payment amount ($" . number_format($amount, 2) .
                    ") cannot exceed outstanding balance ($" . number_format($invoice->balance_due, 2) . ")"
            );
        }
    }

    /**
     * Update invoice balance after payment
     */
    private function updateInvoiceBalance(Invoice $invoice)
    {
        $totalPaid = $invoice->payments()->sum('amount');
        $balanceDue = $invoice->total_amount - $totalPaid;

        // Update invoice
        $invoice->update([
            'paid_amount' => $totalPaid,
            'balance_due' => $balanceDue,
            'status' => $this->determineInvoiceStatus($invoice->total_amount, $totalPaid, $invoice->due_date)
        ]);
    }

    /**
     * Determine invoice status based on payment
     */
    private function determineInvoiceStatus($totalAmount, $paidAmount, $dueDate)
    {
        if ($paidAmount >= $totalAmount) {
            return 'paid';
        } elseif ($paidAmount > 0) {
            return 'partially_paid';
        } elseif ($dueDate->isPast()) {
            return 'overdue';
        } else {
            return 'sent';
        }
    }

    /**
     * Create journal entry for payment
     */
    private function createPaymentJournalEntry(Payment $payment)
    {
        $invoice = $payment->invoice;

        $journalEntry = $this->createJournalEntry([
            'entry_date' => $payment->payment_date,
            'reference_type' => 'payment',
            'reference_id' => $payment->id,
            'description' => "Payment {$payment->payment_number} for Invoice {$invoice->invoice_number}",
            'total_debit' => $payment->amount,
            'total_credit' => $payment->amount,
        ]);

        if ($payment->type === 'received') {
            // Money received (for receivable invoices)
            // Debit: Cash/Bank Account
            // Credit: Accounts Receivable

            $this->createJournalEntryLine([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $this->getCashAccountId($payment->method),
                'debit_amount' => $payment->amount,
                'description' => 'Cash received from customer'
            ]);

            $this->createJournalEntryLine([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $this->getAccountsReceivableAccountId(),
                'credit_amount' => $payment->amount,
                'description' => 'Payment received for invoice'
            ]);
        } else {
            // Money sent (for payable invoices)
            // Debit: Accounts Payable
            // Credit: Cash/Bank Account

            $this->createJournalEntryLine([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $this->getAccountsPayableAccountId(),
                'debit_amount' => $payment->amount,
                'description' => 'Payment made to supplier'
            ]);

            $this->createJournalEntryLine([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $this->getCashAccountId($payment->method),
                'credit_amount' => $payment->amount,
                'description' => 'Cash paid to supplier'
            ]);
        }
    }

    /**
     * Update journal entry when payment is modified
     */
    private function updatePaymentJournalEntry(Payment $payment, $oldAmount)
    {
        // Delete old journal entries
        $this->deletePaymentJournalEntries($payment);

        // Create new journal entries with updated amount
        $this->createPaymentJournalEntry($payment);
    }

    /**
     * Delete journal entries for payment
     */
    private function deletePaymentJournalEntries(Payment $payment)
    {
        $this->deleteJournalEntriesByReference('payment', $payment->id);
    }

    /**
     * Get cash account ID based on payment method
     */
    private function getCashAccountId($method)
    {
        return match ($method) {
            'cash' => $this->getAccountByCode('1000')->id, // Cash Account
            'check', 'bank_transfer' => $this->getAccountByCode('1100')->id, // Bank Account
            'credit_card' => $this->getAccountByCode('1100')->id, // Bank Account
            default => $this->getAccountByCode('1000')->id, // Default to Cash
        };
    }

    /**
     * Get accounts receivable account ID
     */
    private function getAccountsReceivableAccountId()
    {
        return $this->getAccountByCode('1200')->id; // Accounts Receivable
    }

    /**
     * Get accounts payable account ID
     */
    private function getAccountsPayableAccountId()
    {
        return $this->getAccountByCode('2000')->id; // Accounts Payable
    }

    /**
     * Process partial payment
     */
    public function processPartialPayment(Invoice $invoice, array $paymentData)
    {
        if ($paymentData['amount'] < $invoice->balance_due) {
            $paymentData['notes'] = ($paymentData['notes'] ?? '') . ' [Partial Payment]';
        }

        return $this->record($paymentData);
    }

    /**
     * Process overpayment (if allowed)
     */
    public function processOverpayment(Invoice $invoice, array $paymentData)
    {
        $overpaymentAmount = $paymentData['amount'] - $invoice->balance_due;

        if ($overpaymentAmount > 0) {
            // You might want to create a credit note or handle overpayment differently
            $paymentData['notes'] = ($paymentData['notes'] ?? '') .
                " [Overpayment: $" . number_format($overpaymentAmount, 2) . "]";
        }

        return $this->record($paymentData);
    }

    /**
     * Get payment methods summary
     */
    public function getPaymentMethodsSummary($period = 'this_month')
    {
        $dateRange = $this->getDateRange($period);

        return Payment::whereBetween('payment_date', $dateRange)
            ->selectRaw('
                method,
                COUNT(*) as transaction_count,
                SUM(amount) as total_amount,
                AVG(amount) as average_amount,
                MIN(amount) as min_amount,
                MAX(amount) as max_amount
            ')
            ->groupBy('method')
            ->orderByDesc('total_amount')
            ->get();
    }

    /**
     * Export payments to CSV/Excel
     */
    public function exportPayments($period = 'this_month', $format = 'csv')
    {
        $dateRange = $this->getDateRange($period);

        $payments = Payment::with(['invoice.company'])
            ->whereBetween('payment_date', $dateRange)
            ->get();

        // Implementation would depend on your export library
        // This is a placeholder for the export logic

        return [
            'data' => $payments,
            'filename' => "payments_export_" . now()->format('Y-m-d_H-i-s'),
            'format' => $format
        ];
    }
}
