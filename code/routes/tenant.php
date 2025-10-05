<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\Tenant\TenantController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here is where you can register tenant routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "tenant" middleware group.
|
*/

Route::get('/', function () {
    return view('tenant.welcome', [
        'tenant' => tenant(),
        'domain' => request()->getHost()
    ]);
});

Route::get('/dashboard', function () {
    return view('tenant.dashboard', [
        'tenant' => tenant(),
        'domain' => request()->getHost()
    ]);
})->name('tenant.dashboard');

// Tenant API routes
Route::prefix('api/v1')->group(function () {
    Route::get('/tenant/info', [TenantController::class, 'info']);
    Route::get('/tenant/health', [TenantController::class, 'health']);
    
    // Test model endpoints for debugging
    Route::get('/tests', function () {
        return response()->json([
            'tenant_id' => tenant('id'),
            'database' => DB::getDatabaseName(),
            'connection' => DB::connection()->getName(),
            'tests' => \App\Models\Test::all(),
            'count' => \App\Models\Test::count(),
        ]);
    });
    
    Route::post('/tests', function (\Illuminate\Http\Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        
        $test = \App\Models\Test::create([
            'name' => $request->input('name')
        ]);
        
        return response()->json([
            'tenant_id' => tenant('id'),
            'database' => DB::getDatabaseName(),
            'test' => $test,
            'message' => 'Test created successfully'
        ], 201);
    });
    
    Route::delete('/tests/{id}', function ($id) {
        $test = \App\Models\Test::findOrFail($id);
        $test->delete();
        
        return response()->json([
            'tenant_id' => tenant('id'),
            'message' => 'Test deleted successfully'
        ]);
    });
});

