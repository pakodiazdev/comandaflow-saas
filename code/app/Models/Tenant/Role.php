<?php

namespace App\Models\Tenant;

use CF\CE\Auth\Models\Role as BaseRole;

/**
 * Tenant Role Model
 * 
 * Extends CF\CE\Auth\Models\Role with additional helper methods
 * This model operates on tenant-specific databases for isolated roles
 */
class Role extends BaseRole
{
    protected $connection = 'tenant';

    protected $guard_name = 'tenant';

    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    public static function getByCodeOrCreate(array $attributes)
    {
        $name = $attributes['name'] ?? $attributes['code'] ?? null;
        
        if (!$name) {
            throw new \InvalidArgumentException('Name is required for getByCodeOrCreate');
        }

        $role = static::where('name', $name)
            ->where('guard_name', $attributes['guard_name'] ?? 'tenant')
            ->first();

        if ($role) {
            return $role;
        }

        $attributes['name'] = $name;
        unset($attributes['code']);
        return static::create($attributes);
    }
}
