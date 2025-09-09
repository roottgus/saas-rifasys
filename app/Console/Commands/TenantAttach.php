<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Tenant;

class TenantAttach extends Command
{
    protected $signature = 'tenant:attach {tenantSlug} {email}';
    protected $description = 'Adjunta un usuario a un tenant (pivot tenant_user)';

    public function handle(): int
    {
        $slug  = $this->argument('tenantSlug');
        $email = $this->argument('email');

        $tenant = Tenant::where('slug', $slug)->first();
        if (! $tenant) {
            $this->error("Tenant {$slug} no existe.");
            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();
        if (! $user) {
            $this->error("Usuario {$email} no existe.");
            return self::FAILURE;
        }

        $user->tenants()->syncWithoutDetaching([$tenant->id]);
        $this->info("OK: {$email} adjunto a {$slug}");
        return self::SUCCESS;
    }
}
