<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class PaymentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $verifyUrl;
    public $customerName;
    public $rifa;
    public $rejectReason;

    public function __construct(Order $order, string $verifyUrl, ?string $rejectReason = null)
    {
        $this->order        = $order;
        $this->verifyUrl    = $verifyUrl;
        $this->rifa         = $order->rifa;
        $this->customerName = $order->customer_name;
        $this->rejectReason = $rejectReason;
    }

    public function build()
    {
        return $this->subject('Pago rechazado – Orden ' . $this->order->code)
            ->view('emails.payment-rejected');
    }
}
