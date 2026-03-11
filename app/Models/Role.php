<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * Role belongs to many permissions
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Role has many users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission($permissionSlug)
    {
        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Check if role has any of the given permissions
     */
    public function hasAnyPermission(array $permissionSlugs)
    {
        return $this->permissions()->whereIn('slug', $permissionSlugs)->exists();
    }

    /**
     * Check if role has all of the given permissions
     */
    public function hasAllPermissions(array $permissionSlugs)
    {
        return count($permissionSlugs) === $this->permissions()->whereIn('slug', $permissionSlugs)->count();
    }
}
