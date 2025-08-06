<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'last_login',
        'gender',
        'is_active',
        'force_password_change',
        'employee_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'force_password_change' => 'boolean'
    ];

    // ===== RELATIONSHIPS =====

    /**
     * User belongs to an employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * User can have multiple roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    // ===== ACCOUNT STATUS METHODS =====

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Check if user is blocked
     */
    public function isBlocked(): bool
    {
        return $this->is_active === false;
    }

    /**
     * Check if user must change password
     */
    public function mustChangePassword(): bool
    {
        return $this->force_password_change === true;
    }

    /**
     * Block user account
     */
    public function block(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Activate user account
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Force password change on next login
     */
    public function forcePasswordChange(): bool
    {
        return $this->update(['force_password_change' => true]);
    }

    /**
     * Remove force password change requirement
     */
    public function clearForcePasswordChange(): bool
    {
        return $this->update(['force_password_change' => false]);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): bool
    {
        return $this->update(['last_login' => now()]);
    }

    // ===== PERMISSION METHODS =====

    /**
     * Get all permissions through roles
     */
    public function getAllPermissions()
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->load('permissions')->pluck('permissions')->flatten()->unique('id');
        }

        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->unique('id');
    }

    /**
     * Get permissions as a relationship-like method
     */
    public function permissions()
    {
        return $this->getAllPermissions();
    }

    // ===== RBAC HELPER METHODS =====

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles()->where('slug', $role)->exists();
        }
        return $this->roles()->where('id', $role)->exists();
    }

    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        // Check if account is active first
        if (!$this->isActive()) {
            return false;
        }

        // Get all permissions through roles
        $permissions = $this->getAllPermissions();

        // Check by slug
        return $permissions->where('slug', $permission)->isNotEmpty();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        if (!$this->isActive()) {
            return false;
        }

        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        $userPermissions = $this->getAllPermissions();

        foreach ($permissions as $permission) {
            if ($userPermissions->where('slug', $permission)->isNotEmpty()) {
                return true;
            }
        }

        return false;
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        if ($role && !$this->hasRole($role->slug)) {
            $this->roles()->attach($role);
        }
    }

    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role);
        }
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isManager()
    {
        return $this->hasRole('manager');
    }

    public function getPrimaryRole()
    {
        return $this->roles()->first();
    }

    public function getRoleNames()
    {
        return $this->roles()->pluck('name')->toArray();
    }

    public function getRoleSlugs()
    {
        return $this->roles()->pluck('slug')->toArray();
    }

    /**
     * Enhanced can method with account status check
     */
    public function can($ability, $arguments = [])
    {
        // Check if account is active
        if (!$this->isActive()) {
            return false;
        }

        // Check if it's a permission slug
        if ($this->hasPermission($ability)) {
            return true;
        }

        // For admin users, allow everything by default (if active)
        if ($this->isAdmin()) {
            return true;
        }

        // Fall back to parent implementation
        return parent::can($ability, $arguments);
    }

    // ===== SCOPES =====

    /**
     * Scope to get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only blocked users
     */
    public function scopeBlocked($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope to get users that must change password
     */
    public function scopeMustChangePassword($query)
    {
        return $query->where('force_password_change', true);
    }

    /**
     * Scope to get users with employee relationship
     */
    public function scopeWithEmployee($query)
    {
        return $query->whereNotNull('employee_id');
    }

    // ===== ACCESSORS =====

    /**
     * Get user status badge color
     */
    public function getStatusColorAttribute()
    {
        return $this->is_active ? '#10b981' : '#ef4444';
    }

    /**
     * Get user status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Active' : 'Blocked';
    }

    /**
     * Get full user info for display
     */
    public function getDisplayInfoAttribute()
    {
        $info = $this->name . ' (' . $this->email . ')';

        if ($this->employee) {
            $info .= ' - ' . $this->employee->employee_id;
        }

        if (!$this->is_active) {
            $info .= ' [BLOCKED]';
        }

        return $info;
    }
}
