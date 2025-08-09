<?php

namespace App\Policies;


use App\Models\Auth\User;
use App\Models\Management\Account\Invoice;
use App\Traits\HasRoleBasedAuthorization;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
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
    public function view(User $user, Invoice $invoice): bool
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
    public function update(User $user, Invoice $invoice): bool
    {
        // Admin can edit any invoice
        if ($this->isAdmin($user)) {
            return true;
        }

        // Manager can edit unpaid/draft invoices
        if ($user->role === 'manager') {
            return in_array($invoice->status, ['draft', 'pending', 'overdue']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        // Use the gate defined in AppServiceProvider
        return $user->can('delete-invoices') && $invoice->status === 'draft';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Invoice $invoice): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Invoice $invoice): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can send the invoice to client.
     */
    public function send(User $user, Invoice $invoice): bool
    {
        return $this->canManageAccounting($user) && $invoice->status !== 'cancelled';
    }

    /**
     * Determine whether the user can generate PDF.
     */
    public function generatePdf(User $user, Invoice $invoice): bool
    {
        return $this->canViewAccounting($user);
    }

    /**
     * Determine whether the user can mark as paid.
     */
    public function markPaid(User $user, Invoice $invoice): bool
    {
        return $this->canManageAccounting($user) && $invoice->status !== 'paid';
    }
}
