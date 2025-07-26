{{-- File: resources/views/permissions/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Permission - LogiFlow')
@section('page-title', 'Create New Permission')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('auth.permissions.index') }}" class="btn btn-secondary">← Back to Permissions</a>
    </div>

    <form method="POST" action="{{ route('auth.permissions.store') }}">
        @csrf

        <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <h4 style="color: #1e40af; margin-bottom: 20px;">📝 Permission Information</h4>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Permission Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small style="color: #6b7280; font-size: 12px;">e.g., "View Users", "Create Shipments"</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Group *</label>
                    <select name="group" class="form-input" required>
                        <option value="">Select Group</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group }}" {{ old('group') == $group ? 'selected' : '' }}>
                                {{ $group }}</option>
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
                <textarea name="description" class="form-input" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span>Active Permission</span>
                </label>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Create Permission</button>
            <a href="{{ route('auth.permissions.index') }}" class="btn btn-secondary">Cancel</a>
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
