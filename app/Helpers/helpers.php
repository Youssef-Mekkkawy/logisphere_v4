<?php

// File: app/Helpers/helpers.php (create this file if it doesn't exist)

if (!function_exists('canAccess')) {
    /**
     * Simple permission check function
     * @param string|array $permissions - Permission name(s) to check
     * @param \App\Models\Auth\User|null $user - User to check (defaults to current user)
     * @return bool
     */
    function canAccess($permissions, $user = null)
    {
        $user = $user ?? auth()->user();

        // If no user is logged in, deny access
        if (!$user) {
            return false;
        }

        // Admins can access everything
        if ($user->isAdmin()) {
            return true;
        }

        // Convert single permission to array
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('canAccessAny')) {
    /**
     * Check if user can access any of the given permissions
     * @param array $permissions
     * @param \App\Models\Auth\User|null $user
     * @return bool
     */
    function canAccessAny(array $permissions, $user = null)
    {
        return canAccess($permissions, $user);
    }
}

if (!function_exists('canAccessAll')) {
    /**
     * Check if user can access ALL of the given permissions
     * @param array $permissions
     * @param \App\Models\Auth\User|null $user
     * @return bool
     */
    function canAccessAll(array $permissions, $user = null)
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        foreach ($permissions as $permission) {
            if (!$user->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('isAdmin')) {
    /**
     * Check if current user is admin
     * @param \App\Models\Auth\User|null $user
     * @return bool
     */
    function isAdmin($user = null)
    {
        $user = $user ?? auth()->user();
        return $user && $user->isAdmin();
    }
}

if (!function_exists('showMenuItem')) {
    /**
     * Simple function to determine if a menu item should be shown
     * @param string|array $permissions - Required permissions
     * @param bool $adminOnly - If true, only admins can see this item
     * @return bool
     */
    function showMenuItem($permissions = [], $adminOnly = false)
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // If admin only and user is not admin
        if ($adminOnly && !$user->isAdmin()) {
            return false;
        }

        // If no permissions required, show to all authenticated users
        if (empty($permissions)) {
            return true;
        }

        // Check permissions
        return canAccess($permissions, $user);
    }
}
if (!function_exists('canAccess')) {
    /**
     * Check if current user can access a permission
     */
    function canAccess(string $permission): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();

        // Check if user account is active
        if (!$user->isActive()) {
            return false;
        }

        // Admin can access everything
        if ($user->isAdmin()) {
            return true;
        }

        // Check specific permission
        return $user->hasPermission($permission);
    }
}

if (!function_exists('hasRole')) {
    /**
     * Check if current user has a specific role
     */
    function hasRole(string $role): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->hasRole($role);
    }
}

if (!function_exists('isAdmin')) {
    /**
     * Check if current user is admin
     */
    function isAdmin(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->isAdmin();
    }
}

if (!function_exists('generateEmployeeEmail')) {
    /**
     * Generate employee email based on name
     */
    function generateEmployeeEmail(string $name, string $domain = null): string
    {
        $domain = $domain ?: config('employee.email_domain', 'logistas.com');

        // Clean name and create email
        $emailName = strtolower(str_replace(' ', '.', trim($name)));
        $emailName = preg_replace('/[^a-z0-9.]/', '', $emailName);

        $baseEmail = $emailName . '@' . $domain;

        // Check if email already exists and add number if needed
        $counter = 1;
        $finalEmail = $baseEmail;

        while (
            \App\Models\Management\Employee::where('email', $finalEmail)->exists() ||
            \App\Models\Auth\User::where('email', $finalEmail)->exists()
        ) {
            $finalEmail = $emailName . $counter . '@' . $domain;
            $counter++;
        }

        return $finalEmail;
    }
}
