<?php

namespace App\Services;

use App\Models\{Invoice, Payment, Expense, EmployeeAdvance, JobAssignment};

class AccountingDashboardService
{
    public function getDashboardData()
    {
        return [
            'stats' => $this->getStats(),
            'recentInvoices' => $this->getRecentInvoices(),
            'recentPayments' => $this->getRecentPayments(),
            'pendingExpenses' => $this->getPendingExpenses(),
        ];
    }

    private function getStats()
    {
        return [
            'total_revenue' => Invoice::receivable()->paid()->sum('total_amount'),
            'outstanding_receivables' => Invoice::receivable()->unpaid()->sum('balance_due'),
            'total_expenses' => Expense::approved()->sum('amount'),
            'pending_advances' => EmployeeAdvance::outstanding()->sum('balance'),
            'monthly_revenue' => Invoice::receivable()->thisMonth()->sum('total_amount'),
            'monthly_expenses' => Expense::thisMonth()->sum('amount'),
        ];
    }

    private function getRecentInvoices()
    {
        return Invoice::with('company')->latest()->take(5)->get();
    }

    private function getRecentPayments()
    {
        return Payment::with(['invoice', 'company'])->latest()->take(5)->get();
    }

    private function getPendingExpenses()
    {
        return Expense::pending()->with('employee')->latest()->take(5)->get();
    }
}
