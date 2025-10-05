<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class SwaggerServiceProvider extends ServiceProvider
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
        // Determine context and set appropriate Swagger documentation
        $this->app->booted(function () {
            $isTenant = $this->detectTenantContext();
            
            // Set the default documentation based on context
            Config::set('l5-swagger.default', $isTenant ? 'tenant' : 'central');
        });
    }

    /**
     * Detect if we're in tenant context from multiple sources:
     * - Path: /sushigo/api/v1/doc (via nginx header X-Tenant-Path)
     * - Subdomain: sushigo.comandaflow.local
     * - Domain: sushigo.local
     */
    private function detectTenantContext(): bool
    {
        // Check if tenancy is already initialized (subdomain/domain detection by tenancy middleware)
        if (app()->bound('currentTenant') && tenancy()->tenant) {
            return true;
        }

        // Check if nginx passed a tenant identifier via X-Tenant-Path header
        $tenantId = Request::header('X-Tenant-Path');
        if ($tenantId) {
            // Try to initialize tenancy from path
            $tenant = \App\Models\Tenant::find($tenantId);
            if ($tenant) {
                tenancy()->initialize($tenant);
                return true;
            }
        }

        // Check path for tenant identifier: /{tenant}/api/v1/doc (fallback)
        $path = Request::path();
        if (preg_match('#^([^/]+)/api/v1/doc#', $path, $matches)) {
            $tenantId = $matches[1];
            
            // Try to initialize tenancy from path
            $tenant = \App\Models\Tenant::find($tenantId);
            if ($tenant) {
                tenancy()->initialize($tenant);
                return true;
            }
        }

        return false;
    }
}
