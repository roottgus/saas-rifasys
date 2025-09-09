<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $footerGifs = [];

    public function __construct(public Order $order) {}

    public function build()
    {
        // Carga relaciones necesarias (ajusta los nombres si difieren)
        $order  = $this->order->loadMissing(['tenant', 'rifa', 'items']);
        $tenant = $order->tenant;

        // Lista de boletos en formato "016, 039, ..."
        $tickets = $order->items->pluck('numero')->sort()->implode(', ');

        // URL para verificar la orden/ticket
        $verifyUrl = route('store.verify', [
            'tenant' => $tenant->slug ?? $tenant->id,
            'code'   => $order->code,
        ]);

        // QR opcional (si tienes simple-qrcode instalado)
        $qrPng = null;
        if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
            $qrPng = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size(440)->margin(1)->generate($verifyUrl);
        }

        // ----------- FOOTER GIFS universal -----------
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
        // ---------------------------------------------

        // Datos para la vista (coinciden con el diseño corporativo que definimos)
        $data = [
            // Branding
            'tenantName'    => $tenant->name ?? config('app.name'),
            'tenantUrl'     => url("/t/{$tenant->slug}"),
            'logoUrl'       => $tenant->logo_url ?? asset('img/logo-mail.png'),
            'primary'       => $tenant->color_primary ?? '#1d4ed8',

            // Orden / Rifa
            'orderCode'     => $order->code,
            'customerName'  => $order->customer_name,
            'customerEmail' => $order->customer_email,
            'rifaTitle'     => optional($order->rifa)->titulo ?? optional($order->rifa)->nombre,
            'tickets'       => $tickets,
            'totalUsd'      => '$' . number_format((float) $order->total_amount, 2),

            // Enlaces
            'verifyUrl'     => $verifyUrl,

            // Binario PNG para usar en el Blade con $message->embedData(...)
            'qrPng'         => $qrPng,

            // GIFs footer
            'footerGifs'    => $this->footerGifs,
        ];

        return $this->subject('¡Tu(s) ticket(s) confirmado(s)! ' . $order->code)
            ->view('emails.ticket-paid')
            ->with($data + [
                'order'      => $order,
                'footerGifs' => $this->footerGifs,
            ]);
    }
}
