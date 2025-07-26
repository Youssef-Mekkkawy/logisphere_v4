@extends('layouts.app')

@section('title', 'Edit Employee - LogiFlow')
@section('page-title', 'Edit Employee: ' . $employee->name)

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('management.employees.index') }}" class="btn btn-secondary">← Back to Employees</a>
        <a href="{{ route('management.employees.show', $employee) }}" class="btn btn-primary">View Employee</a>
    </div>

    <form method="POST" action="{{ route('management.employees.update', $employee) }}"
        style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
        @csrf
        @method('PUT')

        <!-- Employee Info Header -->
        <div style="margin-bottom: 30px; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <h4 style="color: #1e40af; margin-bottom: 10px;">Edit Employee Information</h4>
            <p style="color: #6b7280; margin: 0;">
                <strong>Employee ID:</strong> {{ $employee->employee_id }}
                <span style="margin-left: 20px;"><strong>Created:</strong>
                    {{ $employee->created_at->format('M d, Y') }}</span>
            </p>
        </div>

        <!-- Basic Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">👤 Basic Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $employee->name) }}"
                        required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="employee_id" class="form-input"
                        value="{{ old('employee_id', $employee->employee_id) }}" readonly
                        style="background-color: #f3f4f6; cursor: not-allowed;">
                    <small style="color: #6b7280; font-size: 12px;">Employee ID cannot be changed</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-input" required>
                        <option value="Active" {{ old('status', $employee->status) == 'Active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="Inactive" {{ old('status', $employee->status) == 'Inactive' ? 'selected' : '' }}>
                            Inactive</option>
                    </select>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Work Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">💼 Work Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <select name="department" class="form-input" required>
                        <option value="">Select Department</option>
                        @foreach ($departments as $key => $dept)
                            <option value="{{ $key }}"
                                {{ old('department', $employee->department) == $key ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                    @error('department')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Position *</label>
                    <select name="position" class="form-input" required>
                        <option value="">Select Position</option>
                        @foreach ($positions as $key => $pos)
                            <option value="{{ $key }}"
                                {{ old('position', $employee->position) == $key ? 'selected' : '' }}>
                                {{ $pos }}
                            </option>
                        @endforeach
                    </select>
                    @error('position')
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
                    <input type="number" name="salary" class="form-input" step="0.01" min="0"
                        value="{{ old('salary', $employee->salary) }}" placeholder="0.00">
                    @error('salary')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📞 Contact Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $employee->email) }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-input" value="{{ old('phone', $employee->phone) }}">
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Emergency Contact</label>
                    <input type="text" name="emergency_contact" class="form-input"
                        value="{{ old('emergency_contact', $employee->emergency_contact) }}">
                    @error('emergency_contact')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nationality</label>
                    <input type="text" name="nationality" class="form-input"
                        value="{{ old('nationality', $employee->nationality) }}">
                    @error('nationality')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-input" rows="3">{{ old('address', $employee->address) }}</textarea>
                @error('address')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Personal Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📄 Personal Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-input"
                        value="{{ old('date_of_birth', $employee->date_of_birth ? $employee->date_of_birth->format('Y-m-d') : '') }}">
                    @error('date_of_birth')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Passport Number</label>
                    <input type="text" name="passport_number" class="form-input"
                        value="{{ old('passport_number', $employee->passport_number) }}">
                    @error('passport_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Visa Status</label>
                    <input type="text" name="visa_status" class="form-input"
                        value="{{ old('visa_status', $employee->visa_status) }}">
                    @error('visa_status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Bank Account</label>
                    <input type="text" name="bank_account" class="form-input"
                        value="{{ old('bank_account', $employee->bank_account) }}">
                    @error('bank_account')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-input" rows="3">{{ old('notes', $employee->notes) }}</textarea>
                @error('notes')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Audit Information -->
        <div style="margin-bottom: 30px; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📊 Employment Information</h4>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 14px;">
                <div>
                    <strong>Employee Since:</strong><br>
                    <span style="color: #6b7280;">
                        {{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}
                    </span>
                </div>
                <div>
                    <strong>Employment Duration:</strong><br>
                    <span style="color: #6b7280;">
                        @if ($employee->hire_date)
                            {{ $employee->hire_date->diffForHumans() }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div>
                    <strong>Record Created:</strong><br>
                    <span style="color: #6b7280;">{{ $employee->created_at->format('M d, Y') }}</span>
                </div>
                <div>
                    <strong>Last Updated:</strong><br>
                    <span style="color: #6b7280;">{{ $employee->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Employee
            </button>
            <a href="{{ route('management.employees.show', $employee) }}" class="btn btn-secondary" style="margin-left: 15px;">
                <i class="fas fa-times"></i> Cancel
            </a>

            @if (auth()->user()->isAdmin() && in_array($employee->status, ['Inactive']))
                <button type="button" class="btn btn-danger" style="margin-left: 15px; float: right;"
                    onclick="if(confirm('Are you sure you want to delete this employee? This action cannot be undone and will affect all related records.')) { document.getElementById('delete-form').submit(); }">
                    <i class="fas fa-trash"></i> Delete Employee
                </button>
            @endif
        </div>
    </form>

    <!-- Hidden Delete Form -->
    @if (auth()->user()->isAdmin() && in_array($employee->status, ['Inactive']))
        <form id="delete-form" method="POST" action="{{ route('management.employees.destroy', $employee) }}"
            style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endif

@endsection
