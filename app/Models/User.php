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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'password' => 'hashed',
    ];

    // ===== RBAC RELATIONSHIPS =====

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Get all permissions through roles - FIXED VERSION
     */
    public function getAllPermissions()
    {
        return $this->roles->load('permissions')->pluck('permissions')->flatten()->unique('id');
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
     * 🔥 FIXED: Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        // Get all permissions through roles
        $permissions = $this->getAllPermissions();

        // 🔥 BUG WAS HERE: was checking 'role-slug' instead of 'slug'
        return $permissions->where('slug', $permission)->isNotEmpty();
    }

    /**
     * 🔥 FIXED: Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
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
}
