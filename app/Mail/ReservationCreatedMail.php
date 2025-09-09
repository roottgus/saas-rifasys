<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $footerGifs = [];

    public function __construct(public Order $order) {}

    public function build()
    {
        // Cargar relaciones
        $order  = $this->order->loadMissing(['tenant', 'rifa', 'items']);
        $tenant = $order->tenant;
        $rifa   = $order->rifa;

        // Números reservados (array)
        $ticketsArr = $order->items->pluck('numero')->sort()->values()->all();

        // Números como texto "016, 025, 104"
        $ticketsText = collect($ticketsArr)->map(fn($n) => str_pad($n, 3, '0', STR_PAD_LEFT))->implode(', ');

        // URL para continuar la orden (CHECKOUT)
        $checkoutUrl = route('store.checkout', [
            'tenant' => $tenant->slug ?? $tenant->id,
            'code'   => $order->code,
        ]);

        // --------- GIF universal ---------
        $this->footerGifs = [];
        $footerGifPaths = [];
        foreach (['qr.gif', 'factura.gif', 'check.gif'] as $f) {
            $p = storage_path('app/public/email/' . $f);
            if (is_file($p)) {
                $footerGifPaths[] = $p;
            }
        }
        $gifCidMap = [];
        foreach ($footerGifPaths as $p) {
            $id = 'gif-' . pathinfo($p, PATHINFO_FILENAME) . '-' . uniqid();
            $gifCidMap[] = ['id' => $id, 'path' => $p];
            $this->footerGifs[] = 'cid:' . $id;
        }
        $this->withSymfonyMessage(function ($message) use ($gifCidMap) {
            foreach ($gifCidMap as $row) {
                $message->embedFromPath($row['path'], $row['id'], 'image/gif');
            }
        });
        // ----------------------------------

        return $this->subject('Reserva confirmada: ' . $order->code)
            ->view('emails.orders.reserved-user') 
            ->with([
                // Branding
                'tenant'        => $tenant,
                'tenantName'    => $tenant->name ?? config('app.name'),
                'tenantUrl'     => url("/t/{$tenant->slug}"),
                'logoUrl'       => $tenant->logo_url ?? asset('img/logo-mail.png'),
                'primary'       => '#2563eb',

                // Orden / Rifa
                'order'         => $order,
                'rifa'          => $rifa,
                'rifaTitle'     => $rifa?->titulo ?? $rifa?->nombre ?? '-',
                'ticketsArr'    => $ticketsArr,
                'ticketsText'   => $ticketsText,
                'totalUsd'      => '$' . number_format((float) $order->total_amount, 2),
                'customerName'  => $order->customer_name,

                // Reserva
                'reservedUntil' => null, // ahora el Blade dirá "4 horas a partir de tu reserva"

                // Links
                'checkoutUrl'   => $checkoutUrl,
                'footerGifs'    => $this->footerGifs,
            ]);
    }
}
