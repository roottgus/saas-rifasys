<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\NumeroEstado;

class RifaNumero extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id','rifa_id','numero','estado','reservado_hasta'];

    protected $casts = [
        'estado'          => NumeroEstado::class,
        'reservado_hasta' => 'datetime',
    ];

    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class);
    }
}
