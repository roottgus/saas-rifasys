<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * 'customer' | 'admin'
     */
    public string $audience;

    public function __construct(Order $order, string $audience = 'customer')
    {
        $this->order    = $order;
        $this->audience = ($audience === 'admin') ? 'admin' : 'customer';
    }

    public function build()
    {
        // 🔑 Cargar relaciones NECESARIAS desde el inicio
        $order  = $this->order->loadMissing(['tenant', 'rifa', 'items', 'paymentAccount']);
        $tenant = $order->tenant;

        $verifyUrl = url('/t/' . ($tenant->slug ?? $tenant->id) . '/verify?code=' . $order->code);
        $isAdmin   = $this->audience === 'admin';

        // (Opcional) Embebidos de gifs del footer
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
        $this->withSymfonyMessage(function ($message) use ($gifCidMap) {
            foreach ($gifCidMap as $row) {
                $message->embedFromPath($row['path'], $row['id'], 'image/gif');
            }
        });

        // Remitente
        $fromEmail = $tenant->from_email ?? config('mail.from.address');
        $fromName  = $tenant->from_name  ?? ($tenant->name ?? config('mail.from.name') ?? config('app.name'));

        // ✔️ Vista y textos por audiencia
        $view = $isAdmin
            ? 'emails.orders.payment-submitted-admin'
            : 'emails.payment-submitted';

        $subject = $isAdmin
            ? "Nuevo pago enviado — {$fromName} — Orden {$order->code}"
            : "Hemos recibido tu pago — Orden {$order->code}";

        $title     = $isAdmin ? 'Nuevo pago enviado'             : '¡Pago recibido!';
        $subtitle  = $isAdmin ? 'Un cliente ha enviado su pago'  : 'Hemos recibido tu comprobante';
        $preheader = $isAdmin ? 'Pago pendiente de verificación' : 'Tu pago está en verificación';
        $badge     = ['text' => 'Pago en revisión', 'bg' => '#fef9c3', 'color' => '#b45309'];

        // (Opcional) Log para auditar qué vista se está usando
        \Log::info('PaymentSubmittedMail build', [
            'order_id' => $order->id,
            'audience' => $this->audience,
            'view'     => $view,
            'to'       => optional($this->to[0] ?? null)['address'] ?? null,
        ]);

        $mail = $this->subject($subject)
            ->from($fromEmail, $fromName)
            ->view($view)
            ->with([
                'order'      => $order,
                'tenant'     => $tenant,
                'verifyUrl'  => $verifyUrl,
                'audience'   => $this->audience,
                // layout vars
                'title'      => $title,
                'subtitle'   => $subtitle,
                'preheader'  => $preheader,
                'badge'      => $badge,
                'logoUrl'    => null,
                'footerGifs' => $footerGifs,
                'footerText' => 'Powered by Rifasys • Sistema 100% seguro',
            ]);

        // ✔️ Para admin: que el Reply-To sea el cliente
        if ($isAdmin && filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
            $mail->replyTo(
                $order->customer_email,
                $order->customer_name ?: $order->customer_email
            );
        }

        return $mail;
    }
}
