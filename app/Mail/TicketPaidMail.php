<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage; // <-- Necesario
use Illuminate\Support\Facades\URL;      // <-- Necesario

class TicketPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        // Cargar relaciones necesarias
        $order = $this->order->loadMissing(['tenant', 'rifa', 'items', 'paymentAccount']);
        $tenant = $order->tenant;

        if (!$tenant) {
            \Log::error('Tenant no encontrado para la orden: ' . $order->id);
            throw new \Exception('Tenant no encontrado');
        }

        // LOGO URL ABSOLUTA
        $logoUrl = null;
        if ($tenant && method_exists($tenant, 'brandSettings')) {
            $brand = $tenant->brandSettings()->first();
            if ($brand && $brand->logo_path) {
                $logoUrl = url(Storage::url($brand->logo_path)); // <-- SIEMPRE ABSOLUTA
            }
        }

        // Configuración de remitente
        $fromEmail = $tenant->from_email ?? config('mail.from.address', 'noreply@rifasys.com');
        $fromName = $tenant->from_name ?? ($tenant->name ?? config('mail.from.name', 'Rifasys'));

        // Reply-To
        $replyTo = $tenant->reply_to_email ?? $fromEmail;
        $replyToName = $tenant->reply_to_name ?? $fromName;

        // Datos para la vista
        $viewData = [
            'order' => $order,
            'tenant' => $tenant,
            'verifyUrl' => route('store.verify', [
                'tenant' => $tenant->slug ?? $tenant->id,
                'code' => $order->code,
            ]),
            'title' => 'Pago verificado – ¡Participación confirmada!',
            'subtitle' => 'Hemos verificado tu pago y tus boletos quedaron confirmados para el sorteo.',
            'badge' => [
                'text' => 'Pagado',
                'bg' => '#dcfce7',
                'color' => '#166534'
            ],
            'preheader' => 'Tu pago ha sido confirmado y tus boletos están activos.',
            'footerText' => 'Powered by Rifasys • Sistema 100% seguro',
            'logoUrl' => $logoUrl, // <- AHORA SIEMPRE VIENE ABSOLUTA
        ];

        return $this->subject('¡Tu(s) ticket(s) confirmado(s)! ' . $order->code)
            ->from($fromEmail, $fromName)
            ->replyTo($replyTo, $replyToName)
            ->view('emails.ticket-paid')
            ->with($viewData);
    }
}
