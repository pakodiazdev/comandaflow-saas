<?php

namespace App\Models\Tenant;

use CF\CE\Auth\Models\Permission as BasePermission;

/**
 * Tenant Permission Model
 * 
 * Extends CF\CE\Auth\Models\Permission with additional helper methods
 * This model operates on tenant-specific databases for isolated permissions
 */
class Permission extends BasePermission
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

        $permission = static::where('name', $name)
            ->where('guard_name', $attributes['guard_name'] ?? 'tenant')
            ->first();

        if ($permission) {
            return $permission;
        }

        $attributes['name'] = $name;
        unset($attributes['code']);
        return static::create($attributes);
    }
}
