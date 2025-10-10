<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;

/**
 * Tenant Database Seeder
 * 
 * Seeds data for Tenant databases (each tenant's isolated database)
 * Manages tenant-specific users, roles, and configurations
 */
class TenantDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            PassportClientSeeder::class,
            TenantRoleSeeder::class,
            // Add more tenant seeders here as needed
        ]);
    }
}
