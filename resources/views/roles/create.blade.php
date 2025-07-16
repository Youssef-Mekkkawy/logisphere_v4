{{-- File: resources/views/roles/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Role - LogiFlow')
@section('page-title', 'Create New Role')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">← Back to Roles</a>
    </div>

    <form method="POST" action="{{ route('roles.store') }}">
        @csrf

        <div
            style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 20px;">📝 Basic Information</h4>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Role Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Color *</label>
                    <input type="color" name="color" class="form-input" value="{{ old('color', '#3b82f6') }}" required>
                    @error('color')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span>Active Role</span>
                </label>
            </div>
        </div>

        <div
            style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 20px;">🔐 Permissions</h4>

            @foreach ($permissions as $group => $groupPermissions)
                <div style="margin-bottom: 25px; padding: 20px; background: #f8fafc; border-radius: 10px;">
                    <h5 style="color: #374151; margin-bottom: 15px; font-weight: 600;">{{ $group }}</h5>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                        @foreach ($groupPermissions as $permission)
                            <label
                                style="display: flex; align-items: center; gap: 8px; padding: 8px; background: white; border-radius: 6px; cursor: pointer;">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                <span style="font-size: 14px;">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Create Role</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
