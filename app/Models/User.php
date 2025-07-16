<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ===== ROLE METHODS =====

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is manager
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get role badge HTML
     */
    public function getRoleBadge(): string
    {
        $badges = [
            'admin' => '<span class="status-badge" style="background: #dc2626; color: white;">Admin</span>',
            'manager' => '<span class="status-badge" style="background: #d97706; color: white;">Manager</span>',
            'user' => '<span class="status-badge" style="background: #059669; color: white;">User</span>',
        ];

        return $badges[$this->role] ?? '<span class="status-badge">Unknown</span>';
    }

    // ===== SCOPES =====

    /**
     * Scope a query to only include users of a given role.
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope a query to only include active users (logged in recently).
     */
    public function scopeActive($query, $days = 30)
    {
        return $query->where('last_login', '>=', now()->subDays($days));
    }

    // ===== RELATIONSHIPS =====

    /**
     * Get the employees for the user (if applicable).
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'manager_id', 'username');
    }

    /**
     * Get created shipments by this user.
     */
    public function createdShipments()
    {
        return $this->hasMany(Shipment::class, 'created_by');
    }

    // ===== HELPER METHODS =====

    /**
     * Get the user's display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ' (' . $this->username . ')';
    }

    /**
     * Get the user's initials
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';

        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }

        return substr($initials, 0, 2); // Max 2 initials
    }

    /**
     * Check if user has logged in recently
     */
    public function hasLoggedInRecently($days = 7): bool
    {
        if (!$this->last_login) {
            return false;
        }

        return $this->last_login->get(now()->subDays($days));
    }

    /**
     * Get the time since last login
     */
    public function getLastLoginHumanAttribute(): string
    {
        if (!$this->last_login) {
            return 'Never';
        }

        return $this->last_login->diffForHumans();
    }
}
