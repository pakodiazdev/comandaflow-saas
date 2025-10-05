<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stancl\Tenancy\Database\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

class CreateTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default tenants for ComandaFlow multi-tenancy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating ComandaFlow tenants...');

        $tenants = [
            'sushigo' => 'sushigo.comandaflow.local',
            'realburger' => 'realburger.comandaflow.local',
            'danielswinds' => 'danielswinds.comandaflow.local',
        ];

        foreach ($tenants as $tenantId => $domain) {
            // Check if tenant already exists
            if (Tenant::find($tenantId)) {
                $this->warn("Tenant '{$tenantId}' already exists, skipping...");
                continue;
            }

            // Create tenant
            $tenant = Tenant::create(['id' => $tenantId]);
            $this->info("Created tenant: {$tenantId}");

            // Create domain
            Domain::create([
                'domain' => $domain,
                'tenant_id' => $tenant->id,
            ]);
            $this->info("Created domain: {$domain}");

            // Run tenant migrations
            $this->info("Running migrations for tenant: {$tenantId}");
            $tenant->run(function () {
                \Artisan::call('migrate', [
                    '--path' => 'database/migrations/tenant',
                    '--force' => true,
                ]);
            });
            $this->info("Migrations completed for tenant: {$tenantId}");
        }

        $this->info('All tenants created successfully!');
        $this->line('');
        $this->line('You can now access:');
        $this->line('- Central Admin: http://comandaflow.local');
        foreach ($tenants as $tenantId => $domain) {
            $this->line("- {$tenantId}: http://{$domain}");
        }
    }
}