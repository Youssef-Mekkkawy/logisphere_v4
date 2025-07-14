@extends('layouts.app')

@section('title', 'Edit User - LogiFlow')
@section('page-title', 'Edit User: ' . $user->name)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back to Users</a>
    <a href="{{ route('users.show', $user) }}" class="btn btn-primary">View User</a>
</div>

<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf
    @method('PUT')
    
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Username *</label>
            <input type="text" name="username" class="form-input" 
                   value="{{ old('username', $user->username) }}" required>
            @error('username')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" class="form-input" 
                   value="{{ old('name', $user->name) }}" required>
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" 
                   value="{{ old('email', $user->email) }}">
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Role *</label>
            <select name="role" class="form-input" required>
                <option value="">Select Role</option>
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
            @error('role')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-input">
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <small style="color: #6b7280; font-size: 12px;">Leave empty to keep current password</small>
        </div>
        
        <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-input">
            <small style="color: #6b7280; font-size: 12px;">Required only if changing password</small>
        </div>
    </div>
    
    <div style="margin-top: 30px;">
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">Cancel</a>
        
        @if($user->id !== auth()->id())
        <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline; margin-left: 10px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn" style="background: #dc2626; color: white;" 
                    onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                Delete User
            </button>
        </form>
        @endif
    </div>
</form>

<div style="margin-top: 40px; padding: 20px; background: #f8fafc; border-radius: 10px;">
    <h4 style="margin-bottom: 15px;">User Information</h4>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div>
            <strong>Created:</strong> {{ $user->created_at->format('M d, Y') }}
        </div>
        <div>
            <strong>Last Login:</strong> {{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never' }}
        </div>
        <div>
            <strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}
        </div>
    </div>
</div>
@endsection