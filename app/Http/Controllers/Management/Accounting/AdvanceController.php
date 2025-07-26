<?php

namespace App\Http\Controllers\Management\Accounting;

use App\Http\Controllers\Controller;
use App\Models\{EmployeeAdvance, Employee};
use App\Http\Requests\Accounting\{StoreAdvanceRequest, RepayAdvanceRequest};
use App\Services\AdvanceService;

class AdvanceController extends Controller
{
    protected $advanceService;

    public function __construct(AdvanceService $advanceService)
    {
        $this->advanceService = $advanceService;
    }

    public function index()
    {
        $advances = EmployeeAdvance::with('employee')
            ->when(request('status'), fn($q, $status) => $q->where('status', $status))
            ->when(request('type'), fn($q, $type) => $q->where('type', $type))
            ->latest()
            ->paginate(20);

        return view('dashboard.accounting.advances.index', compact('advances'));
    }

    public function create()
    {
        $employees = Employee::active()->get();
        return view('dashboard.accounting.advances.create', compact('employees'));
    }

    public function store(StoreAdvanceRequest $request)
    {
        $advance = $this->advanceService->issue($request->validated());
        return redirect()->route('accounting.advances.index')
            ->with('success', 'Advance issued successfully!');
    }

    public function show(EmployeeAdvance $advance)
    {
        $advance->load('employee');
        return view('dashboard.accounting.advances.show', compact('advance'));
    }

    public function destroy(EmployeeAdvance $advance)
    {
        $this->authorize('delete', $advance);

        $this->advanceService->delete($advance);
        return redirect()->route('accounting.advances.index')
            ->with('success', 'Advance deleted successfully!');
    }

    public function showRepaymentForm(EmployeeAdvance $advance)
    {
        return view('dashboard.accounting.advances.repay', compact('advance'));
    }

    public function recordRepayment(RepayAdvanceRequest $request, EmployeeAdvance $advance)
    {
        $this->advanceService->recordRepayment($advance, $request->validated());
        return redirect()->route('accounting.advances.index')
            ->with('success', 'Repayment recorded successfully!');
    }

    public function writeOff(EmployeeAdvance $advance)
    {
        $this->advanceService->writeOff($advance);
        return back()->with('success', 'Advance written off successfully!');
    }
}
