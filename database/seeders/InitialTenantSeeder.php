<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;

class InitialTenantSeeder extends Seeder
{
    public function run(): void
    {
        // Cambia slug/name por tu cliente inicial
        Tenant::firstOrCreate(
            ['slug' => 'rifasys'],
            [
                'name' => 'Rifasys',
                'domain' => null,
                'branding_json' => ['primary' => '#2563EB', 'mode' => 'light'],
                'status' => 'active',
            ]
        );
    }
}
