@extends('layouts.app')

@section('title', 'Users Management')
@section('page_title', 'Users Management (Ctrl+U)')
@section('breadcrumb', 'Home > Users')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">👥 Users Management</h2>
            <p style="color: #64748b;">Manage login accounts and permissions</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">+ Add New User</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}"
                placeholder="Name, username, email..." />
            <x-form-select name="role" label="Role" :options="[
                'admin' => 'Administrator',
                'manager' => 'Manager',
                'user' => 'User',
            ]" value="{{ request('role') }}" />
            <x-form-select name="is_active" label="Status" :options="[
                '1' => 'Active',
                '0' => 'Inactive',
            ]" value="{{ request('is_active') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary" style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Users Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Last Login</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div
                                style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        </td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="status-badge status-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                        <td>
                            @if ($user->last_login)
                                {{ $user->last_login->format('M d, Y H:i') }}
                            @else
                                <span style="color: #64748b;">Never</span>
                            @endif
                        </td>
                        <td>
                            @if ($user->is_active)
                                <span class="status-badge status-active">Active</span>
                            @else
                                <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin() && $user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;"
                                    onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: #64748b;">
                            No users found. <a href="{{ route('users.create') }}"
                                style="color: var(--primary-color);">Create your first user</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($users->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $users->links() }}
            </div>
        @endif
    </x-card>

    <!-- Summary Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 2rem;">
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">{{ $userStats['total'] ?? 0 }}
            </div>
            <div style="font-size: 0.875rem; color: #64748b;">Total Users</div>
        </div>
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--success-color);">{{ $userStats['active'] ?? 0 }}
            </div>
            <div style="font-size: 0.875rem; color: #64748b;">Active Users</div>
        </div>
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--warning-color);">{{ $userStats['admins'] ?? 0 }}
            </div>
            <div style="font-size: 0.875rem; color: #64748b;">Administrators</div>
        </div>
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: #8b5cf6;">{{ $userStats['online_today'] ?? 0 }}</div>
            <div style="font-size: 0.875rem; color: #64748b;">Online Today</div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Ctrl+U keyboard shortcut for users
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'u') {
                e.preventDefault();
                // Already on users page, focus on search
                document.querySelector('input[name="search"]')?.focus();
            }
        });
    </script>
@endsection
