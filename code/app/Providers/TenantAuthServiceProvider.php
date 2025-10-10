<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Stancl\Tenancy\Database\Models\Domain;

/**
 * Tenant Auth Service Provider
 * 
 * This provider extends the CF Auth functionality to support multi-tenancy.
 * It configures Passport keys location based on the current context (Central vs Tenant).
 */
class TenantAuthServiceProvider extends ServiceProvider
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
        // Configure Passport keys based on context
        $this->configurePassportKeys();
    }

    /**
     * Configure Passport keys location based on context (Central vs Tenant)
     */
    private function configurePassportKeys(): void
    {
        // Check if we're in a tenant context
        if ($this->app->bound('currentTenant')) {
            $tenant = $this->app->make('currentTenant');
            
            // Tenant context - use tenant-specific keys
            $keysPath = storage_path('app/tenants/' . $tenant->id . '/oauth');
            
            // Create directory if it doesn't exist
            if (!file_exists($keysPath)) {
                mkdir($keysPath, 0755, true);
            }

            Passport::loadKeysFrom($keysPath);
        } else {
            // Central context - use default keys location
            Passport::loadKeysFrom(storage_path());
        }
    }
}
