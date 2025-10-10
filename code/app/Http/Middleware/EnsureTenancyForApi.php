<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stancl\Tenancy\Tenancy;
use Stancl\Tenancy\Database\Models\Domain;

/**
 * Ensure Tenancy is Initialized for API Requests
 * 
 * This middleware runs BEFORE any controller logic and ensures
 * that tenancy is initialized for tenant domains.
 * 
 * For API routes, we need to ensure the correct database connection
 * is used based on the domain making the request.
 */
class EnsureTenancyForApi
{
    public function __construct(
        protected Tenancy $tenancy
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tenancy initialization for Swagger documentation routes
        if ($request->is('api/internal/swagger/*')) {
            return $next($request);
        }
        
        $host = $request->getHost();
        $centralDomains = config('tenancy.central_domains', []);
        
        // Check if this is NOT a central domain
        $isTenantDomain = !in_array($host, $centralDomains);
        
        if ($isTenantDomain && !$this->tenancy->initialized) {
            // Try to find and initialize tenant by domain
            $domain = Domain::where('domain', $host)->first();
            
            if ($domain && $domain->tenant) {
                // Initialize tenancy for this request
                $this->tenancy->initialize($domain->tenant);
            }
        }
        
        return $next($request);
    }
}
