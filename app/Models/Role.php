<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
        'is_system',
        'role_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    /**
     * Get all permissions assigned to this role
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Get all users that have this role
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    /**
     * Check if role has specific permission
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->where('slug', $permission)->exists();
        }

        return $this->permissions()->where('id', $permission->id)->exists();
    }

    /**
     * Assign permission to role
     */
    public function givePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if ($permission && !$this->hasPermission($permission)) {
            $this->permissions()->attach($permission);
        }

        return $this;
    }

    /**
     * Remove permission from role
     */
    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission);
        }

        return $this;
    }

    /**
     * Sync permissions for this role
     */
    public function syncPermissions($permissions)
    {
        if (is_array($permissions)) {
            $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id');
        } else {
            $permissionIds = $permissions;
        }

        $this->permissions()->sync($permissionIds);

        return $this;
    }

    /**
     * Get role badge HTML
     */
    public function getBadgeAttribute()
    {
        return '<span class="status-badge" style="background: ' . $this->color . '; color: white;">' . $this->name . '</span>';
    }

    /**
     * Scope to filter active roles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter non-system roles
     */
    public function scopeNonSystem($query)
    {
        return $query->where('is_system', false);
    }
}
