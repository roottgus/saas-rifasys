<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Enums\NumeroEstado;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\RifaNumero;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CancelOrder
{
    public function handle(Order $order): void
    {
        $status = $order->status instanceof \BackedEnum ? $order->status->value : $order->status;
        if (! in_array($status, [OrderStatus::Pending->value, OrderStatus::Submitted->value], true)) {
            throw new RuntimeException('Solo se pueden cancelar órdenes en pending o submitted.');
        }

        $items = $order->items()->select('rifa_id', 'numero')->get();

        DB::transaction(function () use ($order, $items) {
            foreach ($items as $item) {
                $num = RifaNumero::query()
                    ->where('tenant_id', $order->tenant_id)
                    ->where('rifa_id', $item->rifa_id)
                    ->where('numero', $item->numero)
                    ->lockForUpdate()
                    ->first();

                if ($num) {
                    $estado = $num->estado instanceof \BackedEnum ? $num->estado->value : $num->estado;
                    if ($estado === NumeroEstado::Reservado->value) {
                        $num->estado = NumeroEstado::Disponible;
                        $num->reservado_hasta = null;
                        $num->save();
                    }
                }
            }

            $order->status = OrderStatus::Cancelled;
            $order->expires_at = null;
            $order->save();

            OrderLog::create([
                'order_id'  => $order->id,
                'tenant_id' => $order->tenant_id,
                'actor_id'  => Auth::id(),
                'action'    => 'cancelled',
                'notes'     => 'Orden cancelada por operador y números liberados.',
                'meta'      => ['items' => $items->pluck('numero')->values()],
            ]);
        });
    }
}
