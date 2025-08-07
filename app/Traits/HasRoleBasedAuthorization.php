<?php

namespace App\Traits;

use App\Models\Auth\User;

trait HasRoleBasedAuthorization
{
    /**
     * Check if user has minimum role level
     */
    protected function hasMinimumRole(User $user, string $requiredRole): bool
    {
        $hierarchy = [
            'user' => 1,
            'manager' => 2,
            'admin' => 3,
        ];

        return ($hierarchy[$user->role] ?? 0) >= ($hierarchy[$requiredRole] ?? 0);
    }

    /**
     * Check if user is admin
     */
    protected function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Check if user is admin or manager
     */
    protected function isAdminOrManager(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Check if user can view basic accounting data
     */
    protected function canViewAccounting(User $user): bool
    {
        return $this->hasMinimumRole($user, 'user');
    }

    /**
     * Check if user can manage accounting data
     */
    protected function canManageAccounting(User $user): bool
    {
        return $this->isAdminOrManager($user);
    }
}
