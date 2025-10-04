<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class CheckTenants extends Command
{
    protected $signature = 'tenants:check';
    protected $description = 'Check tenants and their domains';

    public function handle()
    {
        $this->info('Checking tenants and domains...');
        
        $tenants = Tenant::with('domains')->get();
        
        if ($tenants->isEmpty()) {
            $this->warn('No tenants found!');
            return;
        }
        
        $this->table(
            ['Tenant ID', 'Domains'],
            $tenants->map(function ($tenant) {
                return [
                    $tenant->id,
                    $tenant->domains->pluck('domain')->join(', ')
                ];
            })
        );
        
        return self::SUCCESS;
    }
}
