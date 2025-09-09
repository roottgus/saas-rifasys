<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function build()
    {
        // Cargar relaciones necesarias
        $order = $this->order->loadMissing(['tenant', 'rifa', 'items', 'paymentAccount']);
        $tenant = $order->tenant;
        
        // URL del verificador
        $verifyUrl = url('/t/'.($tenant->slug ?? $tenant->id).'/verify?code='.$order->code);

        // --- FOOTER GIFS ----
        $footerGifs = [];
        $gifCidMap  = [];
        foreach (['qr.gif', 'factura.gif', 'check.gif'] as $f) {
            $p = storage_path('app/public/email/' . $f);
            if (is_file($p)) {
                $id = 'gif-' . pathinfo($p, PATHINFO_FILENAME) . '-' . uniqid();
                $gifCidMap[] = ['id' => $id, 'path' => $p];
                $footerGifs[] = 'cid:' . $id;
            }
        }

        // Embeber GIFs usando withSymfonyMessage
        $this->withSymfonyMessage(function ($message) use ($gifCidMap) {
            foreach ($gifCidMap as $row) {
                $message->embedFromPath($row['path'], $row['id'], 'image/gif');
            }
        });

        return $this->subject('Pago recibido — '.$order->code)
            ->from(
                $tenant->from_email ?? config('mail.from.address'),
                $tenant->from_name  ?? config('mail.from.name')
            )
            ->view('emails.payment-submitted')
            ->with([
                'order'       => $order,
                'tenant'      => $tenant,
                'verifyUrl'   => $verifyUrl,
                // Layout vars
                'title'       => '¡Pago recibido!',
                'subtitle'    => 'Hemos recibido tu comprobante',
                'preheader'   => 'Tu pago está en verificación',
                'badge'       => [
                    'text' => 'Pago en revisión',
                    'bg' => '#fef9c3',
                    'color' => '#b45309'
                ],
                'logoUrl'     => null,
                'footerGifs'  => $footerGifs, // <-- PASA EL ARRAY LOCAL!
                'footerText'  => 'Powered by Rifasys • Sistema 100% seguro',
            ]);
    }
}
