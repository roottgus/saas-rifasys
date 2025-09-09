<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeSetting extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'banner_path',
        'titulo',
        'subtitulo',
        'descripcion',
        'cta_label',
        'countdown_at',
        'time_zone',
    ];

    protected $casts = [
        'countdown_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** Zona horaria efectiva (cae a app.timezone si no hay una configurada) */
    public function getEffectiveTimeZoneAttribute(): string
    {
        return $this->time_zone ?: config('app.timezone');
    }

    /** Fecha/hora del contador en ISO-8601 usando la zona horaria efectiva */
    public function getCountdownIsoAttribute(): ?string
    {
        if (! $this->countdown_at) {
            return null;
        }

        return $this->countdown_at
            ->clone()
            ->timezone($this->effective_time_zone)
            ->toIso8601String();
    }
}
