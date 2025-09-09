<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    // ==== Alias de middlewares (Laravel 11/12) ====
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // Middleware para fijar el tenant en cada request
            'setTenant' => \App\Http\Middleware\SetTenant::class,
        ]);
    })

    // ==== Registrar comandos Artisan adicionales ====
    ->withCommands([
        \App\Console\Commands\TenantCreate::class,
        \App\Console\Commands\TenantAttach::class,
        \App\Console\Commands\RifaGenerateNumbers::class,
        \App\Console\Commands\OrdersExpire::class, // <— libera reservas vencidas
    ])

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
