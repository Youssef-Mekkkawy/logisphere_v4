<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'department' => 'required|string',
    //         'position' => 'required|string',
    //         'email' => 'required|email|unique:employees,email',
    //         'phone' => 'required|string',
    //         'hire_date' => 'required|date',
    //         'salary' => 'required|numeric|min:0',
    //         'emergency_contact' => 'nullable|string',
    //         'address' => 'nullable|string'
    //     ]);

    //     try {
    //         $validated['employee_id'] = $this->generateEmployeeId();
    //         $validated['status'] = 'Active';

    //         $employee = Employee::create($validated);

    //         return redirect()->route('employees.show', $employee)
    //             ->with('success', 'Employee created successfully!');
    //     } catch (\Exception $e) {
    //         return back()->withInput()
    //             ->with('error', 'Failed to create employee: ' . $e->getMessage());
    //     }
    // }

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
    public function create()
    {
        $departments = [
            'Operations' => 'Operations',
            'Customer Service' => 'Customer Service',
            'Sales' => 'Sales',
            'Finance & Accounting' => 'Finance & Accounting',
            'Human Resources' => 'Human Resources',
            'IT & Technology' => 'IT & Technology',
            'Customs Clearance' => 'Customs Clearance',
            'Warehousing' => 'Warehousing',
            'Transportation' => 'Transportation',
            'Management' => 'Management',
            'Administration' => 'Administration'
        ];

        $positions = [
            'Manager' => 'Manager',
            'Senior Officer' => 'Senior Officer',
            'Officer' => 'Officer',
            'Assistant' => 'Assistant',
            'Coordinator' => 'Coordinator',
            'Specialist' => 'Specialist',
            'Supervisor' => 'Supervisor',
            'Executive' => 'Executive',
            'Driver' => 'Driver',
            'Warehouse Worker' => 'Warehouse Worker',
            'Customs Officer' => 'Customs Officer',
            'Documentation Officer' => 'Documentation Officer'
        ];

        return view('employees.create', compact('departments', 'positions'));
    }

    /**
     * Display the specified resource.
     */
    // public function show(Employee $employee)
    // {
    //     // Load relationships
    //     $employee->load([
    //         'shipments' => function ($query) {
    //             $query->latest()->take(5);
    //         },
    //         'jobAssignments' => function ($query) {
    //             $query->latest()->take(5);
    //         },
    //         'advances' => function ($query) {
    //             $query->latest()->take(5);
    //         }
    //     ]);

    //     // Get employee statistics
    //     $statistics = [
    //         'total_shipments' => $employee->shipments()->count(),
    //         'active_shipments' => $employee->shipments()->active()->count(),
    //         'completed_shipments' => $employee->shipments()->where('status', 'Delivered')->count(),
    //         'total_job_assignments' => $employee->jobAssignments()->count(),
    //         'completed_jobs' => $employee->jobAssignments()->where('status', 'Completed')->count(),
    //         'total_advances' => $employee->advances()->sum('amount'),
    //         'pending_advances' => $employee->advances()->where('status', 'Pending')->sum('amount'),
    //         'this_month_shipments' => $employee->shipments()->whereMonth('created_at', now()->month)->count(),
    //         'this_year_shipments' => $employee->shipments()->whereYear('created_at', now()->year)->count()
    //     ];

    //     // Monthly performance for the last 6 months
    //     $monthlyPerformance = $employee->shipments()
    //         ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as shipments')
    //         ->where('created_at', '>=', now()->subMonths(6))
    //         ->groupByRaw('YEAR(created_at), MONTH(created_at)')
    //         ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
    //         ->get();

    //     return view('employees.show', compact('employee', 'statistics', 'monthlyPerformance'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $departments = [
            'Operations' => 'Operations',
            'Customer Service' => 'Customer Service',
            'Sales' => 'Sales',
            'Finance & Accounting' => 'Finance & Accounting',
            'Human Resources' => 'Human Resources',
            'IT & Technology' => 'IT & Technology',
            'Customs Clearance' => 'Customs Clearance',
            'Warehousing' => 'Warehousing',
            'Transportation' => 'Transportation',
            'Management' => 'Management',
            'Administration' => 'Administration'
        ];

        $positions = [
            'Manager' => 'Manager',
            'Senior Officer' => 'Senior Officer',
            'Officer' => 'Officer',
            'Assistant' => 'Assistant',
            'Coordinator' => 'Coordinator',
            'Specialist' => 'Specialist',
            'Supervisor' => 'Supervisor',
            'Executive' => 'Executive',
            'Driver' => 'Driver',
            'Warehouse Worker' => 'Warehouse Worker',
            'Customs Officer' => 'Customs Officer',
            'Documentation Officer' => 'Documentation Officer'
        ];

        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Employee $employee)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'department' => 'required|string|max:100',
    //         'position' => 'required|string|max:100',
    //         'email' => 'required|email|unique:employees,email,' . $employee->id,
    //         'phone' => 'required|string|max:50',
    //         'hire_date' => 'required|date|before_or_equal:today',
    //         'salary' => 'required|numeric|min:0',
    //         'emergency_contact' => 'nullable|string|max:255',
    //         'address' => 'nullable|string|max:500',
    //         'status' => 'required|in:Active,Inactive,On Leave,Terminated',
    //         'nationality' => 'nullable|string|max:100',
    //         'date_of_birth' => 'nullable|date|before:today',
    //         'passport_number' => 'nullable|string|max:50',
    //         'visa_status' => 'nullable|string|max:100',
    //         'bank_account' => 'nullable|string|max:100',
    //         'notes' => 'nullable|string|max:1000'
    //     ]);

    //     try {
    //         $employee->update($validated);

    //         return redirect()->route('employees.show', $employee)
    //             ->with('success', 'Employee updated successfully!');
    //     } catch (\Exception $e) {
    //         return back()->withInput()
    //             ->with('error', 'Failed to update employee: ' . $e->getMessage());
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Check if employee has any related records
        $shipmentsCount = $employee->shipments()->count();
        $jobAssignmentsCount = $employee->jobAssignments()->count();
        $advancesCount = $employee->advances()->count();

        if ($shipmentsCount > 0 || $jobAssignmentsCount > 0 || $advancesCount > 0) {
            return redirect()->route('employees.index')
                ->with('error', 'Cannot delete employee. They have associated shipments, job assignments, or advances.');
        }

        try {
            $employee->delete();

            return redirect()->route('employees.index')
                ->with('success', 'Employee deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('employees.index')
                ->with('error', 'Failed to delete employee: ' . $e->getMessage());
        }
    }

    /**
     * Get employee performance metrics
     */
    public function performance(Employee $employee)
    {
        $performanceData = [
            'shipment_metrics' => [
                'total_shipments' => $employee->shipments()->count(),
                'completed_shipments' => $employee->shipments()->where('status', 'Delivered')->count(),
                'on_time_deliveries' => $employee->shipments()
                    ->where('status', 'Delivered')
                    ->whereColumn('actual_delivery_date', '<=', 'eta')
                    ->count(),
                'average_shipment_value' => $employee->shipments()->avg('value'),
                'completion_rate' => $employee->shipments()->count() > 0
                    ? round(($employee->shipments()->where('status', 'Delivered')->count() / $employee->shipments()->count()) * 100, 2)
                    : 0
            ],
            'job_metrics' => [
                'total_jobs' => $employee->jobAssignments()->count(),
                'completed_jobs' => $employee->jobAssignments()->where('status', 'Completed')->count(),
                'pending_jobs' => $employee->jobAssignments()->where('status', 'Pending')->count(),
                'in_progress_jobs' => $employee->jobAssignments()->where('status', 'In Progress')->count(),
                'average_completion_time' => $employee->jobAssignments()
                    ->where('status', 'Completed')
                    ->whereNotNull('completed_at')
                    ->selectRaw('AVG(DATEDIFF(completed_at, assigned_at)) as avg_days')
                    ->value('avg_days')
            ],
            'financial_metrics' => [
                'total_advances' => $employee->advances()->sum('amount'),
                'approved_advances' => $employee->advances()->where('status', 'Approved')->sum('amount'),
                'pending_advances' => $employee->advances()->where('status', 'Pending')->sum('amount'),
                'repaid_advances' => $employee->advances()->where('status', 'Repaid')->sum('amount')
            ],
            'monthly_trends' => $employee->shipments()
                ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as shipments')
                ->where('created_at', '>=', now()->subYear())
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->get()
        ];

        return view('employees.performance', compact('employee', 'performanceData'));
    }

    /**
     * Get employee shipments
     */
    public function shipments(Employee $employee, Request $request)
    {
        $query = $employee->shipments()->with(['company', 'originPort', 'destinationPort']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(20);
        $statuses = ['Pending', 'In Transit', 'At Port', 'Customs Clearance', 'Delivered', 'Cancelled'];

        return view('employees.shipments', compact('employee', 'shipments', 'statuses'));
    }

    /**
     * Toggle employee status
     */
    public function toggleStatus(Employee $employee)
    {
        $currentStatus = $employee->status;
        $newStatus = $currentStatus === 'Active' ? 'Inactive' : 'Active';

        $employee->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Employee status changed from {$currentStatus} to {$newStatus}!");
    }

    /**
     * Get employees by department
     */
    public function getByDepartment($department)
    {
        $employees = Employee::where('status', 'Active')
            ->where('department', $department)
            ->select('id', 'employee_id', 'name', 'position')
            ->orderBy('name')
            ->get();

        return response()->json($employees);
    }

    /**
     * Create employee covenant (equipment assignment)
     */
    public function createCovenant(Employee $employee, Request $request)
    {
        $validated = $request->validate([
            'equipment_type' => 'required|string|max:100',
            'equipment_name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:100',
            'value' => 'nullable|numeric|min:0',
            'assigned_date' => 'required|date',
            'condition' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $covenant = $employee->covenants()->create($validated);

            return redirect()->back()
                ->with('success', 'Equipment covenant created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create covenant: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        try {
            // Load relationships safely
            $employee->load([
                'shipments' => function ($query) {
                    $query->latest()->take(5);
                }
            ]);

            // Get employee statistics with safe defaults
            $statistics = [
                'total_shipments' => $employee->shipments()->count() ?? 0,
                'active_shipments' => $employee->shipments()->whereNotIn('status', ['Delivered', 'Cancelled'])->count() ?? 0,
                'completed_shipments' => $employee->shipments()->where('status', 'Delivered')->count() ?? 0,
                'total_job_assignments' => 0, // Placeholder
                'completed_jobs' => 0, // Placeholder
                'total_advances' => 0, // Placeholder
                'pending_advances' => 0, // Placeholder
                'this_month_shipments' => $employee->shipments()->whereMonth('created_at', now()->month)->count() ?? 0,
                'this_year_shipments' => $employee->shipments()->whereYear('created_at', now()->year)->count() ?? 0
            ];

            // Monthly performance for the last 6 months
            $monthlyPerformance = $employee->shipments()
                ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as shipments')
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->get();

            return view('employees.show', compact('employee', 'statistics', 'monthlyPerformance'));
        } catch (\Exception $e) {
            // If there's any error, redirect back with a message
            return redirect()->route('employees.index')
                ->with('error', 'Error loading employee details: ' . $e->getMessage());
        }
    }
    /**
     * Store new employee
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'email' => 'nullable|email|unique:employees,email',
            'phone' => 'nullable|string|max:50',
            'hire_date' => 'nullable|date|before_or_equal:today',
            'salary' => 'nullable|numeric|min:0',
            'emergency_contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:Active,Inactive'
        ]);

        try {
            // The employee_id will be auto-generated by the model
            $employee = Employee::create($validated);

            return redirect()->route('employees.show', $employee)
                ->with('success', 'Employee created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }
    // public function update(Request $request, Employee $employee)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'department' => 'required|string|max:100',
    //         'position' => 'required|string|max:100',
    //         'email' => 'nullable|email|unique:employees,email,' . $employee->id,
    //         'phone' => 'nullable|string|max:50',
    //         'hire_date' => 'nullable|date|before_or_equal:today',
    //         'salary' => 'nullable|numeric|min:0',
    //         'emergency_contact' => 'nullable|string|max:255',
    //         'address' => 'nullable|string|max:500',
    //         'status' => 'required|in:Active,Inactive',
    //         'nationality' => 'nullable|string|max:100',
    //         'date_of_birth' => 'nullable|date|before:today',
    //         'passport_number' => 'nullable|string|max:50',
    //         'visa_status' => 'nullable|string|max:100',
    //         'bank_account' => 'nullable|string|max:100',
    //         'notes' => 'nullable|string|max:1000'
    //     ]);

    //     try {
    //         $employee->update($validated);

    //         return redirect()->route('employees.show', $employee)
    //             ->with('success', 'Employee updated successfully!');
    //     } catch (\Exception $e) {
    //         return back()->withInput()
    //             ->with('error', 'Failed to update employee: ' . $e->getMessage());
    //     }
    // }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        // Debug: Log the incoming data
        Log::info('Employee Update Request Data:', $request->all());
        Log::info('Employee Before Update:', $employee->toArray());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'email' => 'nullable|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:50',
            'hire_date' => 'nullable|date|before_or_equal:today',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:Active,Inactive',
            'emergency_contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'nationality' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:today',
            'passport_number' => 'nullable|string|max:50',
            'visa_status' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Debug: Log validated data
        Log::info('Validated Data:', $validated);

        try {
            // Update the employee
            $employee->update($validated);

            // Debug: Log employee after update
            Log::info('Employee After Update:', $employee->fresh()->toArray());

            return redirect()->route('employees.show', $employee)
                ->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            // Debug: Log any errors
            Log::error('Employee Update Error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return back()->withInput()
                ->with('error', 'Failed to update employee: ' . $e->getMessage());
        }
    }
}
