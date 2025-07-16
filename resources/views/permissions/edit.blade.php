{{-- File: resources/views/permissions/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Permission - LogiFlow')
@section('page-title', 'Edit Permission: ' . $permission->name)

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">← Back to Permissions</a>
        <a href="{{ route('permissions.show', $permission) }}" class="btn btn-primary">View Permission</a>
    </div>

    <form method="POST" action="{{ route('permissions.update', $permission) }}">
        @csrf
        @method('PUT')

        <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <h4 style="color: #1e40af; margin-bottom: 20px;">📝 Permission Information</h4>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Permission Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $permission->name) }}"
                        required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Group *</label>
                    <select name="group" class="form-input" required>
                        <option value="">Select Group</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group }}"
                                {{ old('group', $permission->group) == $group ? 'selected' : '' }}>{{ $group }}
                            </option>
                        @endforeach
                        <option value="new" {{ old('group') == 'new' ? 'selected' : '' }}>+ Create New Group</option>
                    </select>
                    @error('group')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group" id="new-group-field" style="display: none;">
                <label class="form-label">New Group Name</label>
                <input type="text" name="new_group" class="form-input" placeholder="Enter new group name">
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="3">{{ old('description', $permission->description) }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $permission->is_active) ? 'checked' : '' }}>
                    <span>Active Permission</span>
                </label>
            </div>

            <!-- Current Slug Preview -->
            <div class="form-group">
                <label class="form-label">Current Slug</label>
                <div style="padding: 10px; background: #f1f5f9; border-radius: 8px;">
                    <code style="color: #1e40af;">{{ $permission->slug }}</code>
                </div>
                <small style="color: #6b7280; font-size: 12px;">Slug will be updated based on group and name changes</small>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Update Permission</button>
            <a href="{{ route('permissions.show', $permission) }}" class="btn btn-secondary">Cancel</a>

            @if ($permission->roles->count() === 0)
                <form method="POST" action="{{ route('permissions.destroy', $permission) }}"
                    style="display: inline; margin-left: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background: #dc2626; color: white;"
                        onclick="return confirm('Are you sure you want to delete this permission?')">
                        Delete Permission
                    </button>
                </form>
            @endif
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const groupSelect = document.querySelector('select[name="group"]');
            const newGroupField = document.getElementById('new-group-field');
            const newGroupInput = document.querySelector('input[name="new_group"]');

            groupSelect.addEventListener('change', function() {
                if (this.value === 'new') {
                    newGroupField.style.display = 'block';
                    newGroupInput.required = true;
                } else {
                    newGroupField.style.display = 'none';
                    newGroupInput.required = false;
                }
            });

            // Trigger on page load if "new" is selected
            if (groupSelect.value === 'new') {
                newGroupField.style.display = 'block';
                newGroupInput.required = true;
            }
        });
    </script>
@endsection
