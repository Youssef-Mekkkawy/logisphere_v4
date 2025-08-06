{{-- File: resources/views/components/user-status-indicator.blade.php - New component --}}

@props(['user'])

<div style="display: flex; align-items: center; gap: 8px;">
    <span class="status-badge status-{{ $user->isActive() ? 'active' : 'blocked' }}">
        {{ $user->isActive() ? 'Active' : 'Blocked' }}
    </span>

    @if (!$user->isActive())
        <span style="font-size: 12px; color: #dc2626;">
            🚫 Account Blocked
        </span>
    @endif

    @if ($user->mustChangePassword())
        <span style="font-size: 12px; color: #f59e0b;">
            🔑 Password Change Required
        </span>
    @endif
</div>
