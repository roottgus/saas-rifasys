<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;

class SetTenant
{
    public function handle(Request $request, Closure $next)
    {
        // Dev: prefijo /t/{tenant}; Prod: puedes usar subdominio {tenant}.tudominio.com
        $slugFromPrefix = $request->route('tenant');            // /t/{tenant}
        $hostSlug = explode('.', $request->getHost())[0] ?? ''; // subdominio (opcional)

        $slug = $slugFromPrefix ?: $hostSlug;

        if ($slug) {
            $tenant = Tenant::where('slug', $slug)->first();
            if ($tenant) {
                app()->instance(Tenant::class, $tenant);
            }
        }
        return $next($request);
    }
}
