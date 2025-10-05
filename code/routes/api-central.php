<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Central\CentralController;
use App\Http\Controllers\Api\Central\TenantManagementController;

/*
|--------------------------------------------------------------------------
| Central API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register central API routes that are only
| available in the central domain context.
|
*/

// Swagger Documentation for Central API is auto-registered by L5Swagger
// Nginx redirects /api/v1/doc to /api/internal/swagger/central

// Public central API routes
Route::prefix('v1/central')->group(function () {
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
// TODO: Uncomment when Passport is implemented
// Route::prefix('v1/central')->middleware('auth:api')->group(function () {
//     // Add protected central routes here (e.g., admin-only operations)
// });

