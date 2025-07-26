@extends('layouts.app')

@section('title', 'Users - LogiFlow')
@section('page-title', 'Users')

@section('content')
<div class="tabs">
    <div class="tab active" data-tab="all-users">All Users</div>
    <div class="tab" data-tab="add-user">Add User</div>
    <div class="tab" data-tab="user-roles">User Roles</div>
</div>

<div id="all-users-tab" class="tab-content active">
    <input type="text" class="search-bar" placeholder="Search users...">
    <a href="{{ route('auth.users.create') }}" class="btn btn-primary">Add New User</a>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Last Login</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->username }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="status-badge status-{{ $user->role }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td>{{ $user->last_login ? $user->last_login->format('Y-m-d H:i') : 'Never' }}</td>
                <td>
                    @if($user->id !== auth()->id())
                        <a href="{{ route('auth.users.edit', $user) }}" class="btn btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('auth.users.destroy', $user) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" 
                                    onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </form>
                    @else
                        <span style="color: #6b7280; font-size: 12px;">Current User</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px;">
                    <p style="color: #6b7280;">No users found.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    @endif
</div>

<div id="add-user-tab" class="tab-content">
    <h3>Add New User</h3>
    <form method="POST" action="{{ route('auth.users.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Username *</label>
                <input type="text" name="username" class="form-input" 
                       value="{{ old('username') }}" required>
                @error('username')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-input" 
                       value="{{ old('name') }}" required>
                @error('name')
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
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-input" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password *</label>
                <input type="password" name="password_confirmation" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-input">
                    <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </form>
</div>

<div id="user-roles-tab" class="tab-content">
    <h3>User Role Permissions</h3>
    <div class="form-group">
        <label class="form-label">Select Role</label>
        <select class="form-input">
            <option>Administrator</option>
            <option>Manager</option>
            <option>User</option>
        </select>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">
        <label><input type="checkbox" checked> View Shipments</label>
        <label><input type="checkbox" checked> Create Shipments</label>
        <label><input type="checkbox" checked> Edit Shipments</label>
        <label><input type="checkbox"> Delete Shipments</label>
        <label><input type="checkbox" checked> View Companies</label>
        <label><input type="checkbox"> Manage Users</label>
        <label><input type="checkbox" checked> View Reports</label>
        <label><input type="checkbox"> System Settings</label>
    </div>
    <button class="btn btn-primary" style="margin-top: 20px;">Save Permissions</button>
</div>
@endsection