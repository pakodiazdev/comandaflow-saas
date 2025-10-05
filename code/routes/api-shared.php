<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\Shared\AuthController; // TODO: Requires Laravel Passport
use App\Http\Controllers\Api\Shared\HealthController;

/*
|--------------------------------------------------------------------------
| Shared API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register shared API routes that are available
| in both central and tenant contexts.
|
*/

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Health check endpoint
    Route::get('/health', [HealthController::class, 'check']);
    
    // Authentication routes - TODO: Implement when Passport is added
    // Route::prefix('auth')->group(function () {
    //     Route::post('/login', [AuthController::class, 'login']);
    //     Route::post('/register', [AuthController::class, 'register']);
    //     Route::post('/refresh', [AuthController::class, 'refresh']);
    // });
});

// Protected API routes (authentication required)
// TODO: Uncomment when Passport is implemented
// Route::prefix('v1')->middleware('auth:api')->group(function () {
//     // Authenticated routes
//     Route::prefix('auth')->group(function () {
//         Route::post('/logout', [AuthController::class, 'logout']);
//         Route::get('/user', [AuthController::class, 'user']);
//     });
// });

