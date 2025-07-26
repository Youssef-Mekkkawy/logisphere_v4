{{-- File: resources/views/auth/users/index.blade.php (Compatible with Your Layout) --}}
@extends('layouts.app')

@section('title', 'User Management - LogiFlow')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">👥 User Management</h2>
        <p style="color: #64748b;">Manage users, roles, and permissions</p>
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
            <button class="btn btn-primary" onclick="openModal('createUserModal')">+ Add New User</button>
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
                    <a href="{{ route('auth.users.index') }}" class="btn btn-secondary" style="margin-left: 0.5rem;">Clear</a>
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
                                        style="width: 40px; height: 40px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
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
                                <a href="{{ route('auth.users.show', $user) }}" class="btn btn-secondary"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <a href="{{ route('auth.users.edit', $user) }}" class="btn btn-primary"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                @if ($user->id !== auth()->id())
                                    <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                        onclick="deleteUser({{ $user->id }})">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                No users found.
                                <button class="btn btn-primary" onclick="openModal('createUserModal')">Create your first
                                    user</button>
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
            <button class="btn btn-primary" onclick="openModal('createRoleModal')">+ Add New Role</button>
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
                                @if (!$role->is_system)
                                    <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                        onclick="deleteRole({{ $role->id }})">Delete</button>
                                @endif
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
            <button class="btn btn-primary" onclick="openPermissionModal()">+ Add New Permission</button>
        </div>

        <!-- Debug Section -->
        {{-- <div
            style="margin-bottom: 10px; padding: 10px; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 4px;">
            <small style="color: #0369a1;">
                <strong>Debug:</strong>
                <button onclick="debugModal()" class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;">Test
                    Modal</button>
                <button onclick="testJavaScript()" class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;">Test
                    JS</button>
            </small>
        </div> --}}

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
                                <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                    onclick="deletePermission({{ $permission->id }})">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                <div style="padding: 40px;">
                                    <div style="font-size: 3rem; margin-bottom: 15px;">🔑</div>
                                    <h4 style="margin: 0 0 10px 0; color: #374151;">No permissions found</h4>
                                    <p style="margin: 0 0 20px 0; color: #6b7280;">
                                        Permissions control what users can do in the system.
                                    </p>
                                    <button class="btn btn-primary" onclick="openPermissionModal()">
                                        Create your first permission
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Help Section --}}
        <div
            style="margin-top: 20px; padding: 20px; background: #f8fafc; border-radius: 12px; border-left: 4px solid #3b82f6;">
            <h5 style="color: #1e40af; margin-bottom: 10px;">💡 Permission Tips</h5>
            <ul style="color: #374151; margin: 0; padding-left: 20px;">
                <li><strong>Permission Naming:</strong> Use descriptive names like "View Users", "Create Shipments"</li>
                <li><strong>Groups:</strong> Organize permissions by module (Users, Shipments, Companies, etc.)</li>
                <li><strong>Slug Generation:</strong> Automatically created as "group.permission-name"</li>
                <li><strong>Role Assignment:</strong> Assign permissions to roles, then roles to users</li>
            </ul>
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
            <div id="permission-form-errors"
                style="display: none; background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 10px; margin: 15px 30px; border-radius: 5px;">
            </div>
            <form id="createPermissionForm">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Permission Name *</label>
                            <input type="text" name="name" class="form-input" required
                                placeholder="e.g., View Users">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Group *</label>
                            <select name="group" id="permissionGroupSelect" class="form-input" required>
                                <option value="">Select Group</option>
                                @foreach ($permissions->pluck('group')->unique() as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                                <option value="new">+ Create New Group</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="newGroupField" style="display: none;">
                        <label class="form-label">New Group Name *</label>
                        <input type="text" name="new_group" class="form-input" placeholder="e.g., Reports">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-input" rows="3"
                            placeholder="Describe what this permission allows users to do"></textarea>
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
    <script>
        // ===== PERMISSION SPECIFIC FUNCTIONS =====
        window.openPermissionModal = function() {
            console.log('🔑 Opening permission modal');
            openModal('createPermissionModal');
        };

        // ===== PERMISSION ACTIONS =====
        window.deletePermission = function(id) {
            if (confirm('Are you sure you want to delete this permission?')) {
                fetch(`/auth/users/permissions/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json'
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
        };

        window.viewPermission = function(id) {
            alert('Permission view functionality - ID: ' + id);
        };
        const permissionForm = document.getElementById('createPermissionForm');
        if (permissionForm) {
            permissionForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('📝 Permission form submitted');

                const formData = new FormData(this);
                const errorDiv = document.getElementById('permission-form-errors');
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

                // Clear previous errors
                if (errorDiv) errorDiv.style.display = 'none';

                // Show loading state
                submitBtn.textContent = 'Creating...';
                submitBtn.disabled = true;

                fetch('/auth/users/permissions', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;

                        if (data.success) {
                            alert('✅ Permission created successfully!');
                            closeModal('createPermissionModal');
                            location.reload();
                        } else {
                            // Show error
                            if (errorDiv) {
                                errorDiv.innerHTML = '<strong>Error:</strong> ' + (data.message ||
                                    'Something went wrong');
                                errorDiv.style.display = 'block';
                            } else {
                                alert('Error: ' + (data.message || 'Something went wrong'));
                            }
                        }
                    })
                    .catch(error => {
                        console.error('❌ Error:', error);

                        // Reset button
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;

                        // Show error
                        const message = 'An error occurred while creating the permission';
                        if (errorDiv) {
                            errorDiv.innerHTML = '<strong>Error:</strong> ' + message;
                            errorDiv.style.display = 'block';
                        } else {
                            alert('Error: ' + message);
                        }
                    });
            });
        }
        console.log('Available functions:', Object.keys(window).filter(key =>
            typeof window[key] === 'function' &&
            (key.includes('Modal') || key.includes('User') || key.includes('Role') || key.includes(
                'Permission'))
        ));
    </script>
@endsection
