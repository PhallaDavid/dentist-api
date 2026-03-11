<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Get all roles with their permissions
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    /**
     * Create a new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'slug' => 'required|string|max:255|unique:roles',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role
        ], 201);
    }
    public function show($id)
    {
        $role = Role::with('permissions')->find($id);
        
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json($role);
    }

    /**
     * Update a role
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $id,
            'slug' => 'sometimes|required|string|max:255|unique:roles,slug,' . $id,
        ]);

        $role->update($request->only(['name', 'slug']));

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role
        ]);
    }

    /**
     * Delete a role
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

    /**
     * Assign permissions to a role
     */
    public function assignPermissions(Request $request, $roleId)
    {
        $role = Role::find($roleId);
        
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($request->permission_ids);

        return response()->json([
            'message' => 'Permissions assigned successfully',
            'role' => $role->load('permissions')
        ]);
    }

    /**
     * Get all permissions
     */
    public function getPermissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    /**
     * Create a new permission
     */
    public function createPermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'slug' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string',
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Permission created successfully',
            'permission' => $permission
        ], 201);
    }
}
