<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use Filament\Resources\Pages\CreateRecord;
use App\Mail\ContractSignatureRequestMail;
use Illuminate\Support\Facades\Mail;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    /**
     * Este método se ejecuta justo después de crear el contrato.
     */
    protected function afterCreate(): void
    {
        $contract = $this->record;

        try {
            Mail::to($contract->client_email)
                ->send(new ContractSignatureRequestMail($contract));
        } catch (\Throwable $e) {
            // Puedes registrar el error si lo deseas, pero no detiene el flujo
            // \Log::error('Error enviando email de firma de contrato: ' . $e->getMessage());
        }
    }
}
