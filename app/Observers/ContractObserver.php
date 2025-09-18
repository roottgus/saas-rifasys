<?php

namespace App\Observers;

use App\Models\Contract;
use Illuminate\Support\Str;

class ContractObserver
{
    public function creating(Contract $contract)
    {
        // Autogenera el UUID único si no existe
        if (empty($contract->uuid)) {
            $contract->uuid = (string) Str::uuid();
        }

        // Autogenera el número de contrato incremental y seguro
        if (empty($contract->contract_number)) {
            // Busca el último número usado y suma 1
            $lastNumber = Contract::max('id') ?? 0;
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $contract->contract_number = 'RS-CON-' . $nextNumber;
        }

        // Siempre status "pending" al crear el contrato
        $contract->status = 'pending';
    }
}
