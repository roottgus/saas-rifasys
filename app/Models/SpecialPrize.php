<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialPrize extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'rifa_id',
        'title',           // Premio especial
        'lottery_name',    // Lotería
        'lottery_type',    // Tipo (opcional)
        'draw_at',         // Fecha y hora del sorteo
        'description',     // Descripción (opcional)
        'image_path',      // Imagen (opcional)
        'prize_value',     // Monto/valor estimado (opcional)
    ];

    protected $casts = [
        'draw_at'     => 'datetime',
        'prize_value' => 'decimal:2',
    ];

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function rifa(): BelongsTo   { return $this->belongsTo(Rifa::class); }
}
