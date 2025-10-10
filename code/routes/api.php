<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded for ALL API requests (both central and tenant).
| 
| NEW APPROACH:
| - EnsureTenancyForApi middleware (registered globally) handles DB switching
| - It detects if the request is from a tenant domain
| - If tenant domain: switches to tenant DB connection
| - If central domain: keeps central DB connection
| 
| This means we can have ONE set of routes that work for both contexts!
| No more duplicate route registration issues.
|
*/

// Load shared routes for ALL domains (tenant/central context determined by middleware)
require __DIR__.'/api-shared.php';

// Load central-specific routes (admin panel, tenant management, etc.)
require __DIR__.'/api-central.php';
