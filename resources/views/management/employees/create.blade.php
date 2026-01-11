@extends('layouts.app')

@section('title', 'Create Employee - logistics')
@section('page-title', 'Create New Employee')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('management.employees.index') }}" class="btn btn-secondary">← Back to Employees</a>
    </div>

    <form method="POST" action="{{ route('management.employees.store') }}"
        style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
        @csrf

        <!-- Create Employee Info Header -->
        <div style="margin-bottom: 30px; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <h4 style="color: #1e40af; margin-bottom: 10px;">Create New Employee</h4>
            <p style="color: #6b7280; margin: 0;">
                Fill in the employee information below. Employee ID will be auto-generated if left empty.
            </p>
        </div>

        <!-- Basic Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">👤 Basic Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="employee_id" class="form-input" value="{{ old('employee_id') }}"
                        placeholder="Auto-generated if left empty">
                    @error('employee_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small style="color: #6b7280; font-size: 12px;">Leave empty for auto-generation (EMP-001, EMP-002,
                        etc.)</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Gender *</label>
                    <select name="gender" class="form-input" required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', 'male') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-input" required>
                        <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
                    <select name="department" id="department" class="form-input" required>
                        <option value="">Select Department</option>
                        <option value="Operations" {{ old('department') == 'Operations' ? 'selected' : '' }}>Operations
                        </option>
                        <option value="Customer Service" {{ old('department') == 'Customer Service' ? 'selected' : '' }}>
                            Customer Service</option>
                        <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                        <option value="Finance & Accounting"
                            {{ old('department') == 'Finance & Accounting' ? 'selected' : '' }}>Finance & Accounting
                        </option>
                        <option value="Human Resources" {{ old('department') == 'Human Resources' ? 'selected' : '' }}>
                            Human Resources</option>
                        <option value="IT & Technology" {{ old('department') == 'IT & Technology' ? 'selected' : '' }}>IT &
                            Technology</option>
                        <option value="Customs Clearance" {{ old('department') == 'Customs Clearance' ? 'selected' : '' }}>
                            Customs Clearance</option>
                        <option value="Warehousing" {{ old('department') == 'Warehousing' ? 'selected' : '' }}>Warehousing
                        </option>
                        <option value="Transportation" {{ old('department') == 'Transportation' ? 'selected' : '' }}>
                            Transportation</option>
                        <option value="Management" {{ old('department') == 'Management' ? 'selected' : '' }}>Management
                        </option>
                        <option value="Administration" {{ old('department') == 'Administration' ? 'selected' : '' }}>
                            Administration</option>
                    </select>
                    @error('department')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Position *</label>
                    <select name="position" class="form-input" required>
                        <option value="">Select Position</option>
                        <option value="Manager" {{ old('position') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Senior Officer" {{ old('position') == 'Senior Officer' ? 'selected' : '' }}>Senior
                            Officer</option>
                        <option value="Officer" {{ old('position') == 'Officer' ? 'selected' : '' }}>Officer</option>
                        <option value="Assistant" {{ old('position') == 'Assistant' ? 'selected' : '' }}>Assistant</option>
                        <option value="Coordinator" {{ old('position') == 'Coordinator' ? 'selected' : '' }}>Coordinator
                        </option>
                        <option value="Specialist" {{ old('position') == 'Specialist' ? 'selected' : '' }}>Specialist
                        </option>
                        <option value="Supervisor" {{ old('position') == 'Supervisor' ? 'selected' : '' }}>Supervisor
                        </option>
                        <option value="Executive" {{ old('position') == 'Executive' ? 'selected' : '' }}>Executive</option>
                        <option value="Driver" {{ old('position') == 'Driver' ? 'selected' : '' }}>Driver</option>
                        <option value="Warehouse Worker" {{ old('position') == 'Warehouse Worker' ? 'selected' : '' }}>
                            Warehouse Worker</option>
                        <option value="Customs Officer" {{ old('position') == 'Customs Officer' ? 'selected' : '' }}>
                            Customs Officer</option>
                        <option value="Documentation Officer"
                            {{ old('position') == 'Documentation Officer' ? 'selected' : '' }}>Documentation Officer
                        </option>
                    </select>
                    @error('position')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Hire Date</label>
                    <input type="date" name="hire_date" class="form-input" value="{{ old('hire_date') }}"
                        max="{{ date('Y-m-d') }}">
                    @error('hire_date')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Monthly Salary (USD)</label>
                    <input type="number" name="salary" class="form-input" step="0.01" min="0"
                        max="9999999999.99" value="{{ old('salary') }}" placeholder="0.00">
                    @error('salary')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 🔥 NEW: User Account Creation Section -->
        @if (auth()->user()->isAdmin() || auth()->user()->hasPermission('employees.create'))
            <div style="margin-bottom: 30px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">🔐 User Account Creation</h4>
                <div style="padding: 20px; background: #f0f9ff; border-radius: 12px; border-left: 4px solid #3b82f6;">

                    <!-- Create User Account Checkbox -->
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="create_user_account" id="createUserAccount" value="1"
                                {{ old('create_user_account') ? 'checked' : '' }} onchange="toggleUserAccountOptions()">
                            <span style="font-weight: 600; color: #1e40af;">Create User Account for System Access</span>
                        </label>
                        <small style="color: #6b7280; margin-left: 30px;">Check this to create a login account for this
                            employee</small>
                    </div>

                    <!-- User Account Options (Hidden by default) -->
                    <div id="userAccountOptions"
                        style="display: none; margin-top: 20px; padding-top: 20px; border-top: 1px solid #bfdbfe;">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">User Role</label>
                                <select name="user_role" id="userRole" class="form-input">
                                    <option value="">Auto-assign based on department</option>
                                    @php
                                        $roles = \App\Models\Auth\Role::where('is_active', true)->get();
                                    @endphp
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->slug }}"
                                            {{ old('user_role') == $role->slug ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small style="color: #6b7280; font-size: 12px;">Leave empty to auto-assign role based on
                                    department</small>
                            </div>

                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="auto_generate_email" id="autoGenerateEmail"
                                        value="1" {{ old('auto_generate_email', true) ? 'checked' : '' }}>
                                    <span style="font-weight: 600;">Auto-generate Company Email</span>
                                </label>
                                <small style="color: #6b7280; margin-left: 30px;">Generate email like:
                                    john.doe@{{ config('app.employee_email_domain', 'logistas.com') }}</small>
                            </div>
                        </div>

                        <!-- Account Creation Info -->
                        <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 8px;">
                            <h5 style="color: #1e40af; margin-bottom: 10px;">📋 Account Creation Details</h5>
                            <ul style="margin: 0; padding-left: 20px; color: #374151; font-size: 14px;">
                                <li>A secure random password will be generated</li>
                                <li>Password will be displayed after creation (save it securely)</li>
                                <li>Employee must change password on first login</li>
                                <li>Account will be active immediately</li>
                                <li>Admin can block/unblock the account anytime</li>
                                <li>Role permissions based on department unless manually selected</li>
                            </ul>
                        </div>
                    </div>

                    @error('user_role')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @endif

        <!-- Contact Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📞 Contact Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="emailInput" class="form-input"
                        value="{{ old('email') }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small id="emailHelp" style="color: #6b7280; font-size: 12px;">
                        Will be auto-generated if creating user account and left empty
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-input" value="{{ old('phone') }}">
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Emergency Contact</label>
                    <input type="text" name="emergency_contact" class="form-input"
                        value="{{ old('emergency_contact') }}" placeholder="Name and phone number">
                    @error('emergency_contact')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nationality</label>
                    <input type="text" name="nationality" class="form-input" value="{{ old('nationality') }}"
                        placeholder="e.g., American, Egyptian, etc.">
                    @error('nationality')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-input" rows="3" placeholder="Full residential address">{{ old('address') }}</textarea>
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
                    <input type="date" name="date_of_birth" class="form-input" value="{{ old('date_of_birth') }}"
                        max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                    @error('date_of_birth')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small style="color: #6b7280; font-size: 12px;">Must be at least 18 years old</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Passport Number</label>
                    <input type="text" name="passport_number" class="form-input"
                        value="{{ old('passport_number') }}" placeholder="e.g., A12345678">
                    @error('passport_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Visa Status</label>
                    <input type="text" name="visa_status" class="form-input" value="{{ old('visa_status') }}"
                        placeholder="e.g., Work Visa, Resident, Citizen">
                    @error('visa_status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Bank Account</label>
                    <input type="text" name="bank_account" class="form-input" value="{{ old('bank_account') }}"
                        placeholder="Bank name and account number">
                    @error('bank_account')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-input" rows="3" placeholder="Additional notes about the employee">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Employee
            </button>
            <a href="{{ route('management.employees.index') }}" class="btn btn-secondary" style="margin-left: 15px;">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="reset" class="btn btn-secondary" style="margin-left: 15px;" onclick="resetForm()">
                <i class="fas fa-undo"></i> Reset Form
            </button>
        </div>
    </form>

@endsection

@section('scripts')
    <script>
        // Department to role mapping for auto-suggestion
        const departmentRoleMap = {
            'Management': 'manager',
            'Finance & Accounting': 'finance',
            'Human Resources': 'manager',
            'IT & Technology': 'user',
            'Operations': 'user',
            'Customer Service': 'user',
            'Sales': 'user',
            'Customs Clearance': 'user',
            'Warehousing': 'user',
            'Transportation': 'user',
            'Administration': 'user'
        };

        // Toggle user account options visibility
        function toggleUserAccountOptions() {
            const checkbox = document.getElementById('createUserAccount');
            const options = document.getElementById('userAccountOptions');
            const emailInput = document.getElementById('emailInput');
            const emailHelp = document.getElementById('emailHelp');

            if (checkbox.checked) {
                options.style.display = 'block';
                emailHelp.textContent = 'Will be auto-generated if left empty and auto-generate is checked';
            } else {
                options.style.display = 'none';
                emailHelp.textContent = 'Enter employee email address';
            }
        }

        // Auto-suggest role based on department
        document.getElementById('department').addEventListener('change', function() {
            const department = this.value;
            const roleSelect = document.getElementById('userRole');
            const checkbox = document.getElementById('createUserAccount');

            if (checkbox.checked && department && departmentRoleMap[department]) {
                const suggestedRole = departmentRoleMap[department];
                // Find and select the suggested role
                for (let option of roleSelect.options) {
                    if (option.value === suggestedRole) {
                        option.selected = true;
                        break;
                    }
                }
            }
        });

        // Reset form function
        function resetForm() {
            document.getElementById('userAccountOptions').style.display = 'none';
            toggleUserAccountOptions();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleUserAccountOptions();

            // If there are old values, maintain the state
            @if (old('create_user_account'))
                document.getElementById('createUserAccount').checked = true;
                toggleUserAccountOptions();
            @endif
        });
    </script>
@endsection
