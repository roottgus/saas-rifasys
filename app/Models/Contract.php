<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Contract extends Model
{
    protected $fillable = [
        'tenant_id',
        'contract_number',
        'client_name',
        'client_id_number',
        'client_email',
        'client_phone',
        'client_address',
        'raffle_name',
        'status',
        'signed_at',
        'disclaimer_accepted_text',
        'disclaimer_accepted_at',
        'disclaimer_accepted_ip',
        'file_path',
        'uuid',

        // CAMPOS PARA FIRMA MANUSCRITA
        'signature_image_path',  // <- ruta del PNG
        'signature_name',        // <- nombre del firmante
        'signature_signed_at',   // <- fecha/hora de firma
        'signature_ip',          // <- ip de quien firmó
        'signature_ua',          // <- user-agent (dispositivo)
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'disclaimer_accepted_at' => 'datetime',
        'signature_signed_at' => 'datetime', // <- para las fechas de firma
    ];

    protected static function booted()
    {
        static::creating(function ($contract) {
            // Genera un uuid único si no existe
            if (!$contract->uuid) {
                $contract->uuid = Str::uuid();
            }
            // Genera un número de contrato único si no existe
            if (!$contract->contract_number) {
                $year = now()->format('Y');
                $lastId = self::max('id') + 1;
                $contract->contract_number = "RS-CON-{$year}-" . str_pad($lastId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ContractDocument::class);
    }
}
