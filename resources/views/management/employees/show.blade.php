@extends('layouts.app')

@section('title', $employee->name . ' - logisphere')
@section('page-title', 'Employee Details')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('management.employees.index') }}" class="btn btn-secondary">← Back to Employees</a>
        <a href="{{ route('management.employees.edit', $employee) }}" class="btn btn-primary">Edit Employee</a>
    </div>

    <!-- 🔥 NEW: User Account Creation Success Message -->
    @if (session('user_created') && session('generated_password'))
        <div
            style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
            <h4 style="color: #166534; margin-bottom: 15px;">✅ User Account Created Successfully!</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <strong>Login Email:</strong><br>
                    <code style="background: #f0fdf4; padding: 4px 8px; border-radius: 4px;">{{ $employee->email }}</code>
                </div>
                <div>
                    <strong>Generated Password:</strong><br>
                    <code id="generated-password"
                        style="background: #f0fdf4; padding: 4px 8px; border-radius: 4px; font-weight: bold; color: #dc2626;">{{ session('generated_password') }}</code>
                    <button onclick="copyPassword()"
                        style="margin-left: 10px; padding: 2px 8px; background: #059669; color: white; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">Copy</button>
                </div>
            </div>
            <div style="background: #f0fdf4; padding: 15px; border-radius: 8px; margin-top: 15px;">
                <strong>⚠️ Important Security Notes:</strong>
                <ul style="margin: 10px 0 0 20px; color: #166534;">
                    <li>Save this password securely - it won't be shown again</li>
                    <li>Employee must change this password on first login</li>
                    <li>Account is active and ready for use</li>
                    <li>You can block/unblock this account anytime from User Management</li>
                </ul>
            </div>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Main Employee Info -->
        <div>
            <div
                style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <div
                        style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(45deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px; margin-right: 20px;">
                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 style="color: #1e40af; margin: 0;">{{ $employee->name }}</h3>
                        <p style="color: #6b7280; margin: 5px 0 0 0;">{{ $employee->position ?: 'Position not specified' }}
                        </p>
                        @if ($employee->user)
                            <div style="margin-top: 8px;">
                                <span class="status-badge status-{{ $employee->user->isActive() ? 'active' : 'blocked' }}">
                                    🔐 {{ $employee->user->statusText }} User Account
                                </span>
                                @if ($employee->user->mustChangePassword())
                                    <span class="status-badge" style="background: #f59e0b; color: white; margin-left: 5px;">
                                        🔑 Must Change Password
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Employee
                            ID</label>
                        <p style="margin: 0; font-family: monospace; font-size: 16px; color: #1e40af;">
                            {{ $employee->employee_id }}</p>
                    </div>

                    <div>
                        <label
                            style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Department</label>
                        <span class="status-badge"
                            style="background: #dbeafe; color: #1e40af;">{{ $employee->department ?: 'N/A' }}</span>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Status</label>
                        <span
                            class="status-badge status-{{ strtolower($employee->status) }}">{{ $employee->status }}</span>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Email</label>
                        <p style="margin: 0;">
                            @if ($employee->email)
                                <a href="mailto:{{ $employee->email }}" style="color: #3b82f6;">{{ $employee->email }}</a>
                                @if ($employee->user)
                                    <br><small style="color: #059669;">✓ System Access Available</small>
                                @endif
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Phone</label>
                        <p style="margin: 0;">
                            @if ($employee->phone)
                                <a href="tel:{{ $employee->phone }}" style="color: #3b82f6;">{{ $employee->phone }}</a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Hire
                            Date</label>
                        <p style="margin: 0;">{{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>

                    @if ($employee->salary)
                        <div>
                            <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Monthly
                                Salary</label>
                            <p style="margin: 0; font-weight: 600; color: #059669;">
                                ${{ number_format($employee->salary, 2) }}</p>
                        </div>
                    @endif

                    @if ($employee->hire_date)
                        <div>
                            <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Employment
                                Duration</label>
                            <p style="margin: 0;">{{ $employee->hire_date->diffForHumans() }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Account Management Section -->
            @if ($employee->user)
                <div
                    style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                    <h4 style="color: #1e40af; margin-bottom: 20px;">🔐 User Account Management</h4>

                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                        <div style="padding: 20px; background: #f8fafc; border-radius: 10px;">
                            <h5 style="color: #374151; margin-bottom: 10px;">Account Status</h5>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span class="status-badge status-{{ $employee->user->isActive() ? 'active' : 'blocked' }}">
                                    {{ $employee->user->statusText }}
                                </span>
                                @if (auth()->user()->isAdmin())
                                    <button class="btn {{ $employee->user->isActive() ? 'btn-warning' : 'btn-success' }}"
                                        style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"
                                        onclick="toggleEmployeeUserStatus({{ $employee->id }})">
                                        {{ $employee->user->isActive() ? 'Block Account' : 'Unblock Account' }}
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div style="padding: 20px; background: #f8fafc; border-radius: 10px;">
                            <h5 style="color: #374151; margin-bottom: 10px;">Password Status</h5>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if ($employee->user->mustChangePassword())
                                    <span class="status-badge" style="background: #f59e0b; color: white;">Must Change</span>
                                @else
                                    <span class="status-badge status-active">Up to Date</span>
                                @endif
                                @if (auth()->user()->isAdmin())
                                    <button class="btn btn-info" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"
                                        onclick="forceEmployeePasswordReset({{ $employee->user->id }})">
                                        Force Reset
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div style="padding: 15px; background: #f0f9ff; border-radius: 8px;">
                        <h6 style="color: #1e40af; margin-bottom: 10px;">Account Details</h6>
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 14px;">
                            <div><strong>Username:</strong> {{ $employee->user->username }}</div>
                            <div><strong>Roles:</strong>
                                {{ $employee->user->roles->pluck('name')->join(', ') ?: 'No roles assigned' }}</div>
                            <div><strong>Last Login:</strong>
                                {{ $employee->user->last_login ? $employee->user->last_login->format('M d, Y H:i') : 'Never' }}
                            </div>
                            <div><strong>Account Created:</strong> {{ $employee->user->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->isAdmin() || auth()->user()->hasPermission('employees.create'))
                <!-- Create User Account Section -->
                <div
                    style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                    <h4 style="color: #1e40af; margin-bottom: 20px;">🔐 Create User Account</h4>

                    <div style="padding: 20px; background: #fef3c7; border-radius: 10px; margin-bottom: 20px;">
                        <p style="margin: 0; color: #92400e;">
                            This employee doesn't have a user account yet. You can create one to give them system access.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('management.employees.create-user-account', $employee) }}">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">User Role</label>
                                <select name="user_role" class="form-input" required>
                                    <option value="">Select Role</option>
                                    @php
                                        $roles = \App\Models\Role::where('is_active', true)->get();
                                        $suggestedRole = match ($employee->department) {
                                            'Management' => 'manager',
                                            'Finance & Accounting' => 'finance',
                                            'Human Resources' => 'manager',
                                            default => 'user',
                                        };
                                    @endphp
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->slug }}"
                                            {{ $role->slug === $suggestedRole ? 'selected' : '' }}>
                                            {{ $role->name }}
                                            @if ($role->slug === $suggestedRole)
                                                (Suggested)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-input" required>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Create User Account
                        </button>
                    </form>
                </div>
            @endif

            <!-- Employment History -->
            <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <h4 style="color: #1e40af; margin-bottom: 20px;">Employment History & Assignments</h4>
                <div style="padding: 40px; text-align: center; color: #6b7280;">
                    <p>Assignment tracking and employment history features will be implemented in future updates.</p>
                    <p style="margin-top: 15px;">
                        <a href="{{ route('accounting.index') }}" class="btn btn-primary">View Accounting Module</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div>
            <!-- Quick Stats -->
            @if ($employee->salary)
                <div
                    style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                    <h4 style="color: #1e40af; margin-bottom: 15px;">Salary Information</h4>

                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>Monthly Salary</span>
                            <strong style="color: #059669;">${{ number_format($employee->salary, 2) }}</strong>
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>Annual Salary</span>
                            <strong style="color: #1e40af;">${{ number_format($employee->salary * 12, 2) }}</strong>
                        </div>
                    </div>

                    @if ($employee->hire_date)
                        <div style="margin-bottom: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 12px; color: #6b7280;">Total Earnings (Est.)</span>
                                <strong
                                    style="color: #6b7280;">${{ number_format($employee->salary * $employee->hire_date->diffInMonths(now()), 2) }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Employee Details -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Employee Details</h4>

                <div style="margin-bottom: 10px;">
                    <small style="color: #6b7280;">Employee ID</small>
                    <div style="font-family: monospace; font-weight: 600;">{{ $employee->employee_id }}</div>
                </div>

                @if ($employee->hire_date)
                    <div style="margin-bottom: 10px;">
                        <small style="color: #6b7280;">Hire Date</small>
                        <div>{{ $employee->hire_date->format('M d, Y') }}</div>
                    </div>
                @endif

                <div style="margin-bottom: 10px;">
                    <small style="color: #6b7280;">Record Created</small>
                    <div>{{ $employee->created_at->format('M d, Y') }}</div>
                </div>

                <div style="margin-bottom: 10px;">
                    <small style="color: #6b7280;">Last Updated</small>
                    <div>{{ $employee->updated_at->format('M d, Y H:i') }}</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Quick Actions</h4>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('management.employees.edit', $employee) }}" class="btn btn-primary"
                        style="text-align: center;">
                        Edit Employee
                    </a>

                    @if ($employee->user)
                        <a href="{{ route('auth.users.show', $employee->user) }}" class="btn btn-secondary"
                            style="text-align: center;">
                            View User Account
                        </a>
                    @endif

                    @if ($employee->email)
                        <a href="mailto:{{ $employee->email }}" class="btn btn-secondary" style="text-align: center;">
                            Send Email
                        </a>
                    @endif

                    @if ($employee->phone)
                        <a href="tel:{{ $employee->phone }}" class="btn btn-secondary" style="text-align: center;">
                            Call Employee
                        </a>
                    @endif

                    <a href="{{ route('accounting.index') }}" class="btn btn-secondary" style="text-align: center;">
                        View Accounting
                    </a>

                    @if (auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('management.employees.destroy', $employee) }}"
                            style="margin-top: 10px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background: #dc2626; color: white; width: 100%;"
                                onclick="return confirm('Are you sure you want to delete this employee? This action cannot be undone.')">
                                Delete Employee
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Copy generated password to clipboard
        function copyPassword() {
            const passwordElement = document.getElementById('generated-password');
            const password = passwordElement.textContent;

            navigator.clipboard.writeText(password).then(function() {
                // Show success message
                passwordElement.style.background = '#dcfce7';
                setTimeout(() => {
                    passwordElement.style.background = '#f0fdf4';
                }, 2000);

                // Change button text temporarily
                const btn = event.target;
                const originalText = btn.textContent;
                btn.textContent = 'Copied!';
                setTimeout(() => {
                    btn.textContent = originalText;
                }, 2000);
            }).catch(function(err) {
                alert('Failed to copy password. Please copy manually.');
            });
        }

        // Toggle employee user status
        function toggleEmployeeUserStatus(employeeId) {
            if (!confirm('Are you sure you want to change this user account status?')) {
                return;
            }

            fetch(`/management/employees/${employeeId}/toggle-user-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload to show updated status
                    } else {
                        alert(data.message || 'Failed to update user status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating user status');
                });
        }

        // Force password reset for employee user
        function forceEmployeePasswordReset(userId) {
            if (!confirm('Are you sure you want to force this user to change their password on next login?')) {
                return;
            }

            fetch(`/auth/users/${userId}/force-password-reset`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to force password reset');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while forcing password reset');
                });
        }
    </script>
@endsection
