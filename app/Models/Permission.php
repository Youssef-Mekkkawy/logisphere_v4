<?php

// File: app/Models/Permission.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'group',
        'description',
        'is_active',
        'role_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all roles that have this permission
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Get all users that have this permission through roles
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, Role::class, 'id', 'id', 'id', 'id')
            ->join('role_permissions', 'roles.id', '=', 'role_permissions.role_id')
            ->join('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('role_permissions.permission_id', $this->id);
    }

    /**
     * Scope to filter by group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope to filter active permissions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get permission groups
     */
    public static function getGroups()
    {
        return self::distinct()->pluck('group')->sort();
    }
}
