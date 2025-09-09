<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\RifaNumero;
use Illuminate\Support\Facades\DB;
use App\Models\OrderLog;

class OrdersExpire extends Command
{
    protected $signature = 'orders:expire {--include-submitted : Expira también órdenes en submitted} {--limit=1000}';
    protected $description = 'Libera números de órdenes vencidas (pending por defecto) y marca la orden como expired.';

    public function handle(): int
    {
        $statuses = ['pending'];
        if ($this->option('include-submitted')) {
            $statuses[] = 'submitted';
        }

        $now   = now();
        $limit = (int) $this->option('limit');

        $totalOrders   = 0;
        $totalReleased = 0;
        $totalSkipped  = 0;
        $totalFailed   = 0;

        Order::query()
            ->whereIn('status', $statuses)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->with(['items:id,order_id,rifa_id,numero'])
            ->orderBy('id')
            ->chunkById($limit, function ($orders) use (&$totalOrders, &$totalReleased, &$totalSkipped, &$totalFailed) {
                foreach ($orders as $o) {
                    try {
                        DB::transaction(function () use ($o, &$totalOrders, &$totalReleased, &$totalSkipped) {
                            // 1) Releer y BLOQUEAR la orden para evitar carreras con "paid"
                            /** @var Order $order */
                            $order = Order::query()
                                ->whereKey($o->id)
                                ->lockForUpdate()
                                ->first();

                            if (! $order) {
                                $totalSkipped++;
                                return;
                            }

                            $currentStatus = $order->status instanceof \BackedEnum ? $order->status->value : $order->status;
                            if (! in_array($currentStatus, ['pending', 'submitted'], true)) {
                                // Ya cambió de estado mientras llegábamos aquí → saltar
                                $totalSkipped++;
                                return;
                            }

                            // 2) Bloquear números de la orden
                            $nums = $order->items->pluck('numero')->all();

                            $rows = RifaNumero::query()
                                ->where('tenant_id', $order->tenant_id)
                                ->where('rifa_id', $order->rifa_id)
                                ->whereIn('numero', $nums)
                                ->lockForUpdate()
                                ->get();

                            $releasedThisOrder = 0;

                            foreach ($rows as $row) {
                                $estado = $row->estado instanceof \BackedEnum ? $row->estado->value : $row->estado;
                                if ($estado === 'reservado') {
                                    $row->estado = 'disponible';
                                    $row->reservado_hasta = null;
                                    $row->save();
                                    $releasedThisOrder++;
                                }
                            }

                            // 3) Marcar la orden como expirada
                            $order->status = 'expired';
                            $order->expires_at = null;
                            $order->save();

                            // 4) Log
                            OrderLog::create([
                                'order_id'  => $order->id,
                                'tenant_id' => $order->tenant_id,
                                'actor_id'  => null, // sistema
                                'action'    => 'expired',
                                'notes'     => 'Orden vencida automáticamente. Números liberados.',
                                'meta'      => ['items' => $order->items->pluck('numero')->values()],
                            ]);

                            $totalOrders++;
                            $totalReleased += $releasedThisOrder;
                        });
                    } catch (\Throwable $e) {
                        report($e);
                        $totalFailed++;
                        // continuamos con la siguiente orden
                    }
                }
            });

        $this->info(
            "Órdenes expiradas: {$totalOrders} | Números liberados: {$totalReleased} | " .
            "Saltadas: {$totalSkipped} | Fallidas: {$totalFailed}"
        );

        return self::SUCCESS;
    }
}
