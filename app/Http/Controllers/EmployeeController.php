<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::paginate(20);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'position' => 'required|string',
            'email' => 'nullable|email|unique:employees,email',
            'phone' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0'
        ]);

        $data = $request->all();

        // Generate employee ID if not provided
        if (empty($data['employee_id'])) {
            $data['employee_id'] = 'EMP-' . str_pad(Employee::count() + 1, 3, '0', STR_PAD_LEFT);
        }

        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully!');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'position' => 'required|string',
            'email' => 'nullable|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0'
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully!');
    }
}
