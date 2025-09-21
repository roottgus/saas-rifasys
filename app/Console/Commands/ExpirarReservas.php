<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\RifaNumero;
use App\Enums\OrderStatus;
use App\Enums\NumeroEstado;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExpirarReservas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Ejecutar con: php artisan app:expirar-reservas
     *
     * Lo ideal: ponerlo en el cron cada 5 minutos.
     *
     * @var string
     */
    protected $signature = 'app:expirar-reservas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marca como expiradas las órdenes de reserva vencidas y libera los números reservados.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Buscar órdenes pending y vencidas
        $orders = Order::where('status', OrderStatus::Pending)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->with('items')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No hay reservas vencidas para expirar.');
            return 0;
        }

        $totalOrdenes = $orders->count();
        $totalNumeros = 0;

        DB::transaction(function () use ($orders, &$totalNumeros) {
            foreach ($orders as $order) {
                // Expirar orden
                $order->status = OrderStatus::Expired;
                $order->save();

                // Liberar números reservados
                $numeros = $order->items->pluck('numero');
                $totalNumeros += $numeros->count();

                RifaNumero::where('rifa_id', $order->rifa_id)
                    ->whereIn('numero', $numeros)
                    ->update([
                        'estado'          => NumeroEstado::Disponible,
                        'reservado_hasta' => null,
                        'session_id'      => null,
                        'client_info'     => null,
                    ]);
            }
        });

        $this->info("Órdenes expiradas: $totalOrdenes | Números liberados: $totalNumeros");
        return 0;
    }
}
