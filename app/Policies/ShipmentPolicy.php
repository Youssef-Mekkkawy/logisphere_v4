<?php

namespace App\Policies;

use App\Models\Management\Shipment;
use App\Models\Auth\User;
use App\Traits\HasRoleBasedAuthorization;
use Illuminate\Auth\Access\Response;

class ShipmentPolicy
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
    public function view(User $user, Shipment $shipment): bool
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
    public function update(User $user, Shipment $shipment): bool
    {
        // Can't update delivered/cancelled shipments
        if (in_array($shipment->status, ['Delivered', 'Cancelled'])) {
            return $this->isAdmin($user);
        }

        return $this->isAdminOrManager($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shipment $shipment): bool
    {
        return $this->isAdmin($user) && $shipment->status === 'Pending';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Shipment $shipment): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Shipment $shipment): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can track the shipment.
     */
    public function track(User $user, Shipment $shipment): bool
    {
        return $this->hasMinimumRole($user, 'user');
    }

    /**
     * Determine whether the user can update shipment status.
     */
    public function updateStatus(User $user, Shipment $shipment): bool
    {
        return $this->isAdminOrManager($user);
    }
}
 