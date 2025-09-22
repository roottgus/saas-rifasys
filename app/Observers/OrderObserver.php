<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\TicketPaidMail;
use App\Mail\PaymentRejectedMail;
use App\Mail\PaymentSubmittedMail;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function updated(Order $order): void
    {
        // Detectar el valor correcto de status (enum o string)
        $status = is_object($order->status) && property_exists($order->status, 'value')
            ? $order->status->value
            : (string)$order->status;

        // Verifica cambio real de status
        if ($order->wasChanged('status')) {
            $originalStatus = $order->getOriginal('status');
            $verifyUrl = url('/t/' . $order->tenant->slug . '/verify?code=' . $order->code);

            // ----------- Pago enviado (submitted) -----------
            if (in_array($status, ['submitted', 'enviado', 'enviada'])) {
                // Cliente
                if (filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
                    \Log::info('Enviando PaymentSubmittedMail a cliente', [
                        'email' => $order->customer_email,
                        'order' => $order->code,
                        'status' => $status,
                    ]);
                    Mail::to($order->customer_email)
                        ->queue(new PaymentSubmittedMail($order, 'customer'));
                }
                // Admin
                $adminEmail = $order->tenant->notify_email
                    ?? config('mail.admin_address')
                    ?? config('mail.from.address');
                if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                    \Log::info('Enviando PaymentSubmittedMail a admin', [
                        'email' => $adminEmail,
                        'order' => $order->code,
                        'status' => $status,
                    ]);
                    Mail::to($adminEmail)
                        ->queue(new PaymentSubmittedMail($order, 'admin'));
                }
            }

            // ----------- Pago verificado / aprobado -----------
            if (in_array($status, ['verificado', 'paid', 'pagada', 'aprobado', 'completed'])) {
                if (filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
                    \Log::info('Enviando TicketPaidMail a usuario', [
                        'email' => $order->customer_email,
                        'order' => $order->code,
                        'status' => $status
                    ]);
                    Mail::to($order->customer_email)
                        ->queue(new TicketPaidMail($order));
                } else {
                    \Log::warning('No se envió TicketPaidMail: email no válido.', [
                        'email' => $order->customer_email,
                        'order' => $order->code,
                    ]);
                }
            }

            // ----------- Pago rechazado -----------
            if (in_array($status, ['rejected', 'rechazado'])) {
                $rejectReason = $order->reject_reason ?? null;
                if (filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
                    \Log::info('Enviando PaymentRejectedMail a usuario', [
                        'email' => $order->customer_email,
                        'order' => $order->code,
                        'status' => $status
                    ]);
                    Mail::to($order->customer_email)
                        ->queue(new PaymentRejectedMail($order, $verifyUrl, $rejectReason));
                } else {
                    \Log::warning('No se envió PaymentRejectedMail: email no válido.', [
                        'email' => $order->customer_email,
                        'order' => $order->code,
                    ]);
                }
            }
        }
    }
}
