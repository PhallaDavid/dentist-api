<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Get all permissions
     */
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    /**
     * Create a new permission
     */
    public function store(Request $request)
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

    /**
     * Get a specific permission
     */
    public function show($id)
    {
        $permission = Permission::find($id);
        
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }

        return response()->json($permission);
    }

    /**
     * Update a permission
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:permissions,name,' . $id,
            'slug' => 'sometimes|required|string|max:255|unique:permissions,slug,' . $id,
            'description' => 'nullable|string',
        ]);

        $permission->update($request->only(['name', 'slug', 'description']));

        return response()->json([
            'message' => 'Permission updated successfully',
            'permission' => $permission
        ]);
    }

    /**
     * Delete a permission
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }

        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
