<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;

use App\Models\Management\Employee;
use App\Models\Auth\Role;
use App\Models\Auth\User;
// use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:employees.view')->only(['index', 'show']);
        $this->middleware('permission:employees.create')->only(['create', 'store']);
        $this->middleware('permission:employees.edit')->only(['edit', 'update']);
        $this->middleware('permission:employees.delete')->only(['destroy']);
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

        return view('management.employees.index', compact('employees', 'departments'));
    }

    /**
     * Show the form for creating a new employee.
     */
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

        return view('management.employees.create', compact('departments', 'positions'));
    }
    public function store(Request $request)
    {
        // Enhanced validation including user account creation fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'nullable|string|unique:employees,employee_id|max:50',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'email' => 'nullable|email|unique:employees,email',
            'phone' => 'nullable|string|max:50',
            'hire_date' => 'nullable|date|before_or_equal:today',
            'salary' => 'nullable|numeric|min:0|max:9999999999.99',
            'emergency_contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:Active,Inactive',
            'nationality' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:today',
            'passport_number' => 'nullable|string|max:50',
            'visa_status' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'gender' => 'required|in:male,female',

            // User account creation fields
            'create_user_account' => 'boolean',
            'user_role' => 'nullable|exists:roles,slug',
            'auto_generate_email' => 'boolean'
        ], [
            'salary.max' => 'Salary cannot exceed 9,999,999,999.99',
            'email.unique' => 'This email address is already in use by another employee',
            'employee_id.unique' => 'This Employee ID is already in use',
            'name.required' => 'Employee name is required',
            'department.required' => 'Department is required',
            'position.required' => 'Position is required',
            'status.required' => 'Status is required',
            'gender.required' => 'Gender is required'
        ]);

        DB::beginTransaction();

        try {
            // Generate email if requested
            if ($request->boolean('auto_generate_email') && empty($validated['email'])) {
                $validated['email'] = $this->generateEmployeeEmail($validated['name']);
            }

            // Create employee first
            $employee = Employee::create($validated);

            $userCreated = false;
            $generatedPassword = null;
            $userCreationError = null;

            // Create user account if requested
            if ($request->boolean('create_user_account')) {
                try {
                    $userResult = $this->createUserAccount($employee, $validated);
                    $userCreated = $userResult['success'];
                    $generatedPassword = $userResult['password'];
                    $userCreationError = $userResult['error'] ?? null;
                } catch (\Exception $e) {
                    $userCreationError = $e->getMessage();
                    Log::error('User Account Creation Failed:', [
                        'employee_id' => $employee->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            // Prepare success message
            $successMessage = 'Employee created successfully!';
            if ($userCreated) {
                $successMessage .= ' User account created with login credentials.';
            } elseif ($request->boolean('create_user_account') && $userCreationError) {
                $successMessage .= ' However, user account creation failed: ' . $userCreationError;
            }

            return redirect()->route('management.employees.show', $employee)
                ->with('success', $successMessage)
                ->with('generated_password', $generatedPassword)
                ->with('user_created', $userCreated);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Employee Creation Error:', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()->withInput()
                ->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }

    /**
     * Create user account for employee
     */
    private function createUserAccount(Employee $employee, array $validated): array
    {
        // Check if user already exists with this email
        if (User::where('email', $employee->email)->exists()) {
            return [
                'success' => false,
                'error' => 'User with this email already exists',
                'password' => null
            ];
        }

        // Generate secure random password
        $password = $this->generateSecurePassword();

        // Determine role based on department if not specified
        $roleSlug = $validated['user_role'] ?? $this->mapDepartmentToRole($employee->department);

        // Get role
        $role = Role::where('slug', $roleSlug)->first();
        if (!$role) {
            $role = Role::where('slug', 'user')->first(); // Fallback to basic user role
        }

        // Create user
        $user = User::create([
            'name' => $employee->name,
            'username' => $employee->email, // Use email as username
            'email' => $employee->email,
            'password' => Hash::make($password),
            'gender' => $validated['gender'],
            'email_verified_at' => now(),
            'is_active' => true,
            'force_password_change' => true, // Force password change on first login
            'employee_id' => $employee->id
        ]);

        // Assign role
        if ($role) {
            $user->roles()->attach($role->id);
        }

        // Update employee with user relationship
        $employee->update(['user_id' => $user->id]);

        return [
            'success' => true,
            'error' => null,
            'password' => $password,
            'user' => $user
        ];
    }

    /**
     * Generate employee email based on name
     */
    private function generateEmployeeEmail(string $name): string
    {
        $domain = config('app.employee_email_domain', 'logistics.com');

        // Clean name and create email
        $emailName = strtolower(str_replace(' ', '.', trim($name)));
        $emailName = preg_replace('/[^a-z0-9.]/', '', $emailName);

        $baseEmail = $emailName . '@' . $domain;

        // Check if email already exists and add number if needed
        $counter = 1;
        $finalEmail = $baseEmail;

        while (Employee::where('email', $finalEmail)->exists() || User::where('email', $finalEmail)->exists()) {
            $finalEmail = $emailName . $counter . '@' . $domain;
            $counter++;
        }

        return $finalEmail;
    }

    /**
     * Map department to role
     */
    private function mapDepartmentToRole(string $department): string
    {
        $departmentRoleMap = [
            'Management' => 'manager',
            'Finance & Accounting' => 'finance',
            'Human Resources' => 'manager',
            'IT & Technology' => 'user',
            'Operations' => 'user',
            'Customer Service' => 'user',
            'Sales' => 'user',
            'Customs Clearance' => 'user',
            'Warehousing' => 'user',
            'Transportation' => 'user',
            'Administration' => 'user'
        ];

        return $departmentRoleMap[$department] ?? 'user';
    }

    /**
     * Generate secure random password
     */
    private function generateSecurePassword(int $length = 12): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*';

        // Ensure password has at least one character from each category
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];

        // Fill the rest randomly
        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Shuffle the password
        return str_shuffle($password);
    }

    /**
     * Block/Unblock user account
     */
    public function toggleUserStatus(Request $request, Employee $employee)
    {
        // Check if user can manage employee accounts
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('employees.manage-accounts')) {
            return back()->with('error', 'You do not have permission to manage user accounts.');
        }

        if (!$employee->user) {
            return back()->with('error', 'This employee does not have a user account.');
        }

        $user = $employee->user;
        $currentStatus = $user->is_active;
        $newStatus = !$currentStatus;

        $user->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'activated' : 'blocked';

        Log::info('User account status changed', [
            'admin_user' => auth()->user()->id,
            'target_user' => $user->id,
            'employee_id' => $employee->employee_id,
            'new_status' => $statusText
        ]);

        return back()->with('success', "User account has been {$statusText} successfully.");
    }

    /**
     * Create user account for existing employee
     */
    public function createUserAccountForEmployee(Request $request, Employee $employee)
    {
        // Check permissions
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('employees.create')) {
            return back()->with('error', 'You do not have permission to create user accounts.');
        }

        if ($employee->user) {
            return back()->with('error', 'This employee already has a user account.');
        }

        $validated = $request->validate([
            'user_role' => 'required|exists:roles,slug',
            'gender' => 'required|in:male,female'
        ]);

        try {
            // Generate email if employee doesn't have one
            if (!$employee->email) {
                $employee->update([
                    'email' => $this->generateEmployeeEmail($employee->name)
                ]);
            }

            $result = $this->createUserAccount($employee, array_merge($validated, [
                'gender' => $validated['gender']
            ]));

            if ($result['success']) {
                return back()
                    ->with('success', 'User account created successfully!')
                    ->with('generated_password', $result['password'])
                    ->with('user_created', true);
            } else {
                return back()->with('error', 'Failed to create user account: ' . $result['error']);
            }
        } catch (\Exception $e) {
            Log::error('Manual User Account Creation Failed:', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to create user account: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created employee in storage.
     */


    /**
     * Display the specified employee.
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

            return view('management.employees.show', compact('employee', 'statistics', 'monthlyPerformance'));
        } catch (\Exception $e) {
            return redirect()->route('management.employees.index')
                ->with('error', 'Error loading employee details: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified employee.
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

        return view('management.employees.edit', compact('employee', 'departments', 'positions'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'email' => 'nullable|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:50',
            'hire_date' => 'nullable|date|before_or_equal:today',
            'salary' => 'nullable|numeric|min:0|max:9999999999.99', // Max 10 billion
            'status' => 'required|in:Active,Inactive',
            'emergency_contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'nationality' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:today',
            'passport_number' => 'nullable|string|max:50',
            'visa_status' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000'
        ], [
            'salary.max' => 'Salary cannot exceed 9,999,999,999.99',
            'salary.numeric' => 'Salary must be a valid number',
            'email.unique' => 'This email address is already in use by another employee',
            'hire_date.before_or_equal' => 'Hire date cannot be in the future',
            'date_of_birth.before' => 'Date of birth must be in the past'
        ]);

        try {
            $employee->update($validated);

            return redirect()->route('management.employees.show', $employee)
                ->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            Log::error('Employee Update Error:', [
                'error' => $e->getMessage(),
                'employee_id' => $employee->id,
                'data' => $validated
            ]);

            return back()->withInput()
                ->with('error', 'Failed to update employee. Please check your input and try again.');
        }
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        // Check if employee has any related records
        $shipmentsCount = $employee->shipments()->count();
        $jobAssignmentsCount = method_exists($employee, 'jobAssignments') ? $employee->jobAssignments()->count() : 0;
        $advancesCount = method_exists($employee, 'advances') ? $employee->advances()->count() : 0;

        if ($shipmentsCount > 0 || $jobAssignmentsCount > 0 || $advancesCount > 0) {
            return redirect()->route('management.employees.index')
                ->with('error', 'Cannot delete employee. They have associated shipments, job assignments, or advances.');
        }

        try {
            $employee->delete();

            return redirect()->route('management.employees.index')
                ->with('success', 'Employee deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Employee Deletion Error:', [
                'error' => $e->getMessage(),
                'employee_id' => $employee->id
            ]);

            return redirect()->route('management.employees.index')
                ->with('error', 'Failed to delete employee. Please try again.');
        }
    }

    /**
     * Search employees (AJAX)
     */
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

    /**
     * Get employees by department (AJAX)
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
}
