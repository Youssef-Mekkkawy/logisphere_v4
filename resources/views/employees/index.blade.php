@extends('layouts.app')

@section('title', 'Employees - LogiFlow')
@section('page-title', 'Employees')

@section('content')
<div class="tabs">
    <div class="tab active" data-tab="all-employees">All Employees</div>
    <div class="tab" data-tab="add-employee">Add Employee</div>
    <div class="tab" data-tab="employee-assignments">Assignments</div>
</div>

<div id="all-employees-tab" class="tab-content active">
    <input type="text" class="search-bar" placeholder="Search employees...">
    <a href="{{ route('employees.create') }}" class="btn btn-primary">Add New Employee</a>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr>
                <td>{{ $employee->employee_id }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->department ?: 'N/A' }}</td>
                <td>{{ $employee->position ?: 'N/A' }}</td>
                <td>{{ $employee->email ?: 'N/A' }}</td>
                <td>{{ $employee->phone ?: 'N/A' }}</td>
                <td>
                    <span class="status-badge status-{{ strtolower($employee->status) }}">
                        {{ $employee->status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-secondary">Edit</a>
                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-primary">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                    <p style="color: #6b7280;">No employees found. <a href="{{ route('employees.create') }}">Add your first employee</a></p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($employees instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="margin-top: 20px;">
            {{ $employees->links() }}
        </div>
    @endif
</div>

<div id="add-employee-tab" class="tab-content">
    <h3>Add New Employee</h3>
    <form method="POST" action="{{ route('employees.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-input" 
                       value="{{ old('name') }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Employee ID</label>
                <input type="text" name="employee_id" class="form-input" 
                       placeholder="Auto-generated if left empty" value="{{ old('employee_id') }}">
                @error('employee_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Department</label>
                <select name="department" class="form-input">
                    <option value="">Select Department</option>
                    <option value="Operations" {{ old('department') == 'Operations' ? 'selected' : '' }}>Operations</option>
                    <option value="Customs" {{ old('department') == 'Customs' ? 'selected' : '' }}>Customs</option>
                    <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                    <option value="Accounting" {{ old('department') == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                    <option value="IT" {{ old('department') == 'IT' ? 'selected' : '' }}>IT</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Position</label>
                <input type="text" name="position" class="form-input" 
                       value="{{ old('position') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" 
                       value="{{ old('email') }}">
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-input" 
                       value="{{ old('phone') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Hire Date</label>
                <input type="date" name="hire_date" class="form-input" 
                       value="{{ old('hire_date') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Salary</label>
                <input type="number" name="salary" class="form-input" step="0.01" 
                       value="{{ old('salary') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Employee</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </form>
</div>

<div id="employee-assignments-tab" class="tab-content">
    <h3>Employee Assignments</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Assignment</th>
                <th>Shipment ID</th>
                <th>Start Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px;">
                    <p style="color: #6b7280;">No assignments found. Assignment functionality will be implemented soon.</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection