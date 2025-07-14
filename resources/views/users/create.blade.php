@extends('layouts.app')

@section('title', 'Create User - LogiFlow')
@section('page-title', 'Create New User')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back to Users</a>
</div>

<form method="POST" action="{{ route('users.store') }}">
    @csrf
    
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Username *</label>
            <input type="text" name="username" class="form-input" 
                   value="{{ old('username') }}" required>
            @error('username')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <small style="color: #6b7280; font-size: 12px;">Username must be unique and cannot contain spaces</small>
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
            <label class="form-label">Role *</label>
            <select name="role" class="form-input" required>
                <option value="">Select Role</option>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
            @error('role')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-input" required>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <small style="color: #6b7280; font-size: 12px;">Minimum 6 characters</small>
        </div>
        
        <div class="form-group">
            <label class="form-label">Confirm Password *</label>
            <input type="password" name="password_confirmation" class="form-input" required>
            <small style="color: #6b7280; font-size: 12px;">Must match the password above</small>
        </div>
    </div>
    
    <!-- Role Permissions Info -->
    <div style="margin: 30px 0; padding: 20px; background: #f8fafc; border-radius: 10px;">
        <h4 style="margin-bottom: 15px;">Role Permissions</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <strong style="color: #dc2626;">Administrator</strong>
                <ul style="margin: 5px 0; padding-left: 20px; font-size: 14px; color: #6b7280;">
                    <li>Full system access</li>
                    <li>User management</li>
                    <li>System settings</li>
                    <li>All CRUD operations</li>
                </ul>
            </div>
            <div>
                <strong style="color: #d97706;">Manager</strong>
                <ul style="margin: 5px 0; padding-left: 20px; font-size: 14px; color: #6b7280;">
                    <li>Manage shipments</li>
                    <li>Manage companies</li>
                    <li>View reports</li>
                    <li>Limited accounting access</li>
                </ul>
            </div>
            <div>
                <strong style="color: #059669;">User</strong>
                <ul style="margin: 5px 0; padding-left: 20px; font-size: 14px; color: #6b7280;">
                    <li>View shipments</li>
                    <li>View companies</li>
                    <li>Basic operations</li>
                    <li>No admin access</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 30px;">
        <button type="submit" class="btn btn-primary">Create User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection