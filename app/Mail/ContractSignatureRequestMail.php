<?php

namespace App\Mail;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractSignatureRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    public function build()
    {
        $link = url('/contrato/firma/' . $this->contract->uuid);

        return $this->subject('Firma tu Contrato de Servicio | Rifasys')
            ->markdown('emails.contract_signature_request', [
                'contract' => $this->contract,
                'link' => $link,
            ]);
    }
}
