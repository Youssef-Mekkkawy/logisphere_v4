{{-- File: resources/views/users/show.blade.php (FIXED) --}}
@extends('layouts.app')

@section('title', $user->name . ' - LogiFlow')
@section('page-title', 'User Details')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('auth.users.index') }}" class="btn btn-secondary">← Back to Users</a>
        @if ($user->id !== auth()->id())
            <a href="{{ route('auth.users.edit', $user) }}" class="btn btn-primary">Edit User</a>
        @endif
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Main User Info -->
        <div>
            <div
                style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <div
                        style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(45deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px; margin-right: 20px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 style="color: #1e40af; margin: 0;">{{ $user->name }}</h3>
                        <p style="color: #6b7280; margin: 5px 0 0 0;">{{ '@' . $user->username }}</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <label
                            style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Username</label>
                        <p style="margin: 0; font-family: monospace; font-size: 16px; color: #1e40af;">
                            {{ '@' . $user->username }}
                        </p>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Roles</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                            @forelse($user->roles as $role)
                                <span class="status-badge" style="background: {{ $role->color }}; color: white;">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="status-badge" style="background: #6b7280; color: white;">No Role</span>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Email</label>
                        <p style="margin: 0;">
                            @if ($user->email)
                                <a href="mailto:{{ $user->email }}" style="color: #3b82f6;">{{ $user->email }}</a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Last
                            Login</label>
                        <p style="margin: 0;">
                            {{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never logged in' }}
                        </p>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Member
                            Since</label>
                        <p style="margin: 0;">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- User Permissions -->
            <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <h4 style="color: #1e40af; margin-bottom: 20px;">🔐 User Permissions</h4>

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
                    <p style="text-align: center; color: #6b7280; padding: 40px;">
                        No permissions assigned to this user.
                    </p>
                @endforelse
            </div>
        </div>

        <!-- Sidebar Info -->
        <div>
            <!-- User Stats -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">User Statistics</h4>

                <div style="margin-bottom: 10px;">
                    <small style="color: #6b7280;">User ID</small>
                    <div style="font-family: monospace; font-weight: 600;">#{{ $user->id }}</div>
                </div>

                <div style="margin-bottom: 10px;">
                    <small style="color: #6b7280;">Total Roles</small>
                    <div>{{ $user->roles->count() }}</div>
                </div>

                <div style="margin-bottom: 10px;">
                    <small style="color: #6b7280;">Total Permissions</small>
                    <div>{{ $user->getAllPermissions()->count() }}</div>
                </div>

                <div style="margin-bottom: 10px;">
                    <small style="color: #6b7280;">Created</small>
                    <div>{{ $user->created_at->format('M d, Y H:i') }}</div>
                </div>

                <div style="margin-bottom: 10px;">
                    <small style="color: #6b7280;">Last Updated</small>
                    <div>{{ $user->updated_at->format('M d, Y H:i') }}</div>
                </div>

                <div style="margin-bottom: 10px;">
                    <small style="color: #6b7280;">Last Login</small>
                    <div>{{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }}</div>
                </div>
            </div>

            <!-- User Roles Details -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Assigned Roles</h4>

                @forelse($user->roles as $role)
                    <div
                        style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding: 10px; background: #f8fafc; border-radius: 8px;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 50%; background: {{ $role->color }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">
                            {{ strtoupper(substr($role->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 500; font-size: 14px;">{{ $role->name }}</div>
                            <div style="color: #6b7280; font-size: 12px;">{{ $role->permissions->count() }} permissions
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #6b7280; padding: 20px;">
                        No roles assigned to this user.
                    </p>
                @endforelse
            </div>

            <!-- Quick Actions -->
            <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Quick Actions</h4>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @if ($user->id !== auth()->id())
                        <a href="{{ route('auth.users.edit', $user) }}" class="btn btn-primary" style="text-align: center;">
                            Edit User
                        </a>
                    @endif

                    @if ($user->email)
                        <a href="mailto:{{ $user->email }}" class="btn btn-secondary" style="text-align: center;">
                            Send Email
                        </a>
                    @endif

                    @if ($user->id === auth()->id())
                        <a href="{{ route('settings.index') }}" class="btn btn-secondary" style="text-align: center;">
                            Account Settings
                        </a>
                    @endif

                    @if ($user->id !== auth()->id())
                        <form method="POST" action="{{ route('auth.users.destroy', $user) }}" style="margin-top: 10px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background: #dc2626; color: white; width: 100%;"
                                onclick="return confirm('Are you sure you want to delete this user?')">
                                Delete User
                            </button>
                        </form>
                    @else
                        <div style="text-align: center; color: #6b7280; font-size: 14px; margin-top: 10px;">
                            You cannot delete your own account
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
