<?php

namespace App\Policies;

use App\Models\Management\Company;
use App\Models\Auth\User;
use App\Traits\HasRoleBasedAuthorization;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    use HasRoleBasedAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasMinimumRole($user, 'user');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Company $company): bool
    {
        return $this->hasMinimumRole($user, 'user');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isAdminOrManager($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        return $this->isAdminOrManager($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): bool
    {
        // Can't delete companies with shipments
        if ($company->shipments()->count() > 0) {
            return false;
        }

        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Company $company): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return $this->isAdmin($user);
    }
}
