<?php

// File: app/Models/User.php (Fixed)
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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ===== ROLE RELATIONSHIPS =====

    /**
     * Get all roles assigned to this user
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Get all permissions through roles
     */
    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, Role::class, 'id', 'id', 'id', 'id')
            ->join('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->join('role_permissions', 'roles.id', '=', 'role_permissions.role_id')
            ->where('user_roles.user_id', $this->id)
            ->distinct();
    }

    // ===== ROLE METHODS =====

    /**
     * Check if user has specific role
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles()->where('roles.slug', $role)->exists();
        }

        return $this->roles()->where('roles.id', $role->id)->exists();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('roles.slug', $roles)->exists();
    }

    /**
     * Check if user has all of the given roles
     */
    public function hasAllRoles(array $roles): bool
    {
        return $this->roles()->whereIn('roles.slug', $roles)->count() === count($roles);
    }

    /**
     * Assign role to user
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        if ($role && !$this->hasRole($role)) {
            $this->roles()->attach($role->id);
        }

        return $this;
    }

    /**
     * Remove role from user
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }

        return $this;
    }

    /**
     * Sync roles for this user
     */
    public function syncRoles($roles)
    {
        if (is_array($roles)) {
            $roleIds = Role::whereIn('slug', $roles)->pluck('id');
        } else {
            $roleIds = $roles;
        }

        $this->roles()->sync($roleIds);

        return $this;
    }

    // ===== PERMISSION METHODS =====

    /**
     * Check if user has specific permission
     */
    public function hasPermission($permission): bool
    {
        if (is_string($permission)) {
            return $this->getAllPermissions()->contains('slug', $permission);
        }

        return $this->getAllPermissions()->contains('id', $permission->id);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        $userPermissions = $this->getAllPermissions()->pluck('slug')->toArray();

        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        $userPermissions = $this->getAllPermissions()->pluck('slug')->toArray();

        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all permissions for this user (from all roles)
     */
    public function getAllPermissions()
    {
        return Permission::whereIn('id', function ($query) {
            $query->select('permission_id')
                ->from('role_permissions')
                ->whereIn('role_id', function ($subQuery) {
                    $subQuery->select('role_id')
                        ->from('user_roles')
                        ->where('user_id', $this->id);
                });
        })->get();
    }

    /**
     * Laravel's built-in can() method for Gate authorization
     */
    public function can($ability, $arguments = [])
    {
        return $this->hasPermission($ability) || parent::can($ability, $arguments);
    }

    // ===== HELPER METHODS =====

    /**
     * Check if user is admin (has admin role)
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is manager (has manager role)
     */
    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    /**
     * Check if user is regular user (has user role)
     */
    public function isUser(): bool
    {
        return $this->hasRole('user');
    }

    /**
     * Get user's primary role (first role)
     */
    public function getPrimaryRole()
    {
        return $this->roles()->first();
    }

    /**
     * Get all role names
     */
    public function getRoleNames()
    {
        return $this->roles()->pluck('name')->toArray();
    }

    /**
     * Get all permission names
     */
    public function getPermissionNames()
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    /**
     * Get role badge HTML
     */
    public function getRoleBadge(): string
    {
        $role = $this->getPrimaryRole();
        if (!$role) {
            return '<span class="status-badge">No Role</span>';
        }

        return $role->badge;
    }

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

        return substr($initials, 0, 2);
    }

    /**
     * Check if user has logged in recently
     */
    public function hasLoggedInRecently($days = 7): bool
    {
        if (!$this->last_login) {
            return false;
        }

        return $this->last_login->gte(now()->subDays($days));
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
}
