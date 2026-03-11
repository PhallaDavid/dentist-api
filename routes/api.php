<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\TreatmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/create-user', [AuthController::class, 'register']);
Route::post('/treatments', [TreatmentController::class, 'store']); // Add this line to create treatment records

// Treatment routes (no authentication required for now)
Route::get('/treatments', [TreatmentController::class, 'index']); // Get all treatments
Route::get('/treatments/{id}', [TreatmentController::class, 'show']); // Get single treatment

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // User management routes
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/update-profile', [AuthController::class, 'updateprofile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/delete-user/{id}', [AuthController::class, 'delete']);

    // Role management routes (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('/roles', RoleController::class);
        Route::post('/roles/{roleId}/assign-permissions', [RoleController::class, 'assignPermissions']);
        Route::get('/permissions', [RoleController::class, 'getPermissions']);
        Route::post('/permissions', [RoleController::class, 'createPermission']);
    });

    // Permission management routes (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('/permissions', PermissionController::class);
    });

    // User management with specific permissions
    Route::middleware('permission:manage-users')->group(function () {
        Route::get('/users', [AuthController::class, 'index']);
        Route::delete('/delete-user/{id}', [AuthController::class, 'delete']);
    });

    // Profile management with specific permissions
    Route::middleware('permission:manage-profile')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/update-profile', [AuthController::class, 'updateprofile']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);
    });

    // Treatment management routes (authentication required)
    Route::put('/treatments/{id}', [TreatmentController::class, 'update']); // Update treatment
    Route::delete('/treatments/{id}', [TreatmentController::class, 'destroy']); // Delete treatment
});
