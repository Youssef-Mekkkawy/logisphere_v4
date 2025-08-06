{{-- File: resources/views/roles/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Roles Management - logisphere')
@section('page-title', 'Roles Management')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">🔐 Roles Management</h2>
            <p style="color: #64748b;">Manage user roles and permissions</p>
        </div>
        @can('auth.roles.create')
            <a href="{{ route('auth.roles.create') }}" class="btn btn-primary">+ Add New Role</a>
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
                    placeholder="Role name or description...">
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
                <a href="{{ route('auth.roles.index') }}" class="btn btn-secondary" style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </div>

    <!-- Roles Table -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Role Name</th>
                    <th>Description</th>
                    <th>Users Count</th>
                    <th>Status</th>
                    <th>Type</th>
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
                                {{ $role->users_count }} users
                            </span>
                        </td>
                        <td>
                            @if ($role->is_active)
                                <span class="status-badge status-active">Active</span>
                            @else
                                <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if ($role->is_system)
                                <span class="status-badge" style="background: #fbbf24; color: #92400e;">System</span>
                            @else
                                <span class="status-badge" style="background: #3b82f6; color: white;">Custom</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('auth.roles.show', $role) }}" class="btn btn-secondary"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>

                            @can('auth.roles.edit')
                                <a href="{{ route('auth.roles.edit', $role) }}" class="btn btn-primary"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @endcan

                            @can('auth.roles.delete')
                                @if (!$role->is_system)
                                    <form action="{{ route('auth.users.roles.destroy', $role) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                            No roles found.
                            @can('auth.roles.create')
                                <a href="{{ route('auth.roles.create') }}" style="color: var(--primary-color);">Create your first
                                    role</a>
                            @endcan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($roles->hasPages())
            <div style="margin-top: 1.5rem; padding: 0 20px 20px;">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
@endsection
