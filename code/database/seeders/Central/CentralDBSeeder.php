<?php

namespace Database\Seeders\Central;

use Illuminate\Database\Seeder;

/**
 * Central Database Seeder
 * 
 * Seeds data for the Central database (main application database)
 * Manages central admin users, system roles, and global configurations
 */
class CentralDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            PassportClientSeeder::class,
            CentralRoleSeeder::class,
            // Add more central seeders here as needed
        ]);
    }
}
