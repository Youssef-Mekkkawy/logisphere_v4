<div class="dropdown">
    <button class="btn dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
        <x-avatar :user="auth()->user()" :size="32" class="me-2" />
        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        <li class="dropdown-header">
            <div class="d-flex align-items-center">
                <x-avatar :user="auth()->user()" :size="40" :show-status="true" class="me-3" />
                <div>
                    <strong>{{ auth()->user()->name }}</strong>
                    <br>
                    <small class="text-muted">
                        @if (auth()->user()->isAdmin())
                            Administrator
                        @else
                            {{ auth()->user()->roles->first()->name ?? 'User' }}
                        @endif
                    </small>
                </div>
            </div>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="{{ route('profile.show') }}">👤 Profile</a></li>
        <li><a class="dropdown-item" href="{{ route('settings.index') }}">⚙️ Settings</a></li>
        @if (isAdmin())
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="{{ route('auth.users.index') }}">👥 Manage Users</a></li>
        @endif
        <li>
            <hr class="dropdown-divider">
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                🚪 Logout
            </a>
        </li>
    </ul>
</div>
