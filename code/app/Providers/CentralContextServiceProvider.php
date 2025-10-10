<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Central Context Service Provider
 * 
 * DEPRECATED: This provider is no longer needed!
 * 
 * NEW APPROACH:
 * - api-shared.php routes are registered in routes/api.php (only once)
 * - EnsureTenancyForApi middleware handles DB context switching
 * - Same routes work for both central and tenant domains
 * - No duplicate route registration issues
 * 
 * This provider is kept for future central-specific services if needed.
 */
class CentralContextServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Nothing to do here anymore
        // Route context is handled by EnsureTenancyForApi middleware
    }
}
