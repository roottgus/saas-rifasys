<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class TenantCreate extends Command
{
    protected $signature = 'tenant:create
        {slug : Slug del tenant (ej. miempresa)}
        {name : Nombre del tenant (ej. Mi Empresa)}
        {--domain= : Dominio completo opcional}
        {--primary=#2563EB : Color primario (hex)}
        {--mode=light : light|dark}';

    protected $description = 'Crea un tenant de forma repetible sin usar Tinker';

    public function handle(): int
    {
        $slug    = $this->argument('slug');
        $name    = $this->argument('name');
        $domain  = $this->option('domain') ?: null;
        $primary = $this->option('primary') ?: '#2563EB';
        $mode    = in_array($this->option('mode'), ['light','dark']) ? $this->option('mode') : 'light';

        $tenant = Tenant::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'domain' => $domain,
                'branding_json' => ['primary' => $primary, 'mode' => $mode],
                'status' => 'active',
            ]
        );

        $this->info("Tenant listo: {$tenant->name} ({$tenant->slug})");
        return self::SUCCESS;
    }
}
