@extends('layouts.app')

@section('title', 'Edit Employee - LogiFlow')
@section('page-title', 'Edit Employee: ' . $employee->name)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">← Back to Employees</a>
    <a href="{{ route('employees.show', $employee) }}" class="btn btn-primary">View Employee</a>
</div>

<form method="POST" action="{{ route('employees.update', $employee) }}">
    @csrf
    @method('PUT')
    
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" class="form-input" 
                   value="{{ old('name', $employee->name) }}" required>
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Employee ID</label>
            <input type="text" name="employee_id" class="form-input" 
                   value="{{ old('employee_id', $employee->employee_id) }}" readonly>
            <small style="color: #6b7280; font-size: 12px;">Employee ID cannot be changed</small>
        </div>
        
        <div class="form-group">
            <label class="form-label">Department *</label>
            <select name="department" class="form-input" required>
                <option value="">Select Department</option>
                <option value="Operations" {{ old('department', $employee->department) == 'Operations' ? 'selected' : '' }}>Operations</option>
                <option value="Customs" {{ old('department', $employee->department) == 'Customs' ? 'selected' : '' }}>Customs</option>
                <option value="Sales" {{ old('department', $employee->department) == 'Sales' ? 'selected' : '' }}>Sales</option>
                <option value="Accounting" {{ old('department', $employee->department) == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                <option value="IT" {{ old('department', $employee->department) == 'IT' ? 'selected' : '' }}>IT</option>
                <option value="Management" {{ old('department', $employee->department) == 'Management' ? 'selected' : '' }}>Management</option>
            </select>
            @error('department')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Position *</label>
            <input type="text" name="position" class="form-input" 
                   value="{{ old('position', $employee->position) }}" required>
            @error('position')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" 
                   value="{{ old('email', $employee->email) }}">
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Phone</label>
            <input type="tel" name="phone" class="form-input" 
                   value="{{ old('phone', $employee->phone) }}">
            @error('phone')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Hire Date</label>
            <input type="date" name="hire_date" class="form-input" 
                   value="{{ old('hire_date', $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}">
            @error('hire_date')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Monthly Salary (USD)</label>
            <input type="number" name="salary" class="form-input" step="0.01" 
                   value="{{ old('salary', $employee->salary) }}">
            @error('salary')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="Active" {{ old('status', $employee->status) == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ old('status', $employee->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
    
    <div style="margin-top: 30px;">
        <button type="submit" class="btn btn-primary">Update Employee</button>
        <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">Cancel</a>
        
        @if(auth()->user()->isAdmin())
        <form method="POST" action="{{ route('employees.destroy', $employee) }}" style="display: inline; margin-left: 10px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn" style="background: #dc2626; color: white;" 
                    onclick="return confirm('Are you sure you want to delete this employee? This action cannot be undone.')">
                Delete Employee
            </button>
        </form>
        @endif
    </div>
</form>

<div style="margin-top: 40px; padding: 20px; background: #f8fafc; border-radius: 10px;">
    <h4 style="margin-bottom: 15px;">Employment Information</h4>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div>
            <strong>Employee Since:</strong> {{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}
        </div>
        <div>
            <strong>Employment Duration:</strong> 
            @if($employee->hire_date)
                {{ $employee->hire_date->diffForHumans() }}
            @else
                N/A
            @endif
        </div>
        <div>
            <strong>Record Created:</strong> {{ $employee->created_at->format('M d, Y') }}
        </div>
    </div>
</div>
@endsection