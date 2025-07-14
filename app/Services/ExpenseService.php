<?php

namespace App\Services;

use App\Models\{Expense, Employee, Shipment, Account};
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Http\UploadedFile;
use Exception;

class ExpenseService extends BaseAccountingService
{
    /**
     * Create a new expense
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Handle receipt upload
            $receiptPath = null;
            if (isset($data['receipt']) && $data['receipt'] instanceof UploadedFile) {
                $receiptPath = $this->handleReceiptUpload($data['receipt']);
            }

            // Determine initial status
            $status = $this->determineInitialStatus($data);

            // Create expense record
            $expense = Expense::create([
                'expense_number' => $this->generateExpenseNumber(),
                'employee_id' => $data['employee_id'] ?? null,
                'shipment_id' => $data['shipment_id'] ?? null,
                'account_id' => $data['account_id'],
                'category' => $data['category'],
                'description' => $data['description'],
                'amount' => $data['amount'],
                'expense_date' => $data['expense_date'],
                'status' => $status,
                'receipt_path' => $receiptPath,
                'notes' => $data['notes'] ?? null,
            ]);

            // Create journal entry if auto-approved
            if ($status === 'approved') {
                $this->createExpenseJournalEntry($expense);
            }

            // Send notification if needed
            $this->sendExpenseNotification($expense, 'created');

            return $expense;
        });
    }

    /**
     * Update an existing expense
     */
    public function update(Expense $expense, array $data)
    {
        return DB::transaction(function () use ($expense, $data) {
            // Check if expense can be updated
            if (!$this->canUpdateExpense($expense)) {
                throw new Exception('This expense cannot be updated in its current status.');
            }

            $oldAmount = $expense->amount;
            $oldAccountId = $expense->account_id;

            // Handle receipt upload/update
            $receiptPath = $expense->receipt_path;
            if (isset($data['receipt']) && $data['receipt'] instanceof UploadedFile) {
                // Delete old receipt
                if ($receiptPath) {
                    Storage::disk('public')->delete($receiptPath);
                }
                $receiptPath = $this->handleReceiptUpload($data['receipt']);
            }

            // Update expense
            $expense->update([
                'employee_id' => $data['employee_id'] ?? null,
                'shipment_id' => $data['shipment_id'] ?? null,
                'account_id' => $data['account_id'],
                'category' => $data['category'],
                'description' => $data['description'],
                'amount' => $data['amount'],
                'expense_date' => $data['expense_date'],
                'receipt_path' => $receiptPath,
                'notes' => $data['notes'] ?? null,
            ]);

            // Update journal entries if approved and amount/account changed
            if (
                $expense->status === 'approved' &&
                ($oldAmount != $data['amount'] || $oldAccountId != $data['account_id'])
            ) {
                $this->updateExpenseJournalEntry($expense, $oldAmount, $oldAccountId);
            }

            return $expense;
        });
    }

    /**
     * Delete an expense
     */
    public function delete(Expense $expense)
    {
        return DB::transaction(function () use ($expense) {
            // Check if expense can be deleted
            if ($expense->status === 'paid') {
                throw new Exception('Cannot delete paid expenses.');
            }

            // Delete receipt file
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }

            // Delete journal entries
            $this->deleteExpenseJournalEntries($expense);

            // Delete expense
            $expense->delete();

            return true;
        });
    }

    /**
     * Approve an expense
     */
    public function approve(Expense $expense)
    {
        return DB::transaction(function () use ($expense) {
            if ($expense->status !== 'pending') {
                throw new Exception('Only pending expenses can be approved.');
            }

            // Update status
            $expense->update(['status' => 'approved']);

            // Create journal entry
            $this->createExpenseJournalEntry($expense);

            // Send notification
            $this->sendExpenseNotification($expense, 'approved');

            return $expense;
        });
    }

    /**
     * Reject an expense
     */
    public function reject(Expense $expense, $reason = null)
    {
        return DB::transaction(function () use ($expense, $reason) {
            if ($expense->status !== 'pending') {
                throw new Exception('Only pending expenses can be rejected.');
            }

            // Update status and add rejection reason
            $rejectionNote = $reason ? "Rejection reason: {$reason}" : 'Expense rejected';
            $notes = $expense->notes ? $expense->notes . "\n\n" . $rejectionNote : $rejectionNote;

            $expense->update([
                'status' => 'rejected',
                'notes' => $notes
            ]);

            // Send notification
            $this->sendExpenseNotification($expense, 'rejected');

            return $expense;
        });
    }

    /**
     * Mark expense as paid
     */
    public function markAsPaid(Expense $expense, array $paymentData = [])
    {
        return DB::transaction(function () use ($expense, $paymentData) {
            if ($expense->status !== 'approved') {
                throw new Exception('Only approved expenses can be marked as paid.');
            }

            // Update status
            $expense->update([
                'status' => 'paid',
                'notes' => $expense->notes . "\n\nPaid on: " . now()->format('Y-m-d H:i:s')
            ]);

            // Create payment journal entry if payment details provided
            if (!empty($paymentData)) {
                $this->createExpensePaymentJournalEntry($expense, $paymentData);
            }

            return $expense;
        });
    }

    /**
     * Get expense statistics
     */
    public function getExpenseStats($period = 'this_month', $filters = [])
    {
        $dateRange = $this->getDateRange($period);
        $query = Expense::whereBetween('expense_date', $dateRange);

        // Apply filters
        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return [
            'total_expenses' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'pending_amount' => $query->where('status', 'pending')->sum('amount'),
            'approved_amount' => $query->where('status', 'approved')->sum('amount'),
            'paid_amount' => $query->where('status', 'paid')->sum('amount'),
            'by_category' => $this->getExpensesByCategory($dateRange, $filters),
            'by_employee' => $this->getExpensesByEmployee($dateRange, $filters),
            'by_status' => $this->getExpensesByStatus($dateRange, $filters),
        ];
    }

    /**
     * Get pending expenses for approval
     */
    public function getPendingExpenses($limit = null)
    {
        $query = Expense::where('status', 'pending')
            ->with(['employee', 'shipment', 'account'])
            ->orderBy('expense_date', 'asc');

        return $limit ? $query->take($limit)->get() : $query->get();
    }

    /**
     * Get employee expense summary
     */
    public function getEmployeeExpenseSummary($employeeId, $period = 'this_month')
    {
        $dateRange = $this->getDateRange($period);

        return [
            'employee' => Employee::find($employeeId),
            'total_expenses' => Expense::where('employee_id', $employeeId)
                ->whereBetween('expense_date', $dateRange)
                ->count(),
            'total_amount' => Expense::where('employee_id', $employeeId)
                ->whereBetween('expense_date', $dateRange)
                ->sum('amount'),
            'by_category' => Expense::where('employee_id', $employeeId)
                ->whereBetween('expense_date', $dateRange)
                ->selectRaw('category, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('category')
                ->get(),
            'by_status' => Expense::where('employee_id', $employeeId)
                ->whereBetween('expense_date', $dateRange)
                ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('status')
                ->get(),
        ];
    }

    /**
     * Handle receipt file upload
     */
    private function handleReceiptUpload(UploadedFile $file)
    {
        // Validate file
        $this->validateReceiptFile($file);

        // Generate unique filename
        $filename = 'receipt_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store file
        return $file->storeAs('receipts', $filename, 'public');
    }

    /**
     * Validate receipt file
     */
    private function validateReceiptFile(UploadedFile $file)
    {
        $maxSize = config('accounting.expense.max_file_size', 5120) * 1024; // Convert KB to bytes
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];

        if ($file->getSize() > $maxSize) {
            throw new Exception('Receipt file size cannot exceed ' . ($maxSize / 1024 / 1024) . ' MB.');
        }

        if (!in_array($file->getClientOriginalExtension(), $allowedTypes)) {
            throw new Exception('Receipt file must be JPG, PNG, or PDF format.');
        }
    }

    /**
     * Determine initial expense status
     */
    private function determineInitialStatus(array $data)
    {
        // Check if auto-approval is enabled for small amounts
        $autoApprovalLimit = config('accounting.expense.auto_approval_limit', 0);

        if ($autoApprovalLimit > 0 && $data['amount'] <= $autoApprovalLimit) {
            return 'approved';
        }

        // Check if receipt is required
        $requireReceipt = config('accounting.expense.require_receipt', false);
        if ($requireReceipt && !isset($data['receipt'])) {
            return 'pending'; // Will need receipt before approval
        }

        return 'pending';
    }

    /**
     * Check if expense can be updated
     */
    private function canUpdateExpense(Expense $expense)
    {
        return in_array($expense->status, ['pending', 'rejected']);
    }

    /**
     * Create journal entry for approved expense
     */
    private function createExpenseJournalEntry(Expense $expense)
    {
        $journalEntry = $this->createJournalEntry([
            'entry_date' => $expense->expense_date,
            'reference_type' => 'expense',
            'reference_id' => $expense->id,
            'description' => "Expense {$expense->expense_number}: {$expense->description}",
            'total_debit' => $expense->amount,
            'total_credit' => $expense->amount,
        ]);

        // Debit: Expense Account
        $this->createJournalEntryLine([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $expense->account_id,
            'debit_amount' => $expense->amount,
            'description' => $expense->description
        ]);

        // Credit: Accounts Payable (if will be reimbursed) or Cash (if paid immediately)
        $creditAccountId = $expense->employee_id ?
            $this->getAccountsPayableAccountId() : // Employee reimbursement
            $this->getCashAccountId(); // Direct payment

        $this->createJournalEntryLine([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $creditAccountId,
            'credit_amount' => $expense->amount,
            'description' => $expense->employee_id ? 'Amount owed to employee' : 'Cash payment'
        ]);
    }

    /**
     * Update journal entry when expense is modified
     */
    private function updateExpenseJournalEntry(Expense $expense, $oldAmount, $oldAccountId)
    {
        // Delete old journal entries
        $this->deleteExpenseJournalEntries($expense);

        // Create new journal entries
        $this->createExpenseJournalEntry($expense);
    }

    /**
     * Create journal entry when expense is paid
     */
    private function createExpensePaymentJournalEntry(Expense $expense, array $paymentData)
    {
        $journalEntry = $this->createJournalEntry([
            'entry_date' => $paymentData['payment_date'] ?? now(),
            'reference_type' => 'expense_payment',
            'reference_id' => $expense->id,
            'description' => "Payment for expense {$expense->expense_number}",
            'total_debit' => $expense->amount,
            'total_credit' => $expense->amount,
        ], );

        // Debit: Accounts Payable (clear the liability)
        $this->createJournalEntryLine([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $this->getAccountsPayableAccountId(),
            'debit_amount' => $expense->amount,
            'description' => 'Expense payment made'
        ]);

        // Credit: Cash/Bank Account
        $cashAccountId = $this->getCashAccountId($paymentData['method'] ?? 'cash');
        $this->createJournalEntryLine([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $cashAccountId,
            'credit_amount' => $expense->amount,
            'description' => 'Cash paid for expense'
        ]);
    }

    /**
     * Delete journal entries for expense
     */
    private function deleteExpenseJournalEntries(Expense $expense)
    {
        $this->deleteJournalEntriesByReference('expense', $expense->id);
        $this->deleteJournalEntriesByReference('expense_payment', $expense->id);
    }

    /**
     * Get expenses by category
     */
    private function getExpensesByCategory($dateRange, $filters = [])
    {
        $query = Expense::whereBetween('expense_date', $dateRange);

        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->selectRaw('category, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Get expenses by employee
     */
    private function getExpensesByEmployee($dateRange, $filters = [])
    {
        $query = Expense::whereBetween('expense_date', $dateRange)
            ->whereNotNull('employee_id')
            ->with('employee');

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query->selectRaw('employee_id, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('employee_id')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Get expenses by status
     */
    private function getExpensesByStatus($dateRange, $filters = [])
    {
        $query = Expense::whereBetween('expense_date', $dateRange);

        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();
    }

    /**
     * Send expense notification
     */
    private function sendExpenseNotification(Expense $expense, $action)
    {
        // Implementation would depend on your notification system
        // This is a placeholder for notification logic

        if (config('accounting.notifications.expense_approval', true)) {
            // Send email/notification based on action
            switch ($action) {
                case 'created':
                    // Notify managers about new expense
                    break;
                case 'approved':
                    // Notify employee about approval
                    break;
                case 'rejected':
                    // Notify employee about rejection
                    break;
            }
        }
    }

    /**
     * Export expenses to CSV/Excel
     */
    public function exportExpenses($filters = [], $format = 'csv')
    {
        $query = Expense::with(['employee', 'account', 'shipment']);

        // Apply filters
        if (isset($filters['date_from'])) {
            $query->whereDate('expense_date', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to'])) {
            $query->whereDate('expense_date', '<=', $filters['date_to']);
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();

        return [
            'data' => $expenses,
            'filename' => "expenses_export_" . now()->format('Y-m-d_H-i-s'),
            'format' => $format
        ];
    }

    /**
     * Bulk approve expenses
     */
    public function bulkApprove(array $expenseIds)
    {
        return DB::transaction(function () use ($expenseIds) {
            $expenses = Expense::whereIn('id', $expenseIds)
                ->where('status', 'pending')
                ->get();

            $approved = [];
            foreach ($expenses as $expense) {
                $this->approve($expense);
                $approved[] = $expense;
            }

            return $approved;
        });
    }

    /**
     * Bulk reject expenses
     */
    public function bulkReject(array $expenseIds, $reason = null)
    {
        return DB::transaction(function () use ($expenseIds, $reason) {
            $expenses = Expense::whereIn('id', $expenseIds)
                ->where('status', 'pending')
                ->get();

            $rejected = [];
            foreach ($expenses as $expense) {
                $this->reject($expense, $reason);
                $rejected[] = $expense;
            }

            return $rejected;
        });
    }
}
