<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Note: Shared API routes are NOT registered here
            // They are registered in routes/api.php and routes/tenant.php
            // to ensure proper tenancy context detection
            
            // Register central API routes (only in central domain context)
            Route::middleware(['api'])
                ->prefix('api')
                ->group(base_path('routes/api-central.php'));
            
            // Register tenant API routes (only in tenant context)
            Route::middleware(['api', 'tenant'])
                ->prefix('api')
                ->group(base_path('routes/api-tenant.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // CRITICAL: Ensure tenancy is initialized BEFORE any other middleware
        // This middleware runs for ALL API requests and switches database connection
        // based on the domain making the request
        $middleware->priority([
            \App\Http\Middleware\EnsureTenancyForApi::class,
        ]);
        
        $middleware->api(prepend: [
            \App\Http\Middleware\EnsureTenancyForApi::class,
        ]);
        
        $middleware->alias([
            'tenant' => \App\Http\Middleware\IdentifyTenantBySubdomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
