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

class MarkOrderPaid
{
    public function handle(Order $order): void
    {
        $status = $order->status instanceof \BackedEnum ? $order->status->value : $order->status;
        if (! in_array($status, [OrderStatus::Pending->value, OrderStatus::Submitted->value], true)) {
            throw new RuntimeException('Esta orden no puede marcarse como pagada (estado inválido).');
        }

        $items = $order->items()->select('rifa_id', 'numero')->get();

        DB::transaction(function () use ($order, $items) {
            foreach ($items as $item) {
                $numero = RifaNumero::query()
                    ->where('tenant_id', $order->tenant_id)
                    ->where('rifa_id', $item->rifa_id)
                    ->where('numero', $item->numero)
                    ->lockForUpdate()
                    ->first();

                if (! $numero) {
                    throw new RuntimeException("Número {$item->numero} no existe para la rifa #{$item->rifa_id}.");
                }

                $estado = $numero->estado instanceof \BackedEnum ? $numero->estado->value : $numero->estado;
                if ($estado === NumeroEstado::Disponible->value) {
                    throw new RuntimeException("El número {$item->numero} está disponible (no reservado).");
                }

                if ($estado === NumeroEstado::Reservado->value) {
                    $numero->estado = NumeroEstado::Pagado;
                    $numero->reservado_hasta = null;
                    $numero->save();
                }
            }

            $order->status = OrderStatus::Paid;
            $order->expires_at = null;
            $order->save();

            OrderLog::create([
                'order_id'  => $order->id,
                'tenant_id' => $order->tenant_id,
                'actor_id'  => Auth::id(),
                'action'    => 'paid',
                'notes'     => 'Orden aprobada y números confirmados.',
                'meta'      => ['items' => $items->pluck('numero')->values()],
            ]);
        });
    }
}
