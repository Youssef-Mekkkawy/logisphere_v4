{{-- File: resources/views/permissions/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Permissions Management - LogiFlow')
@section('page-title', 'Permissions Management')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">🔑 Permissions Management</h2>
            <p style="color: #64748b;">Manage system permissions and access control</p>
        </div>
        @can('auth.roles.manage-permissions')
            <a href="{{ route('auth.permissions.create') }}" class="btn btn-primary">+ Add New Permission</a>
        @endcan
    </div>

    <!-- Filters -->
    <div
        style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <div class="form-group">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-input" value="{{ request('search') }}"
                    placeholder="Permission name or description...">
            </div>
            <div class="form-group">
                <label class="form-label">Group</label>
                <select name="group" class="form-input">
                    <option value="">All Groups</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                            {{ $group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-input">
                    <option value="">All Status</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('auth.permissions.index') }}" class="btn btn-secondary" style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </div>

    <!-- Permissions Table -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Permission Name</th>
                    <th>Group</th>
                    <th>Slug</th>
                    <th>Description</th>
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
                        <td>{{ $permission->description ?: 'No description' }}</td>
                        <td>
                            @if ($permission->is_active)
                                <span class="status-badge status-active">Active</span>
                            @else
                                <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('auth.permissions.show', $permission) }}" class="btn btn-secondary"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>

                            @can('auth.roles.manage-permissions')
                                <a href="{{ route('auth.permissions.edit', $permission) }}" class="btn btn-primary"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>

                                <form action="{{ route('auth.permissions.destroy', $permission) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
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

        @if ($permissions->hasPages())
            <div style="margin-top: 1.5rem; padding: 0 20px 20px;">
                {{ $permissions->links() }}
            </div>
        @endif
    </div>
@endsection
