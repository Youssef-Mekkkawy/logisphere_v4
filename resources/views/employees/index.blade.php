@extends('layouts.app')

@section('title', 'Employees - LogiFlow')
@section('page-title', 'Employee Management')

@section('content')

    <!-- Header Actions -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="color: #1e40af; margin: 0;">Employee Management</h2>
            <p style="color: #6b7280; margin: 5px 0 0 0;">Manage your company employees and their information</p>
        </div>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Employee
        </a>
    </div>

    <!-- Filters and Search -->
    <div
        style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
        <form method="GET" action="{{ route('employees.index') }}"
            style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Search Employees</label>
                <input type="text" name="search" class="form-input" placeholder="Search by name, ID, or email..."
                    value="{{ request('search') }}">
            </div>

            <div class="form-group" style="margin: 0;">
                <label class="form-label">Department</label>
                <select name="department" class="form-input">
                    <option value="">All Departments</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin: 0;">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                @if (request()->hasAny(['search', 'department', 'status']))
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Employee Statistics -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div
            style="background: linear-gradient(135deg, #3b82f6, #1e40af); color: white; padding: 20px; border-radius: 12px;">
            <h3 style="margin: 0; font-size: 2rem;">{{ $employees->total() }}</h3>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Total Employees</p>
        </div>

        <div
            style="background: linear-gradient(135deg, #059669, #047857); color: white; padding: 20px; border-radius: 12px;">
            <h3 style="margin: 0; font-size: 2rem;">{{ $employees->where('status', 'Active')->count() }}</h3>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Active Employees</p>
        </div>

        <div
            style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; padding: 20px; border-radius: 12px;">
            <h3 style="margin: 0; font-size: 2rem;">{{ $employees->where('status', 'Inactive')->count() }}</h3>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Inactive Employees</p>
        </div>

        <div
            style="background: linear-gradient(135deg, #7c3aed, #6d28d9); color: white; padding: 20px; border-radius: 12px;">
            <h3 style="margin: 0; font-size: 2rem;">{{ $departments->count() }}</h3>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Departments</p>
        </div>
    </div>

    <!-- Employees Table -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="data-table" style="margin: 0;">
                <thead>
                    <tr>
                        <th style="width: 120px;">Employee ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Contact</th>
                        <th>Hire Date</th>
                        <th>Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>
                                <code style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-weight: 600;">
                                    {{ $employee->employee_id }}
                                </code>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <div
                                        style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(45deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 12px;">
                                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $employee->name }}</div>
                                        @if ($employee->nationality)
                                            <small style="color: #6b7280;">{{ $employee->nationality }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge" style="background: #dbeafe; color: #1e40af;">
                                    {{ $employee->department ?: 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $employee->position ?: 'N/A' }}</td>
                            <td>
                                <div style="font-size: 14px;">
                                    @if ($employee->email)
                                        <div>📧 <a href="mailto:{{ $employee->email }}"
                                                style="color: #3b82f6;">{{ $employee->email }}</a></div>
                                    @endif
                                    @if ($employee->phone)
                                        <div>📞 <a href="tel:{{ $employee->phone }}"
                                                style="color: #3b82f6;">{{ $employee->phone }}</a></div>
                                    @endif
                                    @if (!$employee->email && !$employee->phone)
                                        <span style="color: #6b7280;">No contact info</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($employee->hire_date)
                                    <div>{{ $employee->hire_date->format('M d, Y') }}</div>
                                    <small style="color: #6b7280;">{{ $employee->hire_date->diffForHumans() }}</small>
                                @else
                                    <span style="color: #6b7280;">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($employee->status) }}">
                                    {{ $employee->status }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-primary"
                                        style="padding: 6px 12px; font-size: 14px;">
                                        View
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-secondary"
                                        style="padding: 6px 12px; font-size: 14px;">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 60px;">
                                <div style="color: #6b7280;">
                                    <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.5;"></i>
                                    <h3 style="margin: 0 0 10px 0;">No employees found</h3>
                                    <p style="margin: 0;">
                                        @if (request()->hasAny(['search', 'department', 'status']))
                                            No employees match your current filters. <a
                                                href="{{ route('employees.index') }}">Clear filters</a> or
                                        @endif
                                        <a href="{{ route('employees.create') }}">add your first employee</a>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($employees instanceof \Illuminate\Pagination\LengthAwarePaginator && $employees->hasPages())
            <div style="padding: 20px; border-top: 1px solid #e5e7eb;">
                {{ $employees->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Quick Actions Footer -->
    <div style="margin-top: 30px; text-align: center; color: #6b7280;">
        <p style="margin: 0;">
            Keyboard shortcuts:
            <kbd style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 12px;">Ctrl+E</kbd> Employees
            •
            <kbd style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 12px;">Ctrl+N</kbd> New
            Employee
        </p>
    </div>

@endsection

@section('scripts')
    <script>
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                window.location.href = "{{ route('employees.create') }}";
            }
        });

        // Auto-submit filters with delay
        let filterTimeout;
        document.querySelectorAll('select[name="department"], select[name="status"]').forEach(function(select) {
            select.addEventListener('change', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(() => {
                    this.closest('form').submit();
                }, 300);
            });
        });
    </script>
@endsection
