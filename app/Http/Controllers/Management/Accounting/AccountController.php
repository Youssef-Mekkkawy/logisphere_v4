<?php

namespace App\Http\Controllers\Management\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Http\Requests\Accounting\{StoreAccountRequest, UpdateAccountRequest};
use App\Services\AccountService;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index()
    {
        $accounts = Account::when(request('type'), fn($q, $type) => $q->where('type', $type))
            ->when(request('active'), fn($q, $active) => $q->where('is_active', $active === 'true'))
            ->orderBy('code')
            ->paginate(50);

        $accountTypes = ['asset', 'liability', 'equity', 'revenue', 'expense'];
        $accountsByType = Account::selectRaw('type, COUNT(*) as count, SUM(balance) as total_balance')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        return view('accounting.index', compact('accounts', 'accountTypes', 'accountsByType'));
    }

    public function create()
    {
        $accountTypes = [
            'asset' => 'Asset',
            'liability' => 'Liability',
            'equity' => 'Equity',
            'revenue' => 'Revenue',
            'expense' => 'Expense'
        ];

        $categories = [
            'asset' => ['Current Assets', 'Fixed Assets', 'Other Assets'],
            'liability' => ['Current Liabilities', 'Long-term Liabilities'],
            'equity' => ['Owner Equity', 'Retained Earnings'],
            'revenue' => ['Operating Revenue', 'Other Revenue'],
            'expense' => ['Operating Expenses', 'Administrative Expenses', 'Other Expenses']
        ];

        return view('accounting.accounts.create', compact('accountTypes', 'categories'));
    }

    public function store(StoreAccountRequest $request)
    {
        $account = $this->accountService->create($request->validated());

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully!');
    }

    public function show(Account $account)
    {
        $account->load(['journalEntryLines.journalEntry']);

        // Get recent transactions
        $recentTransactions = $account->journalEntryLines()
            ->with('journalEntry')
            ->latest()
            ->take(20)
            ->get();

        return view('accounting.accounts.show', compact('account', 'recentTransactions'));
    }

    public function edit(Account $account)
    {
        $accountTypes = [
            'asset' => 'Asset',
            'liability' => 'Liability',
            'equity' => 'Equity',
            'revenue' => 'Revenue',
            'expense' => 'Expense'
        ];

        $categories = [
            'asset' => ['Current Assets', 'Fixed Assets', 'Other Assets'],
            'liability' => ['Current Liabilities', 'Long-term Liabilities'],
            'equity' => ['Owner Equity', 'Retained Earnings'],
            'revenue' => ['Operating Revenue', 'Other Revenue'],
            'expense' => ['Operating Expenses', 'Administrative Expenses', 'Other Expenses']
        ];

        return view('accounting.accounts.edit', compact('account', 'accountTypes', 'categories'));
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $account = $this->accountService->update($account, $request->validated());

        return redirect()->route('accounting.index')
            ->with('success', 'Account updated successfully!');
    }

    public function destroy(Account $account)
    {
        if ($account->journalEntryLines()->exists()) {
            return back()->with('error', 'Cannot delete account with transaction history.');
        }

        if ($account->expenses()->exists()) {
            return back()->with('error', 'Cannot delete account with recorded expenses.');
        }

        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully!');
    }
}
