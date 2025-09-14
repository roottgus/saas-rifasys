<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DemoReadOnly
{
    public function handle(Request $request, Closure $next)
    {
        $u = $request->user();
        if ($u && $u->hasRole('tenant_demo')) {
            // Solo lectura en demo
            if (! in_array($request->method(), ['GET','HEAD','OPTIONS'])) {
                abort(403, 'Demo: solo lectura');
            }
        }
        return $next($request);
    }
}