<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use App\Traits\HasRoleBasedAuthorization;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
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
    public function view(User $user, Payment $payment): bool
    {
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
    public function update(User $user, Payment $payment): bool
    {
        // Admin can edit any payment
        if ($this->isAdmin($user)) {
            return true;
        }

        // Manager can edit payments within 24 hours
        if ($user->role === 'manager') {
            return $payment->created_at->diffInHours(now()) <= 24;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment): bool
    {
        return $user->can('delete-payments');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payment $payment): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payment $payment): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can void/reverse the payment.
     */
    public function void(User $user, Payment $payment): bool
    {
        return $this->isAdmin($user) && $payment->created_at->diffInDays(now()) <= 7;
    }
}
