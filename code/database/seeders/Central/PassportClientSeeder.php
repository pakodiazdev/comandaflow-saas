<?php

namespace Database\Seeders\Central;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if personal access client already exists
        $personalClientExists = DB::table('oauth_clients')
            ->where('personal_access_client', true)
            ->exists();

        if (!$personalClientExists) {
            // Create Personal Access Client
            $personalClientId = DB::table('oauth_clients')->insertGetId([
                'name' => 'ComandaFlow Central Personal Access Client',
                'secret' => Str::random(40),
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Register personal access client
            DB::table('oauth_personal_access_clients')->insert([
                'client_id' => $personalClientId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info('Personal Access Client created successfully for Central.');
        } else {
            $this->command->info('Personal Access Client already exists for Central.');
        }

        // Check if password grant client already exists
        $passwordClientExists = DB::table('oauth_clients')
            ->where('password_client', true)
            ->exists();

        if (!$passwordClientExists) {
            // Create Password Grant Client
            DB::table('oauth_clients')->insert([
                'name' => 'ComandaFlow Central Password Grant Client',
                'secret' => Str::random(40),
                'redirect' => 'http://localhost',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info('Password Grant Client created successfully for Central.');
        } else {
            $this->command->info('Password Grant Client already exists for Central.');
        }
    }
}
