{{-- File: resources/views/users/create.blade.php (Updated) --}}
@extends('layouts.app')

@section('title', 'Create User - LogiFlow')
@section('page-title', 'Create New User')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back to Users</a>
    </div>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div
            style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 20px;">👤 User Information</h4>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-input" value="{{ old('username') }}" required>
                    @error('username')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small style="color: #6b7280; font-size: 12px;">Username must be unique and cannot contain
                        spaces</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
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
                    <small style="color: #6b7280; font-size: 12px;">Minimum 6 characters</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                    <small style="color: #6b7280; font-size: 12px;">Must match the password above</small>
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
                                {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
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

            <!-- Role Permissions Preview -->
            <div style="margin-top: 20px;">
                <h5 style="color: #374151; margin-bottom: 15px;">Role Permissions Preview</h5>
                <div id="permissions-preview"
                    style="padding: 15px; background: #f1f5f9; border-radius: 8px; color: #6b7280;">
                    Select roles above to see their permissions
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        // Role permissions data
        const rolePermissions = {
            @foreach ($roles as $role)
                "{{ $role->id }}": {
                    name: "{{ $role->name }}",
                    color: "{{ $role->color }}",
                    permissions: @json($role->permissions->pluck('name')->toArray())
                },
            @endforeach
        };

        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="roles[]"]');
            const previewDiv = document.getElementById('permissions-preview');

            function updatePermissionsPreview() {
                const selectedRoles = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (selectedRoles.length === 0) {
                    previewDiv.innerHTML = '<em>Select roles above to see their permissions</em>';
                    return;
                }

                let html = '';
                selectedRoles.forEach(roleId => {
                    const role = rolePermissions[roleId];
                    if (role) {
                        html += `
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <span class="status-badge" style="background: ${role.color}; color: white;">${role.name}</span>
                                <span style="font-size: 12px; color: #6b7280;">${role.permissions.length} permissions</span>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                ${role.permissions.map(perm => `<span style="background: #e5e7eb; padding: 2px 8px; border-radius: 4px; font-size: 12px;">${perm}</span>`).join('')}
                            </div>
                        </div>
                    `;
                    }
                });

                previewDiv.innerHTML = html;
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updatePermissionsPreview);
            });

            // Update on page load
            updatePermissionsPreview();
        });
    </script>
@endsection
