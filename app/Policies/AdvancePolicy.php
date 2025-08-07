<?php

namespace App\Policies;

use App\Models\EmployeeAdvance;
use App\Models\Auth\User;
use App\Traits\HasRoleBasedAuthorization;
use Illuminate\Auth\Access\Response;

class AdvancePolicy
{
    use HasRoleBasedAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->canViewAccounting($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EmployeeAdvance $advance): bool
    {
        // Users can view their own advances
        if ($user->role === 'user') {
            return $user->id === $advance->employee->user_id ?? false;
        }

        return $this->canViewAccounting($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->canManageAccounting($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EmployeeAdvance $advance): bool
    {
        // Only admin can update approved advances
        if ($advance->status === 'approved') {
            return $this->isAdmin($user);
        }

        return $this->canManageAccounting($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EmployeeAdvance $advance): bool
    {
        return $user->can('delete-advances') && $advance->status === 'pending';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EmployeeAdvance $advance): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EmployeeAdvance $advance): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can approve the advance.
     */
    public function approve(User $user, EmployeeAdvance $advance): bool
    {
        return $this->canManageAccounting($user) && $advance->status === 'pending';
    }

    /**
     * Determine whether the user can write off the advance.
     */
    public function writeOff(User $user, EmployeeAdvance $advance): bool
    {
        return $user->can('write-off-advances') &&
            in_array($advance->status, ['approved', 'partial']);
    }

    /**
     * Determine whether the user can record repayment.
     */
    public function recordRepayment(User $user, EmployeeAdvance $advance): bool
    {
        return $this->canManageAccounting($user) &&
            in_array($advance->status, ['approved', 'partial']);
    }
}
