{{-- File: resources/views/components/avatar.blade.php --}}

@php
    $isOnline = $showStatus ? $isUserOnline() : false;
    $userName = $user->name ?? 'Guest';

    $containerClasses = [
        'avatar-container',
        $class,
        $clickable ? 'avatar-clickable' : '',
        $showStatus ? 'avatar-with-status' : '',
    ];
@endphp

<div class="{{ implode(' ', array_filter($containerClasses)) }}"
    style="width: {{ $size }}px; height: {{ $size }}px; position: relative; display: inline-block;"
    @if ($tooltip) title="{{ $tooltip }}" @endif
    @if ($clickable) role="button" tabindex="0" onclick="window.location.href='{{ route('profile.show') }}'" @endif>

    <!-- Avatar Image -->
    <img src="{{ $avatarUrl }}" alt="{{ $userName }}'s avatar" class="avatar-img"
        style="width: {{ $size }}px; height: {{ $size }}px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($userName) }}&size={{ $size }}&background=3b82f6&color=ffffff'">

    <!-- Online Status Indicator -->
    @if ($showStatus)
        <div class="avatar-status {{ $isOnline ? 'online' : 'offline' }}"
            style="position: absolute; bottom: 0; right: 0; width: {{ max(8, $size * 0.25) }}px; height: {{ max(8, $size * 0.25) }}px; border: 2px solid #fff; border-radius: 50%; background: {{ $isOnline ? '#10b981' : '#6b7280' }};">
        </div>
    @endif

    <!-- Role Badge (optional, for larger avatars) -->
    @if ($size >= 64 && $user)
        <div class="avatar-role-badge"
            style="position: absolute; top: -5px; right: -5px; background: {{ $user->isAdmin() ? '#ffd700' : '#3b82f6' }}; color: {{ $user->isAdmin() ? '#000' : '#fff' }}; font-size: 10px; padding: 2px 6px; border-radius: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">
            {{ $user->isAdmin() ? 'Admin' : $user->roles->first()->name ?? 'User' }}
        </div>
    @endif
</div>

<style>
    /* Avatar Container Styles */
    .avatar-container {
        transition: all 0.3s ease;
    }

    .avatar-clickable {
        cursor: pointer;
    }

    .avatar-clickable:hover {
        transform: scale(1.05);
    }

    .avatar-clickable:hover .avatar-img {
        border-color: rgba(255, 255, 255, 0.6);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .avatar-img {
        display: block;
    }

    /* Status Indicator Animation */
    .avatar-status.online {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    /* Role Badge Animation */
    .avatar-role-badge {
        animation: fadeInScale 0.5s ease-out;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.5);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive Sizes */
    @media (max-width: 768px) {
        .avatar-role-badge {
            font-size: 8px !important;
            padding: 1px 4px !important;
        }
    }
</style>
