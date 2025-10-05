<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Tenant\TenantController;

/*
|--------------------------------------------------------------------------
| Tenant API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register tenant API routes that are only
| available in tenant domain contexts.
|
*/

// Swagger Documentation for Tenant API is auto-registered by L5Swagger
// Nginx redirects /api/v1/doc to /api/internal/swagger/tenant

// Public tenant API routes
Route::prefix('v1/tenant')->group(function () {
    // Tenant information routes
    Route::get('/info', [TenantController::class, 'info']);
    Route::get('/health', [TenantController::class, 'health']);
});

// Protected tenant API routes (authentication required)
// TODO: Uncomment when Passport is implemented
// Route::prefix('v1/tenant')->middleware('auth:api')->group(function () {
//     // Add protected tenant routes here
// });

