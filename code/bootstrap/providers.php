<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenancyServiceProvider::class, // MUST load FIRST - registers tenant routes with higher priority
    App\Providers\CentralContextServiceProvider::class, // Loads after - registers central routes as fallback
    App\Providers\SwaggerServiceProvider::class,
    CF\CE\Auth\AuthServiceProvider::class,
    App\Providers\TenantAuthServiceProvider::class,
    CF\CE\TimeTracker\TimeTrackerServiceProvider::class,
];
