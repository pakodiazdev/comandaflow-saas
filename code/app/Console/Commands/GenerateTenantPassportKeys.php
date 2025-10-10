<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use phpseclib3\Crypt\RSA;

class GenerateTenantPassportKeys extends Command
{
    protected $signature = 'passport:keys-tenant {tenant_id : The tenant ID} {--force : Overwrite existing keys}';
    protected $description = 'Generate Passport encryption keys for a specific tenant';

    public function handle()
    {
        $tenantId = $this->argument('tenant_id');
        $force = $this->option('force');

        $keysPath = storage_path('app/tenants/' . $tenantId . '/oauth');
        $privateKeyPath = $keysPath . '/oauth-private.key';
        $publicKeyPath = $keysPath . '/oauth-public.key';

        // Check if keys already exist
        if (File::exists($privateKeyPath) && !$force) {
            $this->error("Encryption keys for tenant '{$tenantId}' already exist. Use the --force option to overwrite them.");
            return 1;
        }

        if (!File::exists($keysPath)) {
            File::makeDirectory($keysPath, 0755, true);
        }

        $this->info("Generating Passport keys for tenant: {$tenantId}");

        $key = RSA::createKey(4096);

        File::put($privateKeyPath, (string) $key);
        File::put($publicKeyPath, (string) $key->getPublicKey());

        File::chmod($privateKeyPath, 0600);
        File::chmod($publicKeyPath, 0644);

        $this->info('Encryption keys generated successfully.');
        $this->line("<fg=gray>Private key:</> {$privateKeyPath}");
        $this->line("<fg=gray>Public key:</> {$publicKeyPath}");

        return 0;
    }
}
