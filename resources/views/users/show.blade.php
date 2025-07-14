@extends('layouts.app')

@section('title', $user->name . ' - LogiFlow')
@section('page-title', 'User Details')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back to Users</a>
    @if($user->id !== auth()->id())
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit User</a>
    @endif
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
    <!-- Main User Info -->
    <div>
        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(45deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px; margin-right: 20px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h3 style="color: #1e40af; margin: 0;">{{ $user->name }}</h3>
                    <p style="color: #6b7280; margin: 5px 0 0 0;">@{{ $user->username }}</p>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Username</label>
                    <p style="margin: 0; font-family: monospace; font-size: 16px; color: #1e40af;">@{{ $user->username }}</p>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Role</label>
                    <span class="status-badge status-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Email</label>
                    <p style="margin: 0;">
                        @if($user->email)
                            <a href="mailto:{{ $user->email }}" style="color: #3b82f6;">{{ $user->email }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Account Status</label>
                    <span class="status-badge status-active">Active</span>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Last Login</label>
                    <p style="margin: 0;">{{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never logged in' }}</p>
                </div>
                
                <div>
                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Member Since</label>
                    <p style="margin: 0;">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Info -->
    <div>
        <!-- Role Permissions -->
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Role Permissions</h4>
            
            @if($user->role === 'admin')
                <div style="color: #dc2626;">
                    <strong>Administrator</strong>
                    <ul style="margin: 10px 0; padding-left: 20px; font-size: 14px;">
                        <li>Full system access</li>
                        <li>User management</li>
                        <li>System settings</li>
                        <li>All CRUD operations</li>
                        <li>Delete permissions</li>
                    </ul>
                </div>
            @elseif($user->role === 'manager')
                <div style="color: #d97706;">
                    <strong>Manager</strong>
                    <ul style="margin: 10px 0; padding-left: 20px; font-size: 14px;">
                        <li>Manage shipments</li>
                        <li>Manage companies</li>
                        <li>Manage employees</li>
                        <li>View reports</li>
                        <li>Accounting access</li>
                    </ul>
                </div>
            @else
                <div style="color: #059669;">
                    <strong>User</strong>
                    <ul style="margin: 10px 0; padding-left: 20px; font-size: 14px;">
                        <li>View shipments</li>
                        <li>View companies</li>
                        <li>View employees</li>
                        <li>Basic operations</li>
                        <li>No admin access</li>
                    </ul>
                </div>
            @endif
        </div>
        
        <!-- Account Details -->
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Account Details</h4>
            
            <div style="margin-bottom: 10px;">
                <small style="color: #6b7280;">User ID</small>
                <div style="font-family: monospace; font-weight: 600;">#{{ $user->id }}</div>
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
        
        <!-- Quick Actions -->
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Quick Actions</h4>
            
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @if($user->id !== auth()->id())
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary" style="text-align: center;">
                        Edit User
                    </a>
                @endif
                
                @if($user->email)
                <a href="mailto:{{ $user->email }}" class="btn btn-secondary" style="text-align: center;">
                    Send Email
                </a>
                @endif
                
                @if($user->id === auth()->id())
                <a href="{{ route('settings') }}" class="btn btn-secondary" style="text-align: center;">
                    Account Settings
                </a>
                @endif
                
                @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('users.destroy', $user) }}" style="margin-top: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background: #dc2626; color: white; width: 100%;"
                            onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
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