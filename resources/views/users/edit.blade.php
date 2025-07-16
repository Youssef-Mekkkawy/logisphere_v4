{{-- File: resources/views/users/edit.blade.php (Updated) --}}
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

        <div
            style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 20px;">👤 User Information</h4>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-input" value="{{ old('username', $user->username) }}"
                        required>
                    @error('username')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}"
                        required>
                    @error('email')
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
        </div>

        <div
            style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 20px;">🔐 Roles & Permissions</h4>

            <div class="form-group">
                <label class="form-label">Assign Roles *</label>
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-top: 10px;">
                    @foreach ($roles as $role)
                        <label
                            style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #f8fafc; border-radius: 8px; cursor: pointer; border: 2px solid transparent;">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
                            <span class="status-badge"
                                style="background: {{ $role->color }}; color: white; margin-right: 5px;">
                                {{ $role->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Current Permissions -->
            <div style="margin-top: 20px;">
                <h5 style="color: #374151; margin-bottom: 15px;">Current Permissions</h5>
                <div style="padding: 15px; background: #f1f5f9; border-radius: 8px;">
                    @forelse($user->permissions()->get()->groupBy('group') as $group => $permissions)
                        <div style="margin-bottom: 10px;">
                            <strong style="color: #1e40af;">{{ $group }}:</strong>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 5px;">
                                @foreach ($permissions as $permission)
                                    <span
                                        style="background: #e5e7eb; padding: 2px 8px; border-radius: 4px; font-size: 12px;">{{ $permission->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <em style="color: #6b7280;">No permissions assigned</em>
                    @endforelse
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">Cancel</a>

            @if ($user->id !== auth()->id())
                @can('users.delete')
                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                        style="display: inline; margin-left: 10px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background: #dc2626; color: white;"
                            onclick="return confirm('Are you sure you want to delete this user?')">
                            Delete User
                        </button>
                    </form>
                @endcan
            @endif
        </div>
    </form>
@endsection
