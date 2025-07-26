{{-- File: resources/views/permissions/show.blade.php --}}
@extends('layouts.app')

@section('title', $permission->name . ' - LogiFlow')
@section('page-title', 'Permission Details')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('auth.permissions.index') }}" class="btn btn-secondary">← Back to Permissions</a>
        @can('auth.roles.manage-permissions')
            <a href="{{ route('auth.permissions.edit', $permission) }}" class="btn btn-primary">Edit Permission</a>
        @endcan
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Main Permission Info -->
        <div>
            <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <div
                        style="width: 60px; height: 60px; border-radius: 50%; background: #3b82f6; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px; margin-right: 20px;">
                        🔑
                    </div>
                    <div>
                        <h3 style="color: #1e40af; margin: 0;">{{ $permission->name }}</h3>
                        <p style="color: #6b7280; margin: 5px 0 0 0;">{{ $permission->description ?: 'No description' }}</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Group</label>
                        <span class="status-badge"
                            style="background: #3b82f6; color: white;">{{ $permission->group }}</span>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Slug</label>
                        <code
                            style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 14px;">{{ $permission->slug }}</code>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Status</label>
                        @if ($permission->is_active)
                            <span class="status-badge status-active">Active</span>
                        @else
                            <span class="status-badge status-inactive">Inactive</span>
                        @endif
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Created</label>
                        <p style="margin: 0;">{{ $permission->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div>
            <!-- Roles with this permission -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Roles with this Permission</h4>

                @forelse($permission->roles as $role)
                    <div
                        style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding: 10px; background: #f8fafc; border-radius: 8px;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 50%; background: {{ $role->color }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">
                            {{ strtoupper(substr($role->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 500; font-size: 14px;">{{ $role->name }}</div>
                            <div style="color: #6b7280; font-size: 12px;">{{ $role->users->count() }} users</div>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #6b7280; padding: 20px;">No roles have this permission.</p>
                @endforelse
            </div>

            <!-- Quick Actions -->
            <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Quick Actions</h4>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @can('auth.roles.manage-permissions')
                        <a href="{{ route('auth.permissions.edit', $permission) }}" class="btn btn-primary"
                            style="text-align: center;">
                            Edit Permission
                        </a>

                        @if ($permission->roles->count() === 0)
                            <form method="POST" action="{{ route('auth.permissions.destroy', $permission) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="background: #dc2626; color: white; width: 100%;"
                                    onclick="return confirm('Are you sure you want to delete this permission?')">
                                    Delete Permission
                                </button>
                            </form>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
