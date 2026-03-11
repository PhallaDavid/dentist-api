<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
       $users = User::all();
        return response()->json($users);
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|regex:/^\+?[0-9]{7,15}$/',
            'role_id' => 'required|integer|exists:roles,id',
            'status' => 'nullable|string|in:active,inactive,pending',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $avatarPath = asset('storage/' . $avatarPath);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $avatarPath,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'status' => $request->status ?? 'active',
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Check if user account is inactive
        if ($user->status === 'inactive') {
            return response()->json(['message' => 'Account is inactive. Please contact support.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
    public function updateprofile(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|regex:/^\+?[0-9]{7,15}$/',
            'role_id' => 'sometimes|required|integer|exists:roles,id',
            'status' => 'nullable|string|in:active,inactive,pending',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = asset('storage/' . $avatarPath);
        }

        $user->update($request->only(['name', 'email', 'phone', 'role_id', 'status']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
