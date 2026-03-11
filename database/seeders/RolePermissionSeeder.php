<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create permissions
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'description' => 'Permission to manage users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'description' => 'Permission to manage roles'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions', 'description' => 'Permission to manage permissions'],
            ['name' => 'Manage Profile', 'slug' => 'manage-profile', 'description' => 'Permission to manage own profile'],
            ['name' => 'View Users', 'slug' => 'view-users', 'description' => 'Permission to view users list'],
            ['name' => 'Delete Users', 'slug' => 'delete-users', 'description' => 'Permission to delete users'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        $userRole = Role::create(['name' => 'User', 'slug' => 'user']);

        // Assign permissions to admin role
        $adminPermissions = Permission::whereIn('slug', [
            'manage-users', 'manage-roles', 'manage-permissions', 
            'manage-profile', 'view-users', 'delete-users'
        ])->pluck('id');

        $adminRole->permissions()->attach($adminPermissions);

        // Assign permissions to user role
        $userPermissions = Permission::whereIn('slug', [
            'manage-profile'
        ])->pluck('id');

        $userRole->permissions()->attach($userPermissions);

        // Create a default admin user
        $adminUser = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '+1234567890',
            'password' => bcrypt('password123'),
            'role_id' => $adminRole->id,
            'status' => 'active',
        ]);

        // Create a default regular user
        $regularUser = \App\Models\User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'phone' => '+0987654321',
            'password' => bcrypt('password123'),
            'role_id' => $userRole->id,
            'status' => 'active',
        ]);
    }
}
