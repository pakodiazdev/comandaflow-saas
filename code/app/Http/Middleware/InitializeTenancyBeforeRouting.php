<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Symfony\Component\HttpFoundation\Response;

/**
 * Initialize Tenancy Before Route Matching
 * 
 * This middleware runs BEFORE Laravel matches routes, ensuring that
 * tenancy is initialized early enough to affect which routes are registered.
 * 
 * This is critical for shared routes (api-shared.php) to work correctly:
 * - Tenant domains → Use tenant context → api-shared.php loaded with tenant middleware
 * - Central domains → Use central context → api-shared.php loaded without tenant middleware
 */
class InitializeTenancyBeforeRouting
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $centralDomains = config('tenancy.central_domains', []);
        
        // Check if this is a tenant domain (not in central domains list)
        $isTenantDomain = !in_array($host, $centralDomains);
        
        if ($isTenantDomain) {
            // Initialize tenancy by domain
            $middleware = app(InitializeTenancyByDomain::class);
            return $middleware->handle($request, $next);
        }
        
        // For central domains, continue without initializing tenancy
        return $next($request);
    }
}
