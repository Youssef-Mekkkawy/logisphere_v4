{{-- File: resources/views/shared/sidebar.blade.php --}}
<nav class="sidebar">
    <div class="logo">
        <h1>🚢 LogiFlow</h1>
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

        {{-- Users - Admin only (keep your existing check or use isAdmin()) --}}
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
                <a href="{{ route('management.accounting.index') }}"
                    class="nav-link {{ request()->routeIs('management.accounting.*') ? 'active' : '' }}">
                    <span class="nav-icon">💰</span>
                    Accounting
                </a>
            </li>
        @endif

        {{-- Settings - Show to everyone --}}
        <li class="nav-item">
            {{-- {{ route('Shared.settings.index') }} --}}
            <a href="" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                Settings
            </a>
        </li>
    </ul>
</nav>
