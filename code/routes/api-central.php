<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Central\CentralController;
use App\Http\Controllers\Api\Central\TenantManagementController;
use App\Http\Controllers\Api\Central\CentralRoleController;
use App\Http\Controllers\Api\Central\CentralPermissionController;

/*
|--------------------------------------------------------------------------
| Central API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register central API routes that are only
| available in the central domain context.
|
| NOTE: Authentication routes (/api/v1/auth/*) are in api-shared.php
| They work for both central and tenant contexts automatically.
|
*/

// Swagger Documentation for Central API is auto-registered by L5Swagger
// Nginx redirects /api/v1/doc to /api/internal/swagger/central

// Public central API routes
Route::prefix('v1/central')->group(function () {
    // NOTE: Authentication routes are in api-shared.php under /api/v1/auth/*
    // They work for both central and tenant contexts thanks to EnsureTenancyForApi middleware
    // No need to duplicate them here!
    
    // Central system routes
    Route::get('/tenants', [CentralController::class, 'tenants']);
    Route::get('/stats', [CentralController::class, 'stats']);
    Route::get('/health', [CentralController::class, 'health']);
    
    // Tenant Management (CRUD)
    Route::post('/tenants', [TenantManagementController::class, 'store']);
    Route::get('/tenants/{id}', [TenantManagementController::class, 'show']);
    Route::put('/tenants/{id}', [TenantManagementController::class, 'update']);
    Route::patch('/tenants/{id}', [TenantManagementController::class, 'update']);
    Route::delete('/tenants/{id}', [TenantManagementController::class, 'destroy']);
    
    // Tenant Domain Management
    Route::post('/tenants/{id}/domains', [TenantManagementController::class, 'addDomain']);
    Route::delete('/tenants/{id}/domains/{domainId}', [TenantManagementController::class, 'removeDomain']);
});

// Protected central API routes (authentication required)
Route::prefix('v1/central')->middleware('auth:api')->group(function () {
    // NOTE: Authentication routes (logout, me, refresh) are in api-shared.php under /api/v1/auth/*
    // They work for both central and tenant contexts thanks to EnsureTenancyForApi middleware
    
    // Roles management - Central context
    Route::prefix('roles')->group(function () {
        Route::get('/', [CentralRoleController::class, 'index']);
        Route::post('/', [CentralRoleController::class, 'store']);
        Route::get('/{id}', [CentralRoleController::class, 'show']);
        Route::put('/{id}', [CentralRoleController::class, 'update']);
        Route::patch('/{id}', [CentralRoleController::class, 'update']);
        Route::delete('/{id}', [CentralRoleController::class, 'destroy']);
        Route::post('/{id}/permissions', [CentralRoleController::class, 'assignPermissions']);
        Route::delete('/{id}/permissions/{permissionName}', [CentralRoleController::class, 'removePermission']);
    });
    
    // Permissions management (read-only) - Central context
    Route::prefix('permissions')->group(function () {
        Route::get('/', [CentralPermissionController::class, 'index']);
        Route::get('/{id}', [CentralPermissionController::class, 'show']);
    });
});

