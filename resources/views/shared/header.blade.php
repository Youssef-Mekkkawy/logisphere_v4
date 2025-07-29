<div class="header">
    <h2 id="page-title">@yield('page-title', 'Dashboard')</h2>
    <div class="user-info">
        {{-- <span>Welcome, {{ auth()->user()->name }}</span> --}}
        <span class="role-badge user">Welcome, {{ auth()->user()->roles->first()->name ?? 'User' }}</span>
        {{-- Enhanced User Info with Your Avatar Component --}}
        <div class="user-info">
            {{-- Your Enhanced Avatar Component --}}
            <div class="user-avatar-wrapper">
                <x-avatar :user="auth()->user()" :size="48" :show-status="true" :clickable="true"
                    tooltip="Click to view profile" class="user-main-avatar" />
            </div>

            <div class="user-details">

                @if (auth()->user()->gender)
                    <span class="user-gender">{{ ucfirst(auth()->user()->gender) }}</span>
                @endif
            </div>

            {{-- Logout Button --}}
            <button type="button" class="btn btn-secondary logout-btn"
                style="margin-left: 10px; padding: 8px 15px; font-size: 12px;" onclick="safeLogout()">
                Logout
            </button>

            <script>
                function safeLogout() {
                    fetch('{{ route('logout') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            window.location.href = '/login';
                        })
                        .catch(error => {
                            // Fallback: redirect anyway
                            window.location.href = '/login';
                        });
                }
            </script>
        </div>
    </div>
</div>
