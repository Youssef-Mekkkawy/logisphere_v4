{{-- File: resources/views/components/avatar.blade.php --}}

@props(['user', 'size' => 40, 'showStatus' => false, 'clickable' => false, 'tooltip' => null, 'class' => ''])

@php
    $initials = strtoupper(substr($user->name ?? 'U', 0, 1));
    $colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4', '#84cc16', '#f97316'];
    $colorIndex = crc32($user->email ?? ($user->name ?? 'default')) % count($colors);
    $bgColor = $colors[$colorIndex];

    $containerClass = $class . ' relative inline-block';
    if ($clickable) {
        $containerClass .= ' cursor-pointer hover:opacity-80 transition-opacity';
    }
@endphp

<div class="{{ $containerClass }}" @if ($tooltip) title="{{ $tooltip }}" @endif
    @if ($clickable) onclick="window.location.href='{{ route('auth.users.show', $user) }}'" @endif>
    <div
        style="
            width: {{ $size }}px; 
            height: {{ $size }}px; 
            background: {{ $bgColor }}; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-weight: bold;
            font-size: {{ $size * 0.4 }}px;
        ">
        {{ $initials }}
    </div>

    @if ($showStatus && isset($user->last_login))
        <div class="absolute -bottom-1 -right-1 rounded-full border-2 border-white"
            style="
                width: {{ $size * 0.3 }}px; 
                height: {{ $size * 0.3 }}px; 
                background: {{ $user->last_login?->isToday() ? '#10b981' : '#6b7280' }};
            "
            title="{{ $user->last_login?->isToday() ? 'Online today' : 'Offline' }}"></div>
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
