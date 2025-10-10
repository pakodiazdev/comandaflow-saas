<?php

namespace App\Models\Central;

use CF\CE\Auth\Models\Role as BaseRole;

/**
 * Central Role Model
 * 
 * Extends CF\CE\Auth\Models\Role with additional helper methods
 * This model operates on the central database for system-wide roles
 */
class Role extends BaseRole
{
    /**
     * The database connection to use
     *
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * The guard name for central roles
     *
     * @var string
     */
    protected $guard_name = 'central';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    /**
     * Get role by name or create if doesn't exist
     * 
     * This method is used by the cf-auth RoleSeeder to ensure
     * idempotent seeding of roles
     *
     * @param array $attributes
     * @return static
     */
    public static function getByCodeOrCreate(array $attributes)
    {
        $name = $attributes['name'] ?? $attributes['code'] ?? null;
        
        if (!$name) {
            throw new \InvalidArgumentException('Name is required for getByCodeOrCreate');
        }

        // Try to find by name
        $role = static::where('name', $name)
            ->where('guard_name', $attributes['guard_name'] ?? 'central')
            ->first();

        if ($role) {
            return $role;
        }

        // Create new role
        $attributes['name'] = $name;
        unset($attributes['code']);
        return static::create($attributes);
    }
}
