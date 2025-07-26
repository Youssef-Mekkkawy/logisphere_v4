<div class="header">
    <h2 id="page-title">@yield('page-title', 'Dashboard')</h2>
    <div class="user-info">
        <span>Welcome, {{ auth()->user()->name }}</span>
        <div class="user-avatar">{{ strtoupper(substr(auth()->check() && auth()->user()->name, 0, 1)) }}</div>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-secondary"
                style="margin-left: 10px; padding: 8px 15px; font-size: 12px;">
                Logout
            </button>
        </form>
    </div>
</div>
