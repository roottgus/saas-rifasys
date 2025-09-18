<?php

namespace App\Mail;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ContractSignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contract $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    public function build()
    {
        $pdfPath = $this->contract->file_path
            ? Storage::disk('public')->path($this->contract->file_path)
            : null;

        return $this->subject('Contrato de Servicio Rifasys Firmado')
            ->markdown('emails.contract_signed')
            ->with([
                'contract' => $this->contract,
            ])
            ->attach($pdfPath, [
                'as' => 'Contrato-Rifasys-' . $this->contract->contract_number . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
