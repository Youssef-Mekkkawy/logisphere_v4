{{-- File: resources/views/roles/show.blade.php --}}
@extends('layouts.app')

@section('title', $role->name . ' - LogiFlow')
@section('page-title', 'Role Details')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('auth.roles.index') }}" class="btn btn-secondary">← Back to Roles</a>
        @can('auth.roles.edit')
            <a href="{{ route('auth.roles.edit', $role) }}" class="btn btn-primary">Edit Role</a>
        @endcan
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Main Role Info -->
        <div>
            <div
                style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <div
                        style="width: 60px; height: 60px; border-radius: 50%; background: {{ $role->color }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px; margin-right: 20px;">
                        {{ strtoupper(substr($role->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 style="color: #1e40af; margin: 0;">{{ $role->name }}</h3>
                        <p style="color: #6b7280; margin: 5px 0 0 0;">{{ $role->description ?: 'No description' }}</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Status</label>
                        @if ($role->is_active)
                            <span class="status-badge status-active">Active</span>
                        @else
                            <span class="status-badge status-inactive">Inactive</span>
                        @endif
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Type</label>
                        @if ($role->is_system)
                            <span class="status-badge" style="background: #fbbf24; color: #92400e;">System Role</span>
                        @else
                            <span class="status-badge" style="background: #3b82f6; color: white;">Custom Role</span>
                        @endif
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Users</label>
                        <span class="status-badge" style="background: #f3f4f6; color: #374151;">{{ $role->users->count() }}
                            users</span>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Created</label>
                        <p style="margin: 0;">{{ $role->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <h4 style="color: #1e40af; margin-bottom: 20px;">🔐 Permissions ({{ $role->permissions->count() }})</h4>

                @forelse($groupedPermissions as $group => $permissions)
                    <div style="margin-bottom: 25px; padding: 20px; background: #f8fafc; border-radius: 10px;">
                        <h5 style="color: #374151; margin-bottom: 15px; font-weight: 600;">{{ $group }}</h5>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            @foreach ($permissions as $permission)
                                <div
                                    style="display: flex; align-items: center; gap: 8px; padding: 8px; background: white; border-radius: 6px;">
                                    <span style="color: #10b981; font-size: 14px;">✓</span>
                                    <span style="font-size: 14px;">{{ $permission->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #6b7280; padding: 40px;">No permissions assigned to this role.</p>
                @endforelse
            </div>
        </div>

        <!-- Sidebar Info -->
        <div>
            <!-- Role Stats -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Role Statistics</h4>

                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span>Total Users</span>
                        <strong style="color: #059669;">{{ $role->users->count() }}</strong>
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span>Permissions</span>
                        <strong style="color: #1e40af;">{{ $role->permissions->count() }}</strong>
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span>Created</span>
                        <strong style="color: #6b7280;">{{ $role->created_at->format('M d, Y') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Users with this role -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Users with this Role</h4>

                @forelse($role->users as $user)
                    <div
                        style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding: 10px; background: #f8fafc; border-radius: 8px;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 50%; background: {{ $role->color }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 500; font-size: 14px;">{{ $user->name }}</div>
                            <div style="color: #6b7280; font-size: 12px;">{{ $user->username }}</div>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #6b7280; padding: 20px;">No users assigned to this role.</p>
                @endforelse
            </div>

            <!-- Quick Actions -->
            <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Quick Actions</h4>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @can('auth.roles.edit')
                        <a href="{{ route('auth.roles.edit', $role) }}" class="btn btn-primary" style="text-align: center;">
                            Edit Role
                        </a>
                    @endcan

                    @can('auth.roles.delete')
                        @if (!$role->is_system && $role->users->count() === 0)
                            <form method="POST" action="{{ route('auth.users.roles.destroy', $role) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="background: #dc2626; color: white; width: 100%;"
                                    onclick="return confirm('Are you sure you want to delete this role?')">
                                    Delete Role
                                </button>
                            </form>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
