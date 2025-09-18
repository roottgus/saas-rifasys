<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));
        
        // Observer para órdenes
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        
        // Observer para contratos de servicio (AUTOMÁTICO para contract_number y uuid)
        \App\Models\Contract::observe(\App\Observers\ContractObserver::class);
    }
}
