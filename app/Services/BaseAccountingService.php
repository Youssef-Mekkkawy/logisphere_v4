<?php

// ================================================================================================
// COMPLETE BASE ACCOUNTING SERVICE - ALL MISSING METHODS
// ================================================================================================

// File: app/Services/BaseAccountingService.php
namespace App\Services;

use App\Models\{JournalEntry, JournalEntryLine, Account};
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

abstract class BaseAccountingService
{
    // ================================================================================================
    // NUMBER GENERATION METHODS
    // ================================================================================================

    protected function generateInvoiceNumber()
    {
        $prefix = config('accounting.invoice.prefix', 'INV');
        $lastInvoice = \App\Models\Invoice::latest()->first();
        $number = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, -4)) + 1 : 1;

        return $prefix . '-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    protected function generatePaymentNumber()
    {
        $prefix = 'PAY';
        $lastPayment = \App\Models\Payment::latest()->first();
        $number = $lastPayment ? ((int) substr($lastPayment->payment_number, -4)) + 1 : 1;

        return $prefix . '-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    protected function generateExpenseNumber()
    {
        $prefix = config('accounting.expense.prefix', 'EXP');
        $lastExpense = \App\Models\Expense::latest()->first();
        $number = $lastExpense ? ((int) substr($lastExpense->expense_number, -4)) + 1 : 1;

        return $prefix . '-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    protected function generateAdvanceNumber()
    {
        $prefix = config('accounting.advance.prefix', 'ADV');
        $lastAdvance = \App\Models\EmployeeAdvance::latest()->first();
        $number = $lastAdvance ? ((int) substr($lastAdvance->advance_number, -4)) + 1 : 1;

        return $prefix . '-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    protected function generateJobNumber()
    {
        $prefix = 'JOB';
        $lastJob = \App\Models\JobAssignment::latest()->first();
        $number = $lastJob ? ((int) substr($lastJob->job_number, -4)) + 1 : 1;

        return $prefix . '-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    protected function generateJournalEntryNumber()
    {
        $prefix = 'JE';
        $lastEntry = JournalEntry::latest()->first();
        $number = $lastEntry ? ((int) substr($lastEntry->entry_number, -4)) + 1 : 1;

        return $prefix . '-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // ================================================================================================
    // JOURNAL ENTRY METHODS (DOUBLE-ENTRY BOOKKEEPING)
    // ================================================================================================

    /**
     * Create a journal entry
     */
    protected function createJournalEntry(array $data)
    {
        return JournalEntry::create([
            'entry_number' => $this->generateJournalEntryNumber(),
            'entry_date' => $data['entry_date'],
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'description' => $data['description'],
            'total_debit' => $data['total_debit'],
            'total_credit' => $data['total_credit'],
        ]);
    }

    /**
     * Create a journal entry line
     */
    protected function createJournalEntryLine(array $data)
    {
        return JournalEntryLine::create([
            'journal_entry_id' => $data['journal_entry_id'],
            'account_id' => $data['account_id'],
            'debit_amount' => $data['debit_amount'] ?? 0,
            'credit_amount' => $data['credit_amount'] ?? 0,
            'description' => $data['description'] ?? null,
        ]);
    }

    /**
     * Delete journal entries by reference
     */
    protected function deleteJournalEntriesByReference($referenceType, $referenceId)
    {
        $journalEntries = JournalEntry::where('reference_type', $referenceType)
            ->where('reference_id', $referenceId)
            ->get();

        foreach ($journalEntries as $entry) {
            // Delete lines first
            $entry->lines()->delete();
            // Delete entry
            $entry->delete();
        }
    }

    /**
     * Update account balances after journal entry
     */
    protected function updateAccountBalances(JournalEntry $journalEntry)
    {
        foreach ($journalEntry->lines as $line) {
            $line->account->updateBalance();
        }
    }

    // ================================================================================================
    // ACCOUNT HELPER METHODS
    // ================================================================================================

    /**
     * Get account by code
     */
    protected function getAccountByCode($code)
    {
        return Account::where('code', $code)->firstOrFail();
    }

    /**
     * Get cash account ID based on method
     */
    protected function getCashAccountId($method = 'cash')
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
    protected function getAccountsReceivableAccountId()
    {
        return $this->getAccountByCode('1200')->id; // Accounts Receivable
    }

    /**
     * Get accounts payable account ID
     */
    protected function getAccountsPayableAccountId()
    {
        return $this->getAccountByCode('2000')->id; // Accounts Payable
    }

    /**
     * Get revenue account ID
     */
    protected function getRevenueAccountId()
    {
        return $this->getAccountByCode('4000')->id; // Service Revenue
    }

    /**
     * Get tax payable account ID
     */
    protected function getTaxPayableAccountId()
    {
        return $this->getAccountByCode('2200')->id; // Tax Payable
    }

    /**
     * Get employee advances payable account ID
     */
    protected function getEmployeeAdvancesPayableAccountId()
    {
        return $this->getAccountByCode('2100')->id; // Employee Advances Payable
    }

    // ================================================================================================
    // DATE RANGE HELPER METHODS
    // ================================================================================================

    /**
     * Get date range for period
     */
    protected function getDateRange($period)
    {
        return match ($period) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'yesterday' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'last_week' => [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'this_quarter' => [now()->startOfQuarter(), now()->endOfQuarter()],
            'last_quarter' => [now()->subQuarter()->startOfQuarter(), now()->subQuarter()->endOfQuarter()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            'last_year' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            'last_30_days' => [now()->subDays(30), now()],
            'last_90_days' => [now()->subDays(90), now()],
            'last_365_days' => [now()->subDays(365), now()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    /**
     * Parse custom date range
     */
    protected function parseCustomDateRange($startDate, $endDate)
    {
        return [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ];
    }

    // ================================================================================================
    // CALCULATION HELPER METHODS
    // ================================================================================================

    /**
     * Calculate tax amount
     */
    protected function calculateTax($amount, $taxRate = null)
    {
        $rate = $taxRate ?? config('accounting.tax.default_rate', 14);
        return round($amount * ($rate / 100), 2);
    }

    /**
     * Calculate discount amount
     */
    protected function calculateDiscount($amount, $discountRate)
    {
        return round($amount * ($discountRate / 100), 2);
    }

    /**
     * Format currency amount
     */
    protected function formatCurrency($amount)
    {
        $symbol = config('accounting.currency.symbol', '$');
        $position = config('accounting.currency.position', 'before');

        $formatted = number_format($amount, 2);

        return $position === 'before' ? $symbol . $formatted : $formatted . $symbol;
    }

    // ================================================================================================
    // VALIDATION HELPER METHODS
    // ================================================================================================

    /**
     * Validate amount
     */
    protected function validateAmount($amount, $min = 0.01, $max = null)
    {
        if ($amount < $min) {
            throw new \Exception("Amount must be at least " . $this->formatCurrency($min));
        }

        if ($max && $amount > $max) {
            throw new \Exception("Amount cannot exceed " . $this->formatCurrency($max));
        }

        return true;
    }

    /**
     * Validate date range
     */
    protected function validateDateRange($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($start->gt($end)) {
            throw new \Exception('Start date must be before or equal to end date.');
        }

        return true;
    }

    // ================================================================================================
    // NOTIFICATION HELPER METHODS
    // ================================================================================================

    /**
     * Send notification (placeholder)
     */
    protected function sendNotification($type, $data)
    {
        // This is a placeholder for notification logic
        // You can implement email, SMS, or in-app notifications here

        if (config('accounting.notifications.enabled', true)) {
            // Log notification for now
            Log::info("Accounting notification: {$type}", $data);

            // You can implement actual notification sending here:
            // Mail::to($recipient)->send(new AccountingNotification($type, $data));
            // Notification::send($users, new AccountingNotification($type, $data));
        }
    }

    // ================================================================================================
    // AUDIT TRAIL METHODS
    // ================================================================================================

    /**
     * Log activity for audit trail
     */
    protected function logActivity($action, $model, $changes = [])
    {
        // This is a placeholder for audit logging
        // You can implement a full audit trail here

        $auditData = [
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now()
        ];

        // Log to file for now
        Log::channel('audit')->info('Accounting activity', $auditData);

        // You can save to database audit table:
        // AuditLog::create($auditData);
    }

    // ================================================================================================
    // REPORTING HELPER METHODS
    // ================================================================================================

    /**
     * Calculate percentage change
     */
    protected function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    /**
     * Get financial year dates
     */
    protected function getFinancialYearDates($year = null)
    {
        $year = $year ?? now()->year;

        // Assuming financial year starts April 1st (adjust as needed)
        $startMonth = config('accounting.financial_year_start', 4); // April

        if (now()->month >= $startMonth) {
            $fyStart = Carbon::create($year, $startMonth, 1);
            $fyEnd = Carbon::create($year + 1, $startMonth - 1, 1)->endOfMonth();
        } else {
            $fyStart = Carbon::create($year - 1, $startMonth, 1);
            $fyEnd = Carbon::create($year, $startMonth - 1, 1)->endOfMonth();
        }

        return [$fyStart, $fyEnd];
    }

    // ================================================================================================
    // CACHE HELPER METHODS
    // ================================================================================================

    /**
     * Get cached data or execute callback
     */
    protected function getCachedData($key, $callback, $minutes = 60)
    {
        return cache()->remember($key, now()->addMinutes($minutes), $callback);
    }

    /**
     * Clear cache for accounting data
     */
    protected function clearAccountingCache($pattern = 'accounting.*')
    {
        // Implementation depends on your cache driver
        // This is a simple example
        cache()->forget($pattern);
    }

    // ================================================================================================
    // CONFIGURATION HELPER METHODS
    // ================================================================================================

    /**
     * Get accounting configuration
     */
    protected function getAccountingConfig($key, $default = null)
    {
        return config("accounting.{$key}", $default);
    }

    /**
     * Check if feature is enabled
     */
    protected function isFeatureEnabled($feature)
    {
        return config("accounting.features.{$feature}", true);
    }
}
