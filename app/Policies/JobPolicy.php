<?php

namespace App\Policies;

use App\Models\JobAssignment;
use App\Models\User;
use App\Traits\HasRoleBasedAuthorization;
use Illuminate\Auth\Access\Response;

class JobPolicy
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
    public function view(User $user, JobAssignment $job): bool
    {
        // Users can view their own job assignments
        if ($user->role === 'user') {
            return $user->id === $job->employee->user_id ?? false;
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
    public function update(User $user, JobAssignment $job): bool
    {
        // Can't update completed/billed jobs
        if (in_array($job->status, ['completed', 'billed'])) {
            return $this->isAdmin($user);
        }

        return $this->canManageAccounting($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobAssignment $job): bool
    {
        return $this->isAdminOrManager($user) && $job->status === 'pending';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JobAssignment $job): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JobAssignment $job): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can mark job as complete.
     */
    public function markComplete(User $user, JobAssignment $job): bool
    {
        return $this->canManageAccounting($user) && $job->status === 'in_progress';
    }

    /**
     * Determine whether the user can mark job as billable.
     */
    public function markBillable(User $user, JobAssignment $job): bool
    {
        return $this->canManageAccounting($user) &&
            $job->status === 'completed' &&
            !$job->is_billable;
    }

    /**
     * Determine whether the user can update time/hours.
     */
    public function updateTime(User $user, JobAssignment $job): bool
    {
        // Employee can update their own time if job is in progress
        if ($user->id === $job->employee->user_id ?? false) {
            return $job->status === 'in_progress';
        }

        return $this->canManageAccounting($user);
    }
}
