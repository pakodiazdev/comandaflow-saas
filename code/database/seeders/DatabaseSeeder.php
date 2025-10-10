<?php

namespace Database\Seeders;

use Database\Seeders\Central\CentralDBSeeder;
use Database\Seeders\Tenant\TenantDBSeeder;
use Illuminate\Database\Seeder;

/**
 * Database Seeder
 * 
 * Main entry point for database seeding.
 * Calls both CentralDBSeeder and TenantDBSeeder.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Database Seeding...');
        $this->command->newLine();

        // Seed Central Database
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('   CENTRAL DATABASE SEEDING');
        $this->command->info('═══════════════════════════════════════');
        $this->call(CentralDBSeeder::class);
        
        $this->command->newLine();
        
        // Note: TenantDBSeeder should be called via tenants:seed command
        // to ensure proper tenant context
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('   TENANT DATABASE SEEDING');
        $this->command->info('═══════════════════════════════════════');
        $this->command->warn('To seed tenant databases, run:');
        $this->command->line('  php artisan tenants:seed --class=TenantDBSeeder');
        
        $this->command->newLine();
        $this->command->info('✅ Database seeding completed!');
    }
}
