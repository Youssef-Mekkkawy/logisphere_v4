@extends('layouts.app')

@section('title', 'Users Management - LogiFlow')
@section('page-title', 'Users Management (Ctrl+U)')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">👥 Users Management</h2>
            <p style="color: #64748b;">Manage login accounts and permissions</p>
        </div>
        @can('users.create')
            <a href="{{ route('users.create') }}" class="btn btn-primary">+ Add New User</a>
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
                    placeholder="Name, username, email...">
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-input">
                    <option value="">All Roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary" style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div
                                style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @forelse($user->roles as $role)
                                <span class="status-badge"
                                    style="background: {{ $role->color }}; color: white; margin-right: 5px;">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="status-badge" style="background: #6b7280; color: white;">No Role</span>
                            @endforelse
                        </td>
                        <td>
                            @if ($user->last_login)
                                {{ $user->last_login->format('M d, Y H:i') }}
                            @else
                                <span style="color: #64748b;">Never</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>

                            @can('users.edit')
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @endcan

                            @can('users.delete')
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;">
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
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #64748b;">
                            No users found.
                            @can('users.create')
                                <a href="{{ route('users.create') }}" style="color: var(--primary-color);">Create your first
                                    user</a>
                            @endcan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($users->hasPages())
            <div style="margin-top: 1.5rem; padding: 0 20px 20px;">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Summary Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 2rem;">
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">{{ $userStats['total'] }}</div>
            <div style="font-size: 0.875rem; color: #64748b;">Total Users</div>
        </div>
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--success-color);">{{ $userStats['active'] }}
            </div>
            <div style="font-size: 0.875rem; color: #64748b;">Active Users</div>
        </div>
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--warning-color);">{{ $userStats['admins'] }}
            </div>
            <div style="font-size: 0.875rem; color: #64748b;">Administrators</div>
        </div>
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: #8b5cf6;">{{ $userStats['online_today'] }}</div>
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
