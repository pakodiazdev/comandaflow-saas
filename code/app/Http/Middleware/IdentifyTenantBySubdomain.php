<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class IdentifyTenantBySubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if we're on a central domain
        $centralDomains = config('tenancy.central_domains', []);
        $host = $request->getHost();
        
        if (in_array($host, $centralDomains)) {
            // This is a central domain, continue without tenancy
            return $next($request);
        }
        
        // Use the built-in domain middleware (not subdomain)
        return app(InitializeTenancyByDomain::class)->handle($request, $next);
    }
}