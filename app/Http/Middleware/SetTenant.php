<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;

class SetTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost(); // ej: rifahermanosrodriguez.com

        // 1. Primero: busca por dominio personalizado
        $tenant = Tenant::where('domain', $host)->first();

        // 2. Si no hay dominio personalizado, busca por slug (ruta o subdominio)
        if (!$tenant) {
            $slugFromPrefix = $request->route('tenant');            // /t/{tenant}
            $hostSlug = explode('.', $host)[0] ?? ''; // subdominio (opcional)

            $slug = $slugFromPrefix ?: $hostSlug;

            if ($slug) {
                $tenant = Tenant::where('slug', $slug)->first();
            }
        }

        // 3. Si encuentra el tenant, lo setea en el contexto
        if ($tenant) {
            app()->instance('currentTenant', $tenant);
        }
        // Si no hay tenant, aquí podrías redirigir a una página 404 o la home principal

        return $next($request);
    }
}
