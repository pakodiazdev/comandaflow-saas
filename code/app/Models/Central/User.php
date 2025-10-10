<?php

namespace App\Models\Central;

use CF\CE\Auth\Models\User as BaseUser;

/**
 * Central User Model
 * 
 * This model represents users in the Central database context.
 * It extends the CF Auth User model with Laravel Passport and Spatie Permissions.
 */
class User extends BaseUser
{
    /**
     * The guard name for this model
     *
     * @var string
     */
    protected $guard_name = 'central';

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'pgsql';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
}
