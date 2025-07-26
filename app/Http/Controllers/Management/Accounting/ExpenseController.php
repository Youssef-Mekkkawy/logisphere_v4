<?php

namespace App\Http\Controllers\Management\Accounting;

use App\Http\Controllers\Controller;
use App\Models\{Expense, Account, Employee, Shipment};
use App\Http\Requests\Accounting\{StoreExpenseRequest, UpdateExpenseRequest};
use App\Services\ExpenseService;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index()
    {
        $expenses = Expense::with(['employee', 'shipment', 'account'])
            ->when(request('status'), fn($q, $status) => $q->where('status', $status))
            ->when(request('category'), fn($q, $category) => $q->where('category', $category))
            ->latest()
            ->paginate(20);

        return view('dashboard.accounting.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $accounts = Account::expenses()->active()->get();
        $employees = Employee::active()->get();
        $shipments = Shipment::active()->get();

        return view(
            'dashboard.accounting.expenses.create',
            compact('accounts', 'employees', 'shipments')
        );
    }

    public function store(StoreExpenseRequest $request)
    {
        $expense = $this->expenseService->create($request->validated());
        return redirect()->route('accounting.expenses.index')
            ->with('success', 'Expense recorded successfully!');
    }

    public function show(Expense $expense)
    {
        $expense->load(['employee', 'shipment', 'account']);
        return view('dashboard.accounting.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        $accounts = Account::expenses()->active()->get();
        $employees = Employee::active()->get();
        $shipments = Shipment::active()->get();

        return view(
            'dashboard.accounting.expenses.edit',
            compact('expense', 'accounts', 'employees', 'shipments')
        );
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $expense = $this->expenseService->update($expense, $request->validated());
        return redirect()->route('accounting.expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $this->expenseService->delete($expense);
        return redirect()->route('accounting.expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }

    public function approve(Expense $expense)
    {
        $this->expenseService->approve($expense);
        return back()->with('success', 'Expense approved successfully!');
    }

    public function reject(Expense $expense)
    {
        $this->expenseService->reject($expense);
        return back()->with('success', 'Expense rejected.');
    }
}
