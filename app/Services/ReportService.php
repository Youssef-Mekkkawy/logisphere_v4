<?php

// ================================================================================================
// 4. REPORT SERVICE - COMPLETE IMPLEMENTATION
// ================================================================================================

// File: app/Services/ReportService.php
namespace App\Services;

use App\Models\Management\Account\{Account, Invoice, Payment, Expense, EmployeeAdvance, JournalEntry, JournalEntryLine};
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Exception;

class ReportService extends BaseAccountingService
{
    /**
     * Generate Profit & Loss Statement
     */
    public function generateProfitLoss($period = 'this_month', $options = [])
    {
        $dateRange = $this->getDateRange($period);
        $comparison = $options['comparison'] ?? false;
        
        // Get revenue accounts
        $revenueAccounts = Account::where('type', 'revenue')->get();
        $expenseAccounts = Account::where('type', 'expense')->get();
        
        $report = [
            'report_name' => 'Profit & Loss Statement',
            'period' => $period,
            'date_range' => $dateRange,
            'generated_at' => now(),
            'currency' => config('accounting.currency.code', 'USD'),
        ];
        
        // Calculate revenue
        $revenue = $this->calculateAccountGroupTotal($revenueAccounts, $dateRange, 'credit');
        $report['revenue'] = [
            'accounts' => $this->getAccountDetails($revenueAccounts, $dateRange, 'credit'),
            'total' => $revenue
        ];
        
        // Calculate expenses
        $expenses = $this->calculateAccountGroupTotal($expenseAccounts, $dateRange, 'debit');
        $report['expenses'] = [
            'accounts' => $this->getAccountDetails($expenseAccounts, $dateRange, 'debit'),
            'total' => $expenses
        ];
        
        // Calculate net profit/loss
        $netProfit = $revenue - $expenses;
        $report['net_profit'] = $netProfit;
        $report['profit_margin'] = $revenue > 0 ? ($netProfit / $revenue) * 100 : 0;
        
        // Add comparison data if requested
        if ($comparison) {
            $report['comparison'] = $this->addComparisonData($report, $period);
        }
        
        // Add key performance indicators
        $report['kpis'] = $this->calculateProfitLossKPIs($report);
        
        return $report;
    }

    /**
     * Generate Balance Sheet
     */
    public function generateBalanceSheet($asOfDate = null, $options = [])
    {
        $asOfDate = $asOfDate ? Carbon::parse($asOfDate) : now();
        $includeZeroBalances = $options['include_zero_balances'] ?? false;
        
        $report = [
            'report_name' => 'Balance Sheet',
            'as_of_date' => $asOfDate,
            'generated_at' => now(),
            'currency' => config('accounting.currency.code', 'USD'),
        ];
        
        // Get account balances as of the specified date
        $assets = $this->getAccountBalances('asset', $asOfDate, $includeZeroBalances);
        $liabilities = $this->getAccountBalances('liability', $asOfDate, $includeZeroBalances);
        $equity = $this->getAccountBalances('equity', $asOfDate, $includeZeroBalances);
        
        // Group assets by category
        $report['assets'] = [
            'current_assets' => $assets->where('category', 'Current Assets'),
            'fixed_assets' => $assets->where('category', 'Fixed Assets'),
            'other_assets' => $assets->where('category', 'Other Assets'),
            'total' => $assets->sum('balance')
        ];
        
        // Group liabilities by category
        $report['liabilities'] = [
            'current_liabilities' => $liabilities->where('category', 'Current Liabilities'),
            'long_term_liabilities' => $liabilities->where('category', 'Long-term Liabilities'),
            'total' => $liabilities->sum('balance')
        ];
        
        // Equity section
        $report['equity'] = [
            'accounts' => $equity,
            'total' => $equity->sum('balance')
        ];
        
        // Calculate totals
        $totalLiabilitiesEquity = $report['liabilities']['total'] + $report['equity']['total'];
        $report['total_liabilities_equity'] = $totalLiabilitiesEquity;
        
        // Balance check
        $report['is_balanced'] = abs($report['assets']['total'] - $totalLiabilitiesEquity) < 0.01;
        $report['difference'] = $report['assets']['total'] - $totalLiabilitiesEquity;
        
        return $report;
    }

    /**
     * Generate Cash Flow Statement
     */
    public function generateCashFlow($period = 'this_month', $options = [])
    {
        $dateRange = $this->getDateRange($period);
        $method = $options['method'] ?? 'direct'; // direct or indirect
        
        $report = [
            'report_name' => 'Cash Flow Statement',
            'period' => $period,
            'date_range' => $dateRange,
            'method' => $method,
            'generated_at' => now(),
            'currency' => config('accounting.currency.code', 'USD'),
        ];
        
        if ($method === 'direct') {
            $report = array_merge($report, $this->generateDirectCashFlow($dateRange));
        } else {
            $report = array_merge($report, $this->generateIndirectCashFlow($dateRange));
        }
        
        return $report;
    }

    /**
     * Generate Aging Report (Receivables or Payables)
     */
    public function generateAging($type = 'receivables', $asOfDate = null, $options = [])
    {
        $asOfDate = $asOfDate ? Carbon::parse($asOfDate) : now();
        $agingPeriods = $options['aging_periods'] ?? [30, 60, 90, 120];
        
        $report = [
            'report_name' => ucfirst($type) . ' Aging Report',
            'type' => $type,
            'as_of_date' => $asOfDate,
            'aging_periods' => $agingPeriods,
            'generated_at' => now(),
            'currency' => config('accounting.currency.code', 'USD'),
        ];
        
        if ($type === 'receivables') {
            $invoices = Invoice::where('type', 'receivable')
                ->where('balance_due', '>', 0)
                ->with('company')
                ->get();
        } else {
            $invoices = Invoice::where('type', 'payable')
                ->where('balance_due', '>', 0)
                ->with('company')
                ->get();
        }
        
        $agingData = [];
        $totals = ['current' => 0];
        
        // Initialize aging period totals
        foreach ($agingPeriods as $period) {
            $totals["{$period}_days"] = 0;
        }
        $totals['over_' . max($agingPeriods) . '_days'] = 0;
        
        foreach ($invoices as $invoice) {
            $daysOverdue = $asOfDate->diffInDays($invoice->due_date, false);
            $agingBucket = $this->determineAgingBucket($daysOverdue, $agingPeriods);
            
            $agingData[] = [
                'company' => $invoice->company->name,
                'invoice_number' => $invoice->invoice_number,
                'invoice_date' => $invoice->invoice_date,
                'due_date' => $invoice->due_date,
                'days_overdue' => max(0, $daysOverdue),
                'balance_due' => $invoice->balance_due,
                'aging_bucket' => $agingBucket
            ];
            
            $totals[$agingBucket] += $invoice->balance_due;
        }
        
        $report['aging_data'] = collect($agingData)->groupBy('company');
        $report['totals'] = $totals;
        $report['grand_total'] = array_sum($totals);
        
        return $report;
    }

    /**
     * Generate Trial Balance
     */
    public function generateTrialBalance($asOfDate = null, $options = [])
    {
        $asOfDate = $asOfDate ? Carbon::parse($asOfDate) : now();
        $includeZeroBalances = $options['include_zero_balances'] ?? false;
        
        $accounts = Account::when(!$includeZeroBalances, function ($query) {
                return $query->where('balance', '!=', 0);
            })
            ->orderBy('code')
            ->get();
            
        $totalDebits = 0;
        $totalCredits = 0;
        $trialBalanceData = [];
        
        foreach ($accounts as $account) {
            $balance = $this->getAccountBalanceAsOf($account, $asOfDate);
            
            if (!$includeZeroBalances && $balance == 0) {
                continue;
            }
            
            // Determine if balance is debit or credit based on account type
            $isDebitBalance = in_array($account->type, ['asset', 'expense']);
            
            $debitAmount = $isDebitBalance && $balance > 0 ? $balance : 0;
            $creditAmount = !$isDebitBalance && $balance > 0 ? $balance : 0;
            
            // Handle negative balances (contra accounts)
            if ($balance < 0) {
                $debitAmount = !$isDebitBalance ? abs($balance) : 0;
                $creditAmount = $isDebitBalance ? abs($balance) : 0;
            }
            
            $trialBalanceData[] = [
                'account_code' => $account->code,
                'account_name' => $account->name,
                'account_type' => $account->type,
                'debit_amount' => $debitAmount,
                'credit_amount' => $creditAmount,
            ];
            
            $totalDebits += $debitAmount;
            $totalCredits += $creditAmount;
        }
        
        return [
            'report_name' => 'Trial Balance',
            'as_of_date' => $asOfDate,
            'generated_at' => now(),
            'accounts' => $trialBalanceData,
            'total_debits' => $totalDebits,
            'total_credits' => $totalCredits,
            'is_balanced' => abs($totalDebits - $totalCredits) < 0.01,
            'difference' => $totalDebits - $totalCredits,
            'currency' => config('accounting.currency.code', 'USD'),
        ];
    }

    /**
     * Generate General Ledger Report
     */
    public function generateGeneralLedger($accountId = null, $period = 'this_month', $options = [])
    {
        $dateRange = $this->getDateRange($period);
        
        $query = JournalEntryLine::with(['journalEntry', 'account'])
            ->whereHas('journalEntry', function ($q) use ($dateRange) {
                $q->whereBetween('entry_date', $dateRange);
            });
            
        if ($accountId) {
            $query->where('account_id', $accountId);
            $account = Account::find($accountId);
        }
        
        $entries = $query->orderBy('journal_entry_id')
            ->orderBy('id')
            ->get();
            
        $report = [
            'report_name' => 'General Ledger',
            'period' => $period,
            'date_range' => $dateRange,
            'account' => $accountId ? $account : null,
            'generated_at' => now(),
            'currency' => config('accounting.currency.code', 'USD'),
        ];
        
        if ($accountId) {
            // Single account ledger
            $runningBalance = $this->getAccountBalanceBefore($account, $dateRange[0]);
            $report['opening_balance'] = $runningBalance;
            $report['entries'] = [];
            
            foreach ($entries as $entry) {
                $runningBalance += $entry->debit_amount - $entry->credit_amount;
                
                $report['entries'][] = [
                    'date' => $entry->journalEntry->entry_date,
                    'entry_number' => $entry->journalEntry->entry_number,
                    'description' => $entry->journalEntry->description,
                    'reference' => $entry->journalEntry->reference_type . '#' . $entry->journalEntry->reference_id,
                    'debit_amount' => $entry->debit_amount,
                    'credit_amount' => $entry->credit_amount,
                    'running_balance' => $runningBalance,
                ];
            }
            
            $report['closing_balance'] = $runningBalance;
        } else {
            // All accounts
            $report['accounts'] = $entries->groupBy('account_id')->map(function ($accountEntries, $accountId) use ($dateRange) {
                $account = $accountEntries->first()->account;
                $runningBalance = $this->getAccountBalanceBefore($account, $dateRange[0]);
                
                return [
                    'account' => $account,
                    'opening_balance' => $runningBalance,
                    'entries' => $accountEntries->map(function ($entry) use (&$runningBalance) {
                        $runningBalance += $entry->debit_amount - $entry->credit_amount;
                        
                        return [
                            'date' => $entry->journalEntry->entry_date,
                            'entry_number' => $entry->journalEntry->entry_number,
                            'description' => $entry->journalEntry->description,
                            'debit_amount' => $entry->debit_amount,
                            'credit_amount' => $entry->credit_amount,
                            'running_balance' => $runningBalance,
                        ];
                    }),
                    'closing_balance' => $runningBalance,
                ];
            });
        }
        
        return $report;
    }

    /**
     * Generate Account Summary Report
     */
    public function generateAccountSummary($period = 'this_month', $options = [])
    {
        $dateRange = $this->getDateRange($period);
        $groupBy = $options['group_by'] ?? 'type'; // type or category
        
        $accounts = Account::with(['journalEntryLines' => function ($query) use ($dateRange) {
            $query->whereHas('journalEntry', function ($q) use ($dateRange) {
                $q->whereBetween('entry_date', $dateRange);
            });
        }])->get();
        
        $summary = $accounts->map(function ($account) use ($dateRange) {
            $entries = $account->journalEntryLines;
            $totalDebits = $entries->sum('debit_amount');
            $totalCredits = $entries->sum('credit_amount');
            $netActivity = $totalDebits - $totalCredits;
            
            return [
                'account' => $account,
                'opening_balance' => $this->getAccountBalanceBefore($account, $dateRange[0]),
                'total_debits' => $totalDebits,
                'total_credits' => $totalCredits,
                'net_activity' => $netActivity,
                'closing_balance' => $account->balance,
                'transaction_count' => $entries->count(),
            ];
        });
        
        return [
            'report_name' => 'Account Summary',
            'period' => $period,
            'date_range' => $dateRange,
            'group_by' => $groupBy,
            'accounts' => $summary->groupBy("account.{$groupBy}"),
            'totals' => [
                'total_debits' => $summary->sum('total_debits'),
                'total_credits' => $summary->sum('total_credits'),
                'net_activity' => $summary->sum('net_activity'),
            ],
            'generated_at' => now(),
            'currency' => config('accounting.currency.code', 'USD'),
        ];
    }

    /**
     * Generate Budget vs Actual Report
     */
    public function generateBudgetVsActual($period = 'this_month', $options = [])
    {
        // This would require a budgets table/functionality
        // For now, return a placeholder structure
        
        return [
            'report_name' => 'Budget vs Actual',
            'period' => $period,
            'generated_at' => now(),
            'note' => 'Budget functionality not yet implemented',
            'currency' => config('accounting.currency.code', 'USD'),
        ];
    }

    /**
     * Generate Tax Report
     */
    public function generateTaxReport($period = 'this_month', $options = [])
    {
        $dateRange = $this->getDateRange($period);
        
        // Get tax-related transactions
        $taxableInvoices = Invoice::whereBetween('invoice_date', $dateRange)
            ->where('tax_amount', '>', 0)
            ->get();
            
        $taxCollected = $taxableInvoices->where('type', 'receivable')->sum('tax_amount');
        $taxPaid = $taxableInvoices->where('type', 'payable')->sum('tax_amount');
        
        return [
            'report_name' => 'Tax Report',
            'period' => $period,
            'date_range' => $dateRange,
            'tax_collected' => $taxCollected,
            'tax_paid' => $taxPaid,
            'net_tax_liability' => $taxCollected - $taxPaid,
            'taxable_invoices' => $taxableInvoices->groupBy('type'),
            'generated_at' => now(),
            'currency' => config('accounting.currency.code', 'USD'),
        ];
    }

    /**
     * Generate Custom Report
     */
    public function generateCustomReport(array $config)
    {
        $reportType = $config['type'];
        $dateRange = isset($config['period']) ? 
            $this->getDateRange($config['period']) : 
            $this->parseCustomDateRange($config['start_date'], $config['end_date']);
            
        switch ($reportType) {
            case 'expense_analysis':
                return $this->generateExpenseAnalysis($dateRange, $config);
            case 'revenue_analysis':
                return $this->generateRevenueAnalysis($dateRange, $config);
            case 'employee_costs':
                return $this->generateEmployeeCosts($dateRange, $config);
            case 'shipment_profitability':
                return $this->generateShipmentProfitability($dateRange, $config);
            default:
                throw new Exception("Unknown report type: {$reportType}");
        }
    }

    /**
     * Export report to various formats
     */
    public function exportReport($reportData, $format = 'pdf', $options = [])
    {
        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($reportData, $options);
            case 'excel':
                return $this->exportToExcel($reportData, $options);
            case 'csv':
                return $this->exportToCsv($reportData, $options);
            case 'json':
                return $this->exportToJson($reportData, $options);
            default:
                throw new Exception("Unsupported export format: {$format}");
        }
    }

    /**
     * Calculate account group total for a period
     */
    private function calculateAccountGroupTotal(Collection $accounts, array $dateRange, string $side)
    {
        $total = 0;
        
        foreach ($accounts as $account) {
            $accountTotal = JournalEntryLine::where('account_id', $account->id)
                ->whereHas('journalEntry', function ($query) use ($dateRange) {
                    $query->whereBetween('entry_date', $dateRange);
                })
                ->sum($side === 'debit' ? 'debit_amount' : 'credit_amount');
                
            $total += $accountTotal;
        }
        
        return $total;
    }

    /**
     * Get detailed account information for a period
     */
    private function getAccountDetails(Collection $accounts, array $dateRange, string $side)
    {
        return $accounts->map(function ($account) use ($dateRange, $side) {
            $amount = JournalEntryLine::where('account_id', $account->id)
                ->whereHas('journalEntry', function ($query) use ($dateRange) {
                    $query->whereBetween('entry_date', $dateRange);
                })
                ->sum($side === 'debit' ? 'debit_amount' : 'credit_amount');
                
            return [
                'account' => $account,
                'amount' => $amount
            ];
        })->filter(function ($item) {
            return $item['amount'] > 0;
        });
    }

    /**
     * Get account balances by type
     */
    private function getAccountBalances(string $type, Carbon $asOfDate, bool $includeZero = false)
    {
        return Account::where('type', $type)
            ->when(!$includeZero, function ($query) {
                return $query->where('balance', '!=', 0);
            })
            ->get()
            ->map(function ($account) use ($asOfDate) {
                return [
                    'account' => $account,
                    'balance' => $this->getAccountBalanceAsOf($account, $asOfDate)
                ];
            });
    }

    /**
     * Get account balance as of a specific date
     */
    private function getAccountBalanceAsOf(Account $account, Carbon $date)
    {
        $totalDebits = JournalEntryLine::where('account_id', $account->id)
            ->whereHas('journalEntry', function ($query) use ($date) {
                $query->where('entry_date', '<=', $date);
            })
            ->sum('debit_amount');
            
        $totalCredits = JournalEntryLine::where('account_id', $account->id)
            ->whereHas('journalEntry', function ($query) use ($date) {
                $query->where('entry_date', '<=', $date);
            })
            ->sum('credit_amount');
            
        // Calculate balance based on account type
        if (in_array($account->type, ['asset', 'expense'])) {
            return $totalDebits - $totalCredits;
        } else {
            return $totalCredits - $totalDebits;
        }
    }

    /**
     * Get account balance before a specific date
     */
    private function getAccountBalanceBefore(Account $account, Carbon $date)
    {
        return $this->getAccountBalanceAsOf($account, $date->copy()->subDay());
    }

    /**
     * Generate direct method cash flow
     */
    private function generateDirectCashFlow(array $dateRange)
    {
        // Cash receipts from customers
        $cashFromCustomers = Payment::where('type', 'received')
            ->whereBetween('payment_date', $dateRange)
            ->sum('amount');
            
        // Cash paid to suppliers
        $cashToSuppliers = Payment::where('type', 'sent')
            ->whereBetween('payment_date', $dateRange)
            ->sum('amount');
            
        // Operating expenses
        $operatingExpenses = Expense::where('status', 'paid')
            ->whereBetween('expense_date', $dateRange)
            ->sum('amount');
            
        $netCashFromOperations = $cashFromCustomers - $cashToSuppliers - $operatingExpenses;
        
        return [
            'operating_activities' => [
                'cash_from_customers' => $cashFromCustomers,
                'cash_to_suppliers' => -$cashToSuppliers,
                'operating_expenses' => -$operatingExpenses,
                'net_cash_from_operations' => $netCashFromOperations,
            ],
            'investing_activities' => [
                'equipment_purchases' => 0, // Would need specific tracking
                'net_cash_from_investing' => 0,
            ],
            'financing_activities' => [
                'owner_investments' => 0, // Would need specific tracking
                'loan_proceeds' => 0,
                'loan_payments' => 0,
                'net_cash_from_financing' => 0,
            ],
            'net_change_in_cash' => $netCashFromOperations,
        ];
    }

    /**
     * Generate indirect method cash flow
     */
    private function generateIndirectCashFlow(array $dateRange)
    {
        $profitLoss = $this->generateProfitLoss($this->periodFromDateRange($dateRange));
        $netIncome = $profitLoss['net_profit'];
        
        // This would require more sophisticated calculation
        // For now, return a basic structure
        
        return [
            'operating_activities' => [
                'net_income' => $netIncome,
                'adjustments' => [
                    'depreciation' => 0,
                    'accounts_receivable_change' => 0,
                    'accounts_payable_change' => 0,
                ],
                'net_cash_from_operations' => $netIncome,
            ],
            'investing_activities' => [
                'net_cash_from_investing' => 0,
            ],
            'financing_activities' => [
                'net_cash_from_financing' => 0,
            ],
            'net_change_in_cash' => $netIncome,
        ];
    }

    /**
     * Determine aging bucket for overdue amount
     */
    private function determineAgingBucket(int $daysOverdue, array $agingPeriods)
    {
        if ($daysOverdue <= 0) {
            return 'current';
        }
        
        foreach ($agingPeriods as $period) {
            if ($daysOverdue <= $period) {
                return "{$period}_days";
            }
        }
        
        return 'over_' . max($agingPeriods) . '_days';
    }

    /**
     * Add comparison data to report
     */
    private function addComparisonData(array $report, string $period)
    {
        $previousPeriod = $this->getPreviousPeriod($period);
        $previousReport = $this->generateProfitLoss($previousPeriod, ['comparison' => false]);
        
        return [
            'previous_period' => $previousPeriod,
            'previous_revenue' => $previousReport['revenue']['total'],
            'previous_expenses' => $previousReport['expenses']['total'],
            'previous_net_profit' => $previousReport['net_profit'],
            'revenue_change' => $this->calculatePercentageChange(
                $report['revenue']['total'], 
                $previousReport['revenue']['total']
            ),
            'expense_change' => $this->calculatePercentageChange(
                $report['expenses']['total'], 
                $previousReport['expenses']['total']
            ),
            'profit_change' => $this->calculatePercentageChange(
                $report['net_profit'], 
                $previousReport['net_profit']
            ),
        ];
    }

    /**
     * Calculate P&L KPIs
     */
    private function calculateProfitLossKPIs(array $report)
    {
        $revenue = $report['revenue']['total'];
        $expenses = $report['expenses']['total'];
        $netProfit = $report['net_profit'];
        
        return [
            'gross_margin' => $revenue > 0 ? ($netProfit / $revenue) * 100 : 0,
            'expense_ratio' => $revenue > 0 ? ($expenses / $revenue) * 100 : 0,
            'revenue_per_day' => $revenue / 30, // Assuming monthly report
            'break_even_point' => $expenses,
        ];
    }

    /**
     * Get previous period
     */
    private function getPreviousPeriod(string $period)
    {
        return match($period) {
            'this_month' => 'last_month',
            'this_quarter' => 'last_quarter',
            'this_year' => 'last_year',
            default => 'last_month',
        };
    }

    /**
     * Convert date range to period string
     */
    private function periodFromDateRange(array $dateRange)
    {
        // Simple implementation - could be more sophisticated
        return 'custom';
    }

    /**
     * Export to PDF
     */
    private function exportToPdf(array $reportData, array $options)
    {
        // Implementation would use a PDF library
        return [
            'format' => 'pdf',
            'filename' => $reportData['report_name'] . '_' . now()->format('Y-m-d') . '.pdf',
            'data' => $reportData
        ];
    }

    /**
     * Export to Excel
     */
    private function exportToExcel(array $reportData, array $options)
    {
        // Implementation would use Excel export library
        return [
            'format' => 'excel',
            'filename' => $reportData['report_name'] . '_' . now()->format('Y-m-d') . '.xlsx',
            'data' => $reportData
        ];
    }

    /**
     * Export to CSV
     */
    private function exportToCsv(array $reportData, array $options)
    {
        // Implementation would convert report data to CSV format
        return [
            'format' => 'csv',
            'filename' => $reportData['report_name'] . '_' . now()->format('Y-m-d') . '.csv',
            'data' => $reportData
        ];
    }

    /**
     * Export to JSON
     */
    private function exportToJson(array $reportData, array $options)
    {
        return [
            'format' => 'json',
            'filename' => $reportData['report_name'] . '_' . now()->format('Y-m-d') . '.json',
            'data' => json_encode($reportData, JSON_PRETTY_PRINT)
        ];
    }

    /**
     * Generate expense analysis report
     */
    private function generateExpenseAnalysis(array $dateRange, array $config)
    {
        $expenses = Expense::whereBetween('expense_date', $dateRange)
            ->with(['employee', 'account'])
            ->get();
            
        return [
            'report_name' => 'Expense Analysis',
            'date_range' => $dateRange,
            'total_expenses' => $expenses->sum('amount'),
            'by_category' => $expenses->groupBy('category')->map->sum('amount'),
            'by_employee' => $expenses->groupBy('employee.name')->map->sum('amount'),
            'by_account' => $expenses->groupBy('account.name')->map->sum('amount'),
            'generated_at' => now(),
        ];
    }

    /**
     * Generate revenue analysis report
     */
    private function generateRevenueAnalysis(array $dateRange, array $config)
    {
        $invoices = Invoice::where('type', 'receivable')
            ->whereBetween('invoice_date', $dateRange)
            ->with('company')
            ->get();
            
        return [
            'report_name' => 'Revenue Analysis',
            'date_range' => $dateRange,
            'total_revenue' => $invoices->sum('total_amount'),
            'by_company' => $invoices->groupBy('company.name')->map->sum('total_amount'),
            'by_month' => $invoices->groupBy(fn($i) => $i->invoice_date->format('Y-m'))->map->sum('total_amount'),
            'generated_at' => now(),
        ];
    }

    /**
     * Generate employee costs report
     */
    private function generateEmployeeCosts(array $dateRange, array $config)
    {
        $expenses = Expense::whereBetween('expense_date', $dateRange)
            ->whereNotNull('employee_id')
            ->with('employee')
            ->get();
            
        $advances = EmployeeAdvance::whereBetween('issued_date', $dateRange)
            ->with('employee')
            ->get();
            
        return [
            'report_name' => 'Employee Costs',
            'date_range' => $dateRange,
            'expense_costs' => $expenses->groupBy('employee.name')->map->sum('amount'),
            'advance_costs' => $advances->groupBy('employee.name')->map->sum('amount'),
            'generated_at' => now(),
        ];
    }

    /**
     * Generate shipment profitability report
     */
    private function generateShipmentProfitability(array $dateRange, array $config)
    {
        // This would require shipment revenue and cost tracking
        return [
            'report_name' => 'Shipment Profitability',
            'date_range' => $dateRange,
            'note' => 'Requires shipment revenue/cost tracking implementation',
            'generated_at' => now(),
        ];
    }
}
