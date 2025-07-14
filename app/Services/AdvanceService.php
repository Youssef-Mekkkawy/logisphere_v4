<?php

// ================================================================================================
// 3. ADVANCE SERVICE - COMPLETE IMPLEMENTATION
// ================================================================================================

// File: app/Services/AdvanceService.php
namespace App\Services;

use App\Models\{EmployeeAdvance, Employee};
use Illuminate\Support\Facades\DB;
use Exception;

class AdvanceService extends BaseAccountingService
{
    /**
     * Issue a new advance to employee
     */
    public function issue(array $data)
    {
        return DB::transaction(function () use ($data) {
            $employee = Employee::findOrFail($data['employee_id']);
            
            // Validate advance against employee salary and limits
            $this->validateAdvanceEligibility($employee, $data['amount'], $data['type']);
            
            // Check for existing outstanding advances
            $this->checkOutstandingAdvances($employee, $data['amount']);
            
            // Create advance record
            $advance = EmployeeAdvance::create([
                'advance_number' => $this->generateAdvanceNumber(),
                'employee_id' => $employee->id,
                'type' => $data['type'],
                'amount' => $data['amount'],
                'repaid_amount' => 0,
                'balance' => $data['amount'],
                'issued_date' => now()->toDateString(),
                'due_date' => $data['due_date'] ?? $this->calculateDefaultDueDate($data['type']),
                'status' => 'issued',
                'reason' => $data['reason'],
                'notes' => $data['notes'] ?? null,
            ]);
            
            // Create journal entry for advance
            $this->createAdvanceJournalEntry($advance);
            
            // Update employee advance balance
            $this->updateEmployeeAdvanceBalance($employee);
            
            // Send notification
            $this->sendAdvanceNotification($advance, 'issued');
            
            // Log activity
            $this->logActivity('advance_issued', $advance);
            
            return $advance;
        });
    }

    /**
     * Record repayment for an advance
     */
    public function recordRepayment(EmployeeAdvance $advance, array $data)
    {
        return DB::transaction(function () use ($advance, $data) {
            // Validate repayment amount
            $this->validateRepaymentAmount($advance, $data['amount']);
            
            $oldRepaidAmount = $advance->repaid_amount;
            $newRepaidAmount = $oldRepaidAmount + $data['amount'];
            $newBalance = $advance->amount - $newRepaidAmount;
            
            // Determine new status
            $newStatus = $this->determineAdvanceStatus($advance->amount, $newRepaidAmount);
            
            // Update advance
            $advance->update([
                'repaid_amount' => $newRepaidAmount,
                'balance' => $newBalance,
                'status' => $newStatus,
                'notes' => $this->appendRepaymentNote($advance->notes, $data)
            ]);
            
            // Create journal entry for repayment
            $this->createRepaymentJournalEntry($advance, $data['amount'], $data);
            
            // Update employee advance balance
            $this->updateEmployeeAdvanceBalance($advance->employee);
            
            // Send notification
            $this->sendAdvanceNotification($advance, 'repayment_made');
            
            // Log activity
            $this->logActivity('advance_repayment', $advance, [
                'repayment_amount' => $data['amount'],
                'method' => $data['method']
            ]);
            
            return $advance;
        });
    }

    /**
     * Write off an advance (bad debt)
     */
    public function writeOff(EmployeeAdvance $advance, $reason = null)
    {
        return DB::transaction(function () use ($advance, $reason) {
            if ($advance->balance <= 0) {
                throw new Exception('Cannot write off advance with zero balance.');
            }
            
            $writeOffAmount = $advance->balance;
            
            // Update advance status
            $advance->update([
                'status' => 'written_off',
                'notes' => $this->appendWriteOffNote($advance->notes, $reason, $writeOffAmount)
            ]);
            
            // Create journal entry for write-off
            $this->createWriteOffJournalEntry($advance, $writeOffAmount, $reason);
            
            // Update employee advance balance
            $this->updateEmployeeAdvanceBalance($advance->employee);
            
            // Send notification
            $this->sendAdvanceNotification($advance, 'written_off');
            
            // Log activity
            $this->logActivity('advance_written_off', $advance, [
                'write_off_amount' => $writeOffAmount,
                'reason' => $reason
            ]);
            
            return $advance;
        });
    }

    /**
     * Update an existing advance (only if not fully repaid)
     */
    public function update(EmployeeAdvance $advance, array $data)
    {
        return DB::transaction(function () use ($advance, $data) {
            // Check if advance can be updated
            if (!$this->canUpdateAdvance($advance)) {
                throw new Exception('This advance cannot be updated in its current status.');
            }
            
            $employee = Employee::findOrFail($data['employee_id']);
            
            // If amount changed, validate again
            if ($data['amount'] != $advance->amount) {
                $this->validateAdvanceEligibility($employee, $data['amount'], $data['type']);
                
                // Recalculate balance
                $newBalance = $data['amount'] - $advance->repaid_amount;
                if ($newBalance < 0) {
                    throw new Exception('New advance amount cannot be less than already repaid amount.');
                }
            }
            
            // Update advance
            $advance->update([
                'employee_id' => $employee->id,
                'type' => $data['type'],
                'amount' => $data['amount'],
                'balance' => $data['amount'] - $advance->repaid_amount,
                'due_date' => $data['due_date'],
                'reason' => $data['reason'],
                'notes' => $data['notes'] ?? null,
            ]);
            
            // Update journal entries if amount changed
            if ($data['amount'] != $advance->getOriginal('amount')) {
                $this->updateAdvanceJournalEntry($advance);
            }
            
            return $advance;
        });
    }

    /**
     * Delete an advance (only if no repayments made)
     */
    public function delete(EmployeeAdvance $advance)
    {
        return DB::transaction(function () use ($advance) {
            if ($advance->repaid_amount > 0) {
                throw new Exception('Cannot delete advance with recorded repayments.');
            }
            
            // Delete journal entries
            $this->deleteAdvanceJournalEntries($advance);
            
            // Delete advance
            $advance->delete();
            
            // Update employee advance balance
            $this->updateEmployeeAdvanceBalance($advance->employee);
            
            return true;
        });
    }

    /**
     * Get advance statistics
     */
    public function getAdvanceStats($period = 'this_month', $filters = [])
    {
        $dateRange = $this->getDateRange($period);
        $query = EmployeeAdvance::whereBetween('issued_date', $dateRange);
        
        // Apply filters
        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        return [
            'total_advances' => $query->count(),
            'total_amount_issued' => $query->sum('amount'),
            'total_amount_repaid' => $query->sum('repaid_amount'),
            'outstanding_balance' => $query->sum('balance'),
            'by_type' => $this->getAdvancesByType($dateRange, $filters),
            'by_employee' => $this->getAdvancesByEmployee($dateRange, $filters),
            'by_status' => $this->getAdvancesByStatus($dateRange, $filters),
            'overdue_advances' => $this->getOverdueAdvances($filters),
        ];
    }

    /**
     * Get employee advance summary
     */
    public function getEmployeeAdvanceSummary($employeeId, $includeHistory = true)
    {
        $employee = Employee::findOrFail($employeeId);
        
        $summary = [
            'employee' => $employee,
            'current_outstanding' => EmployeeAdvance::where('employee_id', $employeeId)
                ->where('balance', '>', 0)
                ->sum('balance'),
            'total_advances_issued' => EmployeeAdvance::where('employee_id', $employeeId)
                ->count(),
            'total_amount_issued' => EmployeeAdvance::where('employee_id', $employeeId)
                ->sum('amount'),
            'total_amount_repaid' => EmployeeAdvance::where('employee_id', $employeeId)
                ->sum('repaid_amount'),
            'advance_limit_available' => $this->calculateAvailableAdvanceLimit($employee),
        ];
        
        if ($includeHistory) {
            $summary['advance_history'] = EmployeeAdvance::where('employee_id', $employeeId)
                ->orderBy('issued_date', 'desc')
                ->take(10)
                ->get();
        }
        
        return $summary;
    }

    /**
     * Get overdue advances
     */
    public function getOverdueAdvances($filters = [])
    {
        $query = EmployeeAdvance::where('due_date', '<', now())
            ->where('balance', '>', 0)
            ->with('employee');
            
        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        
        return $query->get();
    }

    /**
     * Calculate available advance limit for employee
     */
    public function calculateAvailableAdvanceLimit(Employee $employee)
    {
        $maxPercentage = config('accounting.advance.max_percentage', 75);
        $maxAdvanceAmount = ($employee->salary * $maxPercentage) / 100;
        
        $currentOutstanding = EmployeeAdvance::where('employee_id', $employee->id)
            ->where('balance', '>', 0)
            ->sum('balance');
            
        return max(0, $maxAdvanceAmount - $currentOutstanding);
    }

    /**
     * Validate advance eligibility
     */
    private function validateAdvanceEligibility(Employee $employee, $amount, $type)
    {
        // Check if employee is active
        if ($employee->status !== 'Active') {
            throw new Exception('Cannot issue advance to inactive employee.');
        }
        
        // Check amount limits
        $this->validateAmount($amount);
        
        // Check against salary percentage
        $maxPercentage = config('accounting.advance.max_percentage', 75);
        $maxAmount = ($employee->salary * $maxPercentage) / 100;
        
        if ($amount > $maxAmount) {
            throw new Exception(
                "Advance amount cannot exceed {$maxPercentage}% of salary (" . 
                $this->formatCurrency($maxAmount) . ")"
            );
        }
        
        // Check minimum employment period for salary advances
        if ($type === 'salary') {
            $minEmploymentDays = config('accounting.advance.min_employment_days', 90);
            $employmentDays = $employee->hire_date->diffInDays(now());
            
            if ($employmentDays < $minEmploymentDays) {
                throw new Exception(
                    "Employee must be employed for at least {$minEmploymentDays} days for salary advance."
                );
            }
        }
    }

    /**
     * Check outstanding advances limit
     */
    private function checkOutstandingAdvances(Employee $employee, $newAmount)
    {
        $currentOutstanding = EmployeeAdvance::where('employee_id', $employee->id)
            ->where('balance', '>', 0)
            ->sum('balance');
            
        $totalAfterNew = $currentOutstanding + $newAmount;
        $availableLimit = $this->calculateAvailableAdvanceLimit($employee);
        
        if ($newAmount > $availableLimit) {
            throw new Exception(
                "This advance would exceed the available limit. Available: " . 
                $this->formatCurrency($availableLimit)
            );
        }
    }

    /**
     * Calculate default due date based on advance type
     */
    private function calculateDefaultDueDate($type)
    {
        return match($type) {
            'salary' => now()->addDays(30), // 1 month for salary advance
            'travel' => now()->addDays(7),  // 1 week for travel advance
            'emergency' => now()->addDays(14), // 2 weeks for emergency
            'project' => now()->addDays(60), // 2 months for project advance
            default => now()->addDays(30), // Default 1 month
        };
    }

    /**
     * Validate repayment amount
     */
    private function validateRepaymentAmount(EmployeeAdvance $advance, $amount)
    {
        if ($amount <= 0) {
            throw new Exception('Repayment amount must be greater than zero.');
        }
        
        if ($amount > $advance->balance) {
            throw new Exception(
                "Repayment amount cannot exceed outstanding balance (" . 
                $this->formatCurrency($advance->balance) . ")"
            );
        }
    }

    /**
     * Determine advance status based on repayment
     */
    private function determineAdvanceStatus($totalAmount, $repaidAmount)
    {
        if ($repaidAmount >= $totalAmount) {
            return 'fully_repaid';
        } elseif ($repaidAmount > 0) {
            return 'partially_repaid';
        } else {
            return 'issued';
        }
    }

    /**
     * Check if advance can be updated
     */
    private function canUpdateAdvance(EmployeeAdvance $advance)
    {
        return in_array($advance->status, ['issued', 'partially_repaid']);
    }

    /**
     * Create journal entry for advance issuance
     */
    private function createAdvanceJournalEntry(EmployeeAdvance $advance)
    {
        $journalEntry = $this->createJournalEntry([
            'entry_date' => $advance->issued_date,
            'reference_type' => 'advance',
            'reference_id' => $advance->id,
            'description' => "Employee advance {$advance->advance_number} - {$advance->employee->name}",
            'total_debit' => $advance->amount,
            'total_credit' => $advance->amount,
        ]);

        // Debit: Employee Advances (Asset account)
        $this->createJournalEntryLine([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $this->getEmployeeAdvancesAccountId(),
            'debit_amount' => $advance->amount,
            'description' => "Advance issued to {$advance->employee->name}"
        ]);
        
        // Credit: Cash (Asset account)
        $this->createJournalEntryLine([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $this->getCashAccountId(),
            'credit_amount' => $advance->amount,
            'description' => 'Cash advance payment'
        ]);
    }

    /**
     * Create journal entry for advance repayment
     */
    private function createRepaymentJournalEntry(EmployeeAdvance $advance, $amount, array $data)
    {
        $journalEntry = $this->createJournalEntry([
            'entry_date' => $data['repayment_date'] ?? now(),
            'reference_type' => 'advance_repayment',
            'reference_id' => $advance->id,
            'description' => "Repayment for advance {$advance->advance_number}",
            'total_debit' => $amount,
            'total_credit' => $amount,
        ]);

        // Determine accounts based on repayment method
        if ($data['method'] === 'salary_deduction') {
            // Debit: Salary Expense, Credit: Employee Advances
            $this->createJournalEntryLine([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $this->getSalaryExpenseAccountId(),
                'debit_amount' => $amount,
                'description' => 'Salary deduction for advance repayment'
            ]);
        } else {
            // Debit: Cash, Credit: Employee Advances
            $this->createJournalEntryLine([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $this->getCashAccountId($data['method']),
                'debit_amount' => $amount,
                'description' => 'Cash repayment received'
            ]);
        }
        
        // Credit: Employee Advances (reduce the asset)
        $this->createJournalEntryLine([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $this->getEmployeeAdvancesAccountId(),
            'credit_amount' => $amount,
            'description' => 'Advance repayment received'
        ]);
    }

    /**
     * Create journal entry for advance write-off
     */
    private function createWriteOffJournalEntry(EmployeeAdvance $advance, $amount, $reason)
    {
        $journalEntry = $this->createJournalEntry([
            'entry_date' => now(),
            'reference_type' => 'advance_writeoff',
            'reference_id' => $advance->id,
            'description' => "Write-off advance {$advance->advance_number}: {$reason}",
            'total_debit' => $amount,
            'total_credit' => $amount,
        ]);

        // Debit: Bad Debt Expense
        $this->createJournalEntryLine([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $this->getBadDebtExpenseAccountId(),
            'debit_amount' => $amount,
            'description' => "Write-off: {$reason}"
        ]);
        
        // Credit: Employee Advances (remove the asset)
        $this->createJournalEntryLine([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $this->getEmployeeAdvancesAccountId(),
            'credit_amount' => $amount,
            'description' => 'Advance written off as bad debt'
        ]);
    }

    /**
     * Update advance journal entry
     */
    private function updateAdvanceJournalEntry(EmployeeAdvance $advance)
    {
        // Delete old journal entries
        $this->deleteAdvanceJournalEntries($advance);
        
        // Create new journal entries
        $this->createAdvanceJournalEntry($advance);
    }

    /**
     * Delete all journal entries for advance
     */
    private function deleteAdvanceJournalEntries(EmployeeAdvance $advance)
    {
        $this->deleteJournalEntriesByReference('advance', $advance->id);
        $this->deleteJournalEntriesByReference('advance_repayment', $advance->id);
        $this->deleteJournalEntriesByReference('advance_writeoff', $advance->id);
    }

    /**
     * Update employee's total advance balance
     */
    private function updateEmployeeAdvanceBalance(Employee $employee)
    {
        $totalOutstanding = EmployeeAdvance::where('employee_id', $employee->id)
            ->where('balance', '>', 0)
            ->sum('balance');
            
        // You could store this in employee table if needed
        // $employee->update(['advance_balance' => $totalOutstanding]);
    }

    /**
     * Append repayment note
     */
    private function appendRepaymentNote($existingNotes, array $data)
    {
        $note = "\nRepayment: " . $this->formatCurrency($data['amount']) . 
                " via " . ucwords(str_replace('_', ' ', $data['method'])) . 
                " on " . now()->format('Y-m-d');
                
        return $existingNotes ? $existingNotes . $note : $note;
    }

    /**
     * Append write-off note
     */
    private function appendWriteOffNote($existingNotes, $reason, $amount)
    {
        $note = "\nWrite-off: " . $this->formatCurrency($amount) . 
                " on " . now()->format('Y-m-d') . 
                ($reason ? " - Reason: {$reason}" : "");
                
        return $existingNotes ? $existingNotes . $note : $note;
    }

    /**
     * Get advances by type
     */
    private function getAdvancesByType($dateRange, $filters = [])
    {
        $query = EmployeeAdvance::whereBetween('issued_date', $dateRange);
        
        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        
        return $query->selectRaw('type, COUNT(*) as count, SUM(amount) as total_issued, SUM(balance) as outstanding')
            ->groupBy('type')
            ->orderByDesc('total_issued')
            ->get();
    }

    /**
     * Get advances by employee
     */
    private function getAdvancesByEmployee($dateRange, $filters = [])
    {
        $query = EmployeeAdvance::whereBetween('issued_date', $dateRange)
            ->with('employee');
            
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        return $query->selectRaw('employee_id, COUNT(*) as count, SUM(amount) as total_issued, SUM(balance) as outstanding')
            ->groupBy('employee_id')
            ->orderByDesc('total_issued')
            ->get();
    }

    /**
     * Get advances by status
     */
    private function getAdvancesByStatus($dateRange, $filters = [])
    {
        $query = EmployeeAdvance::whereBetween('issued_date', $dateRange);
        
        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        return $query->selectRaw('status, COUNT(*) as count, SUM(amount) as total_issued, SUM(balance) as outstanding')
            ->groupBy('status')
            ->get();
    }

    /**
     * Get employee advances account ID
     */
    private function getEmployeeAdvancesAccountId()
    {
        return $this->getAccountByCode('1300')->id; // Employee Advances (Asset)
    }

    /**
     * Get salary expense account ID
     */
    private function getSalaryExpenseAccountId()
    {
        return $this->getAccountByCode('5200')->id; // Employee Salaries
    }

    /**
     * Get bad debt expense account ID
     */
    private function getBadDebtExpenseAccountId()
    {
        return $this->getAccountByCode('5700')->id; // Bad Debt Expense
    }

    /**
     * Send advance notification
     */
    private function sendAdvanceNotification(EmployeeAdvance $advance, $action)
    {
        $this->sendNotification("advance_{$action}", [
            'advance' => $advance,
            'employee' => $advance->employee,
            'action' => $action
        ]);
    }

    /**
     * Export advances to CSV/Excel
     */
    public function exportAdvances($filters = [], $format = 'csv')
    {
        $query = EmployeeAdvance::with('employee');
        
        // Apply filters
        if (isset($filters['date_from'])) {
            $query->whereDate('issued_date', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to'])) {
            $query->whereDate('issued_date', '<=', $filters['date_to']);
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        
        $advances = $query->orderBy('issued_date', 'desc')->get();
        
        return [
            'data' => $advances,
            'filename' => "advances_export_" . now()->format('Y-m-d_H-i-s'),
            'format' => $format
        ];
    }
}

