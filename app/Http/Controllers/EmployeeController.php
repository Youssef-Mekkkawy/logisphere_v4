<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager')->except(['index', 'show']);
    }

    /**
     * Display employees with department filtering
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('name')->paginate(20);
        $departments = Employee::distinct()->pluck('department');

        return view('employees.index', compact('employees', 'departments'));
    }

    /**
     * Store new employee
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'position' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'emergency_contact' => 'nullable|string',
            'address' => 'nullable|string'
        ]);

        try {
            $validated['employee_id'] = $this->generateEmployeeId();
            $validated['status'] = 'Active';

            $employee = Employee::create($validated);

            return redirect()->route('employees.show', $employee)
                ->with('success', 'Employee created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique employee ID
     */
    private function generateEmployeeId()
    {
        $lastEmployee = Employee::orderBy('employee_id', 'desc')->first();

        if ($lastEmployee) {
            $lastNumber = intval(substr($lastEmployee->employee_id, 4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'EMP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
    public function search(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        return response()->json([
            'employees' => $query->limit(10)->get()
        ]);
    }

    public function getMetrics(Employee $employee)
    {
        $employeeService = app(\App\Services\EmployeeService::class);
        return response()->json($employeeService->getEmployeeMetrics($employee));
    }
}
