<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;

class AttachAdminToTenantSeeder extends Seeder
{
    public function run(): void
    {
        // AJUSTA estos valores a lo que ya tienes
        $adminEmail = 'admin@example.com';
        $tenantSlug = 'rifasys';

        $user   = User::where('email', $adminEmail)->first();
        $tenant = Tenant::where('slug', $tenantSlug)->first();

        if (! $user) {
            $this->command->error("Usuario {$adminEmail} no existe. Crea uno con: php artisan make:filament-user");
            return;
        }

        if (! $tenant) {
            $this->command->error("Tenant {$tenantSlug} no existe. Crea uno con: php artisan tenant:create {$tenantSlug} \"{$tenantSlug}\"");
            return;
        }

        $user->tenants()->syncWithoutDetaching([$tenant->id]);
        $this->command->info("Asociado {$adminEmail} → {$tenantSlug}");
    }
}
