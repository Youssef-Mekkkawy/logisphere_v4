{{-- File: resources/views/users/index.blade.php (Integrated Management) --}}
@extends('layouts.app')

@section('title', 'User Management - LogiFlow')
@section('page-title', 'User Management (Ctrl+U)')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">👥 User Management</h2>
            <p style="color: #64748b;">Manage users, roles, and permissions</p>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="tabs">
        <div class="tab active" data-tab="users">👥 Users</div>
        <div class="tab" data-tab="roles">🔐 Roles</div>
        <div class="tab" data-tab="permissions">🔑 Permissions</div>
    </div>

    <!-- Users Tab -->
    <div id="users-tab" class="tab-content active">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3>Users List</h3>
            @can('users.create')
                <button class="btn btn-primary" onclick="openModal('createUserModal')">+ Add New User</button>
            @endcan
        </div>

        <!-- Users Filter -->
        <div
            style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
            <form method="GET"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-input" value="{{ request('search') }}"
                        placeholder="Name, username, email...">
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-input">
                        <option value="">All Roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary" style="margin-left: 0.5rem;">Clear</a>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @forelse($user->roles as $role)
                                    <span class="status-badge"
                                        style="background: {{ $role->color }}; color: white; margin-right: 5px; margin-bottom: 2px;">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="status-badge" style="background: #6b7280; color: white;">No Role</span>
                                @endforelse
                            </td>
                            <td>
                                @if ($user->last_login)
                                    {{ $user->last_login->format('M d, Y H:i') }}
                                @else
                                    <span style="color: #64748b;">Never</span>
                                @endif
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                    onclick="viewUser({{ $user->id }})">View</button>

                                @can('users.edit')
                                    <button class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                        onclick="editUser({{ $user->id }})">Edit</button>
                                @endcan

                                @can('users.delete')
                                    @if ($user->id !== auth()->id())
                                        <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                            onclick="deleteUser({{ $user->id }})">Delete</button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                No users found.
                                @can('users.create')
                                    <button class="btn btn-primary" onclick="openModal('createUserModal')">Create your first
                                        user</button>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($users->hasPages())
                <div style="margin-top: 1.5rem; padding: 0 20px 20px;">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Roles Tab -->
    <div id="roles-tab" class="tab-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3>Roles Management</h3>
            @can('roles.create')
                <button class="btn btn-primary" onclick="openModal('createRoleModal')">+ Add New Role</button>
            @endcan
        </div>

        <!-- Roles Table -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th>Users</th>
                        <th>Permissions</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span class="status-badge" style="background: {{ $role->color }}; color: white;">
                                        {{ $role->name }}
                                    </span>
                                </div>
                            </td>
                            <td>{{ $role->description ?: 'No description' }}</td>
                            <td>
                                <span class="status-badge" style="background: #f3f4f6; color: #374151;">
                                    {{ $role->users->count() }} users
                                </span>
                            </td>
                            <td>
                                <span class="status-badge" style="background: #dbeafe; color: #1e40af;">
                                    {{ $role->permissions->count() }} permissions
                                </span>
                            </td>
                            <td>
                                @if ($role->is_active)
                                    <span class="status-badge status-active">Active</span>
                                @else
                                    <span class="status-badge status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                    onclick="viewRole({{ $role->id }})">View</button>

                                @can('roles.edit')
                                    <button class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                        onclick="editRole({{ $role->id }})">Edit</button>
                                @endcan

                                @can('roles.delete')
                                    @if (!$role->is_system)
                                        <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                            onclick="deleteRole({{ $role->id }})">Delete</button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                No roles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Permissions Tab -->
    <div id="permissions-tab" class="tab-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3>Permissions Management</h3>
            @can('roles.manage-permissions')
                <button class="btn btn-primary" onclick="openModal('createPermissionModal')">+ Add New Permission</button>
            @endcan
        </div>

        <!-- Permissions Table -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Permission Name</th>
                        <th>Group</th>
                        <th>Slug</th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                        <tr>
                            <td><strong>{{ $permission->name }}</strong></td>
                            <td>
                                <span class="status-badge" style="background: #3b82f6; color: white;">
                                    {{ $permission->group }}
                                </span>
                            </td>
                            <td>
                                <code style="background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-size: 12px;">
                                    {{ $permission->slug }}
                                </code>
                            </td>
                            <td>
                                <span class="status-badge" style="background: #f3f4f6; color: #374151;">
                                    {{ $permission->roles->count() }} roles
                                </span>
                            </td>
                            <td>
                                @if ($permission->is_active)
                                    <span class="status-badge status-active">Active</span>
                                @else
                                    <span class="status-badge status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                    onclick="viewPermission({{ $permission->id }})">View</button>

                                @can('roles.manage-permissions')
                                    <button class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                        onclick="editPermission({{ $permission->id }})">Edit</button>
                                    <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                        onclick="deletePermission({{ $permission->id }})">Delete</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                No permissions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="createUserModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Create New User</h4>
                <button class="close" onclick="closeModal('createUserModal')">&times;</button>
            </div>
            <form id="createUserForm">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username *</label>
                            <input type="text" name="username" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Assign Roles</label>
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            @foreach ($roles as $role)
                                <label
                                    style="display: flex; align-items: center; gap: 8px; padding: 8px; background: #f8fafc; border-radius: 6px; cursor: pointer;">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}">
                                    <span class="status-badge"
                                        style="background: {{ $role->color }}; color: white;">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <button type="button" class="btn btn-secondary"
                        onclick="closeModal('createUserModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Role Modal -->
    <div id="createRoleModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Create New Role</h4>
                <button class="close" onclick="closeModal('createRoleModal')">&times;</button>
            </div>
            <form id="createRoleForm">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Role Name *</label>
                            <input type="text" name="name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Color *</label>
                            <input type="color" name="color" class="form-input" value="#3b82f6" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-input" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Permissions</label>
                        <div
                            style="max-height: 300px; overflow-y: auto; border: 1px solid #d1d5db; border-radius: 8px; padding: 15px;">
                            @foreach ($permissions->groupBy('group') as $group => $groupPermissions)
                                <div style="margin-bottom: 20px;">
                                    <h6 style="color: #1e40af; margin-bottom: 10px;">{{ $group }}</h6>
                                    <div
                                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 5px;">
                                        @foreach ($groupPermissions as $permission)
                                            <label
                                                style="display: flex; align-items: center; gap: 8px; padding: 4px; cursor: pointer;">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $permission->id }}">
                                                <span style="font-size: 14px;">{{ $permission->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Role</button>
                    <button type="button" class="btn btn-secondary"
                        onclick="closeModal('createRoleModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Permission Modal -->
    <div id="createPermissionModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Create New Permission</h4>
                <button class="close" onclick="closeModal('createPermissionModal')">&times;</button>
            </div>
            <form id="createPermissionForm">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Permission Name *</label>
                            <input type="text" name="name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Group *</label>
                            <select name="group" class="form-input" required>
                                <option value="">Select Group</option>
                                @foreach ($permissions->pluck('group')->unique() as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                                <option value="new">+ Create New Group</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="newGroupField" style="display: none;">
                        <label class="form-label">New Group Name</label>
                        <input type="text" name="new_group" class="form-input" placeholder="Enter new group name">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-input" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Permission</button>
                    <button type="button" class="btn btn-secondary"
                        onclick="closeModal('createPermissionModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('styles')
    <style>
        /* Tab Styles */
        .tabs {
            display: flex;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .tab {
            padding: 15px 30px;
            cursor: pointer;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab:hover {
            background: #f1f5f9;
        }

        .tab.active {
            background: var(--primary-color);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-header h4 {
            margin: 0;
            color: #1e40af;
        }

        .close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
        }

        .close:hover {
            color: #dc2626;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-footer {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding: 20px 30px;
            border-top: 1px solid #e5e7eb;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tabs {
                flex-direction: column;
            }

            .tab {
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
            }

            .modal-content {
                width: 95%;
                margin: 20px;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Show corresponding content
                    const targetContent = document.getElementById(this.dataset.tab + '-tab');
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });

            // Group field toggle
            const groupSelect = document.querySelector('select[name="group"]');
            const newGroupField = document.getElementById('newGroupField');

            if (groupSelect) {
                groupSelect.addEventListener('change', function() {
                    if (this.value === 'new') {
                        newGroupField.style.display = 'block';
                    } else {
                        newGroupField.style.display = 'none';
                    }
                });
            }

            // Form submissions
            document.getElementById('createUserForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this, '{{ route('users.store') }}', 'User created successfully!');
            });

            document.getElementById('createRoleForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this, '{{ route('roles.store') }}', 'Role created successfully!');
            });

            document.getElementById('createPermissionForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this, '{{ route('permissions.store') }}', 'Permission created successfully!');
            });
        });

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Form submission
        function submitForm(form, url, successMessage) {
            const formData = new FormData(form);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(successMessage);
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Something went wrong'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request');
                });
        }

        // Action functions
        function viewUser(id) {
            window.location.href = `/users/${id}`;
        }

        function editUser(id) {
            window.location.href = `/users/${id}/edit`;
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/users/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('User deleted successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Something went wrong'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the user');
                    });
            }
        }

        function viewRole(id) {
            // You can implement a modal or redirect to a detail view
            alert('Role view functionality - ID: ' + id);
        }

        function editRole(id) {
            // You can implement a modal or redirect to edit form
            alert('Role edit functionality - ID: ' + id);
        }

        function deleteRole(id) {
            if (confirm('Are you sure you want to delete this role?')) {
                fetch(`/roles/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Role deleted successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Something went wrong'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the role');
                    });
            }
        }

        function viewPermission(id) {
            alert('Permission view functionality - ID: ' + id);
        }

        function editPermission(id) {
            alert('Permission edit functionality - ID: ' + id);
        }

        function deletePermission(id) {
            if (confirm('Are you sure you want to delete this permission?')) {
                fetch(`/permissions/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Permission deleted successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Something went wrong'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the permission');
                    });
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Ctrl+U keyboard shortcut
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'u') {
                e.preventDefault();
                document.querySelector('input[name="search"]')?.focus();
            }
        });
    </script>
@endsection
