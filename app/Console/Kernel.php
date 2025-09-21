<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Libera números reservados de órdenes vencidas (pending por defecto)
        $schedule->command('app:expirar-reservas')
            ->everyFiveMinutes()
            ->withoutOverlapping();

        // Si quisieras incluir también submitted:
        // $schedule->command('orders:release-expired --include-submitted')
        //     ->everyFiveMinutes()
        //     ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
