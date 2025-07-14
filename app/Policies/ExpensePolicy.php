<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use App\Traits\HasRoleBasedAuthorization;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
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
    public function view(User $user, Expense $expense): bool
    {
        // Users can view their own expenses
        if ($user->role === 'user') {
            return $user->id === $expense->employee->user_id ?? false;
        }

        return $this->canViewAccounting($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasMinimumRole($user, 'user');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Expense $expense): bool
    {
        // Can't update approved/rejected expenses
        if (in_array($expense->status, ['approved', 'rejected'])) {
            return $this->isAdmin($user);
        }

        // Users can update their own pending expenses
        if ($user->role === 'user') {
            return $user->id === $expense->employee->user_id ?? false;
        }

        return $this->canManageAccounting($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Expense $expense): bool
    {
        // Users can delete their own pending expenses
        if ($user->role === 'user' && $expense->status === 'pending') {
            return $user->id === $expense->employee->user_id ?? false;
        }

        return $this->isAdminOrManager($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Expense $expense): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Expense $expense): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can approve the expense.
     */
    public function approve(User $user, Expense $expense): bool
    {
        return $user->can('approve-expenses') && $expense->status === 'pending';
    }

    /**
     * Determine whether the user can reject the expense.
     */
    public function reject(User $user, Expense $expense): bool
    {
        return $user->can('approve-expenses') && $expense->status === 'pending';
    }
}
