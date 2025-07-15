<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property string $role
 * @method static \Illuminate\Database\Eloquent\Builder|User role($role)
 * @method static \Illuminate\Database\Eloquent\Builder|User admins()
 * @method static \Illuminate\Database\Eloquent\Builder|User managers()
 * @method static \Illuminate\Database\Eloquent\Builder|User regularUsers()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    // Scopes
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeManagers($query)
    {
        return $query->where('role', 'manager');
    }

    public function scopeRegularUsers($query)
    {
        return $query->where('role', 'user');
    }

    public function updateLastLogin()
    {
        $this->update(['last_login' => now()]);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get bookings created by this user
     */
    public function createdBookings()
    {
        return $this->hasMany(Booking::class, 'created_by');
    }

    /**
     * Get bookings confirmed by this user
     */
    public function confirmedBookings()
    {
        return $this->hasMany(Booking::class, 'confirmed_by');
    }

    /**
     * Get invoices created by this user
     */
    public function createdInvoices()
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    /**
     * Get invoices approved by this user
     */
    public function approvedInvoices()
    {
        return $this->hasMany(Invoice::class, 'approved_by');
    }

    /**
     * Get attachments uploaded by this user
     */
    public function uploadedAttachments()
    {
        return $this->morphMany(Attachment::class, 'uploaded_by');
    }
}
