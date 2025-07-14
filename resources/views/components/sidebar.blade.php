<nav class="sidebar">
    <div class="logo">
        <h1>🚢 LogiFlow</h1>
        <p>Logistics Management System</p>
    </div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('file.index') }}" class="nav-link {{ request()->routeIs('file.*') ? 'active' : '' }}">
                <span class="nav-icon">📁</span>
                File
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('submenu.index') }}" class="nav-link {{ request()->routeIs('submenu.*') ? 'active' : '' }}">
                <span class="nav-icon">🗂️</span>
                Submenu
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('companies.index') }}" class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}">
                <span class="nav-icon">🏢</span>
                Companies
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <span class="nav-icon">👷</span>
                Employee
            </a>
        </li>
        @if(auth()->user()->isAdmin())
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                Users
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a href="{{ route('shipments.index') }}" class="nav-link {{ request()->routeIs('shipments.*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span>
                Shipment
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('accounting.index') }}" class="nav-link {{ request()->routeIs('accounting.*') ? 'active' : '' }}">
                <span class="nav-icon">💰</span>
                Accounting
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                Settings
            </a>
        </li>
    </ul>
</nav>