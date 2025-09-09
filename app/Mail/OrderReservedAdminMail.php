<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReservedAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $footerGifs = [];

    public function __construct(public Order $order) {}

    public function build()
    {
        // Cargar relaciones necesarias
        $order  = $this->order->loadMissing(['tenant', 'rifa', 'items']);
        $tenant = $order->tenant;

        // Boletos "016, 039, ..."
        $tickets = $order->items->pluck('numero')->sort()->implode(', ');

        // Enlaces públicos
        $checkoutUrl = route('store.checkout', [
            'tenant' => $tenant->slug ?? $tenant->id,
            'code'   => $order->code,
        ]);

        $verifyUrl = route('store.verify', [
            'tenant' => $tenant->slug ?? $tenant->id,
            'code'   => $order->code,
        ]);

        // Vencimiento de la reserva
        $expiresAt = $order->reserved_until
            ?? $order->expires_at
            ?? $order->reservation_expires_at
            ?? null;

        $reservedUntil = $expiresAt
            ? \Carbon\Carbon::parse($expiresAt)
                ->timezone(config('app.timezone'))
                ->format('d/m/Y H:i')
            : null;

        // ----------- FOOTER GIFS (Universal para todos los mails) ----------
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
        // -------------------------------------------------------------------

        // Datos para la vista
        $data = [
            // Branding
            'tenantName'     => $tenant->name ?? config('app.name'),
            'logoUrl'        => $tenant->logo_url ?? asset('img/logo-mail.png'),

            // Orden / Rifa
            'orderCode'      => $order->code,
            'customerName'   => $order->customer_name,
            'customerEmail'  => $order->customer_email,
            'rifaTitle'      => optional($order->rifa)->titulo ?? optional($order->rifa)->nombre,
            'tickets'        => $tickets,
            'totalUsd'       => '$' . number_format((float) $order->total_amount, 2),
            'reservedUntil'  => $reservedUntil,

            // Enlaces
            'orderPublicUrl' => $checkoutUrl,  // checkout público
            'verifyUrl'      => $verifyUrl,    // ver estado
            // 'adminOrderUrl' => url("/admin/orders/{$order->id}"), // si tienes ruta admin, descomenta y úsala en la vista
        ];

        // Asunto
        $subject = 'Nueva reserva: ' . $order->code;
        if (!empty($data['rifaTitle'])) {
            $subject .= ' • ' . $data['rifaTitle'];
        }

        return $this->subject($subject)
            ->from(
                $tenant->from_email ?? config('mail.from.address'),
                $tenant->from_name  ?? config('mail.from.name')
            )
            ->view('emails.orders.reserved-admin')
            ->with($data + [
                // Compat: si tu Blade espera 'order' y 'url'
                'order'      => $order,
                'url'        => $checkoutUrl,
                'footerGifs' => $this->footerGifs,
            ]);
    }
}
