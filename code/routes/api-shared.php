<?php

use Illuminate\Support\Facades\Route;
use CF\CE\Auth\Http\Controllers\AuthController;
use App\Http\Controllers\Api\Shared\HealthController;
use App\Http\Controllers\Api\Shared\RoleController;
use App\Http\Controllers\Api\Shared\PermissionController;

/*
|--------------------------------------------------------------------------
| Shared API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register shared API routes that are available
| in both central and tenant contexts.
|
| Authentication routes use Laravel Passport with context-aware guards:
| - Central: Uses 'central' guard with Central User model
| - Tenant: Uses 'tenant' guard with Tenant User model (isolated per tenant)
|
*/

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Health check endpoint
    Route::get('/health', [HealthController::class, 'check']);
    
    // Authentication routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
    });
});

// Protected API routes (authentication required)
// Uses Passport middleware with context-aware guards (central/tenant)
Route::prefix('v1')->middleware('auth:api')->group(function () {
    // Authenticated routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
    
    // Roles management
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::patch('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
        Route::post('/{id}/permissions', [RoleController::class, 'assignPermissions']);
        Route::delete('/{id}/permissions/{permissionName}', [RoleController::class, 'removePermission']);
    });
    
    // Permissions management (read-only)
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/{id}', [PermissionController::class, 'show']);
    });
});

