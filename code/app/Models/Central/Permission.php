<?php

namespace App\Models\Central;

use CF\CE\Auth\Models\Permission as BasePermission;

/**
 * Central Permission Model
 * 
 * Extends CF\CE\Auth\Models\Permission with additional helper methods
 * This model operates on the central database for system-wide permissions
 */
class Permission extends BasePermission
{
    /**
     * The database connection to use
     *
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * The guard name for central permissions
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
     * Get permission by name or create if doesn't exist
     * 
     * This method is used by the cf-auth RoleSeeder to ensure
     * idempotent seeding of permissions
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
        $permission = static::where('name', $name)
            ->where('guard_name', $attributes['guard_name'] ?? 'central')
            ->first();

        if ($permission) {
            return $permission;
        }

        // Create new permission
        $attributes['name'] = $name;
        unset($attributes['code']);
        return static::create($attributes);
    }
}
