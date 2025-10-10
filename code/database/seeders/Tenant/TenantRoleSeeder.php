<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;

/**
 * Tenant Role Seeder
 * 
 * Uses the RoleSeeder from cf-auth package to seed roles for Tenant databases
 * This seeder is idempotent - can be run multiple times without creating duplicates
 * 
 * The cf-auth RoleSeeder creates these roles for each tenant:
 * - owner (tenant owner/admin)
 * - manager (operations manager)
 * - cashier (payment handling)
 * - chef (kitchen operations)
 * - waiter (service operations)
 * - accountant (finance & reports)
 * - inventory_manager (stock management)
 * - technical_support (system support)
 */
class TenantRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Calls the cf-auth RoleSeeder to seed tenant roles
     */
    public function run(): void
    {
        $this->command->info('Seeding Tenant roles using cf-auth package...');
        
        // Include the RoleSeeder file from the package
        require_once base_path('packages/comandaflow-ce/code/api/packages/cf-auth/database/seeders/RoleSeeder.php');
        
        // Instantiate and run it
        $roleSeeder = new \CF\CE\Auth\Database\Seeders\RoleSeeder();
        $roleSeeder->setCommand($this->command);
        $roleSeeder->setContainer($this->container);
        $roleSeeder->run();
        
        $this->command->info('✅ Tenant roles seeded successfully!');
    }
}
