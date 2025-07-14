@extends('layouts.app')

@section('title', 'Create Employee - LogiFlow')
@section('page-title', 'Create New Employee')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">← Back to Employees</a>
</div>

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
            <label class="form-label">Department *</label>
            <select name="department" class="form-input" required>
                <option value="">Select Department</option>
                <option value="Operations" {{ old('department') == 'Operations' ? 'selected' : '' }}>Operations</option>
                <option value="Customs" {{ old('department') == 'Customs' ? 'selected' : '' }}>Customs</option>
                <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                <option value="Accounting" {{ old('department') == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                <option value="IT" {{ old('department') == 'IT' ? 'selected' : '' }}>IT</option>
                <option value="Management" {{ old('department') == 'Management' ? 'selected' : '' }}>Management</option>
            </select>
            @error('department')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Position *</label>
            <input type="text" name="position" class="form-input" 
                   value="{{ old('position') }}" required>
            @error('position')
                <span class="error-message">{{ $message }}</span>
            @enderror
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
            @error('phone')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Hire Date</label>
            <input type="date" name="hire_date" class="form-input" 
                   value="{{ old('hire_date') }}">
            @error('hire_date')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Monthly Salary (USD)</label>
            <input type="number" name="salary" class="form-input" step="0.01" 
                   value="{{ old('salary') }}">
            @error('salary')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
    
    <div style="margin-top: 30px;">
        <button type="submit" class="btn btn-primary">Create Employee</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection