<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

class DeleteTenants extends Command
{
    protected $signature = 'tenants:delete';
    protected $description = 'Delete all tenants and their databases';

    public function handle()
    {
        $this->info('Deleting all tenants...');
        
        $tenants = Tenant::all();
        
        foreach ($tenants as $tenant) {
            $this->info("Deleting tenant: {$tenant->id}");
            
            // Delete domains
            Domain::where('tenant_id', $tenant->id)->delete();
            
            // Delete tenant (this should also delete the database)
            $tenant->delete();
        }
        
        $this->info('All tenants deleted successfully!');
        
        return self::SUCCESS;
    }
}
