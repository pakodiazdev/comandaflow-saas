<?php

use App\Http\Middleware\IdentifyTenantBySubdomain;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

return [
    'tenant' => [
        IdentifyTenantBySubdomain::class,
    ],
];
