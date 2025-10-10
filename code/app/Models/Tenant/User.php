<?php

namespace App\Models\Tenant;

use CF\CE\Auth\Models\User as BaseUser;

/**
 * Tenant User Model
 * 
 * This model represents users in the Tenant database context.
 * It extends the CF Auth User model with Laravel Passport and Spatie Permissions.
 * Each tenant has its own isolated set of users.
 */
class User extends BaseUser
{
    protected $guard_name = 'tenant';
    protected $connection = 'tenant';
    protected $table = 'users';
}
