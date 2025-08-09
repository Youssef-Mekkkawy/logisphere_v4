{{-- File: resources/views/shared/sidebar.blade.php --}}
<nav class="sidebar">
    <div class="logo">
        <h1>🚢 logisphere</h1>
        <p>Logistics Management System</p>
    </div>
    <ul class="nav-menu">
        {{-- Dashboard - Show to everyone --}}
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                Dashboard
            </a>
        </li>

        {{-- File - Check permission --}}
        @if (canAccess('tools.file.view'))
            <li class="nav-item">
                <a href="{{ route('tools.file.index') }}"
                    class="nav-link {{ request()->routeIs('tools.file.*') ? 'active' : '' }}">
                    <span class="nav-icon">📁</span>
                    File
                </a>
            </li>
        @endif

        {{-- Logistics - Check permission --}}
        @if (canAccess('logistics.view'))
            <li class="nav-item">
                <a href="{{ route('logistics.index') }}"
                    class="nav-link {{ request()->routeIs('logistics.*') ? 'active' : '' }}">
                    <span class="nav-icon">🗂️</span>
                    Logistics
                </a>
            </li>
        @endif

        {{-- Companies - Check permission --}}
        @if (canAccess('companies.view'))
            <li class="nav-item">
                <a href="{{ route('management.companies.index') }}"
                    class="nav-link {{ request()->routeIs('management.companies.*') ? 'active' : '' }}">
                    <span class="nav-icon">🏢</span>
                    Companies
                </a>
            </li>
        @endif

        {{-- Employee - Check permission --}}
        @if (canAccess('employees.view'))
            <li class="nav-item">
                <a href="{{ route('management.employees.index') }}"
                    class="nav-link {{ request()->routeIs('management.employees.*') ? 'active' : '' }}">
                    <span class="nav-icon">👷</span>
                    Employee
                </a>
            </li>
        @endif

        {{-- Users - Admin only --}}
        @if (auth()->check() && auth()->user()->isAdmin())
            <li class="nav-item">
                <a href="{{ route('auth.users.index') }}"
                    class="nav-link {{ request()->routeIs('auth.users.*') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span>
                    Users
                </a>
            </li>
        @endif

        {{-- Shipment - Check permission --}}
        @if (canAccess('shipments.view'))
            <li class="nav-item">
                <a href="{{ route('management.shipments.index') }}"
                    class="nav-link {{ request()->routeIs('management.shipments.*') ? 'active' : '' }}">
                    <span class="nav-icon">📦</span>
                    Shipment
                </a>
            </li>
        @endif

        {{-- Accounting - Check permission --}}
        @if (canAccess('accounting.view'))
            <li class="nav-item">
                {{-- {{ route('management.accounting.index') }} --}}
                <a href="{{ route('management.account.index') }}"
                    class="nav-link {{ request()->routeIs('management.account.*') ? 'active' : '' }}">
                    <span class="nav-icon">💰</span>
                    Accounting
                </a>
            </li>
        @endif

        {{-- Settings - Show to everyone --}}
        <li class="nav-item">
            <a href="" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                Settings
            </a>
        </li>
    </ul>

    {{-- Enhanced User Info with Your Avatar Component --}}
    {{-- <div class="user-info">
       
        <div class="user-avatar-wrapper">
            <x-avatar :user="auth()->user()" :size="48" :show-status="true" :clickable="true"
                tooltip="Click to view profile" class="user-main-avatar" />
        </div>

        <div class="user-details">
            <span class="user-name">{{ auth()->user()->name }}</span>
            <span class="user-role">
                @if (auth()->user()->isAdmin())
                    <span class="role-badge admin">Administrator</span>
                @else
                    <span class="role-badge user">{{ auth()->user()->roles->first()->name ?? 'User' }}</span>
                @endif
            </span>
            @if (auth()->user()->gender)
                <span class="user-gender">{{ ucfirst(auth()->user()->gender) }}</span>
            @endif
        </div>

      
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn" title="Logout">
                🚪
            </button>
        </form>
    </div> --}}
</nav>

{{-- <style>
    /* Keep your existing sidebar styles and add/update these: */

    /* Enhanced User Info */
    .user-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px;
        background: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        gap: 12px;
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .user-main-avatar {
        transition: all 0.3s ease;
    }

    .user-details {
        flex: 1;
        min-width: 0;
        overflow: hidden;
    }

    .user-name {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: white;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        display: block;
        margin-bottom: 2px;
    }

    .role-badge {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .role-badge.admin {
        background: linear-gradient(135deg, #ffd700, #ffa500);
        color: #1a1a1a;
        box-shadow: 0 2px 4px rgba(255, 215, 0, 0.3);
    }

    .role-badge.user {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .user-gender {
        display: block;
        font-size: 10px;
        opacity: 0.7;
        color: rgba(255, 255, 255, 0.8);
        margin-top: 2px;
    }

    .logout-btn {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: rgba(255, 255, 255, 0.7);
        font-size: 16px;
        padding: 8px 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .logout-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        transform: scale(1.05);
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .user-info {
            padding: 15px;
            gap: 8px;
        }

        .user-name {
            font-size: 13px;
        }

        .role-badge {
            font-size: 10px;
            padding: 1px 6px;
        }

        .user-gender {
            font-size: 9px;
        }
    }

    /* Hover effects for avatar */
    .user-main-avatar:hover {
        transform: scale(1.1);
    }

    /* Add a subtle glow effect for admin users */
    @if (auth()->check() && auth()->user()->isAdmin())
        .user-avatar-wrapper::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #ffd700, #ffa500, #ffd700);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.7;
            animation: adminGlow 2s ease-in-out infinite alternate;
        }

        @keyframes adminGlow {
            from {
                opacity: 0.7;
                transform: scale(1);
            }

            to {
                opacity: 0.9;
                transform: scale(1.02);
            }
        }
    @endif
</style> --}}
