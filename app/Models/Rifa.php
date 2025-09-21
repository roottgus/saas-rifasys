<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rifa extends Model
{
    use BelongsToTenant;

    protected $fillable = [
    'tenant_id',
    'titulo',
    'slug',
    'banner_path',
    'descripcion',
    'precio',
    'total_numeros',
    'min_por_compra',
    'max_por_compra',
    'estado',
    'starts_at',
    'ends_at',
    'lottery_name',
    'lottery_type',
    'draw_at',
    'external_draw_ref',
    'bg_color',
    'bg_image_path',
    'is_edit_locked',
    'quick_selections',
];


    protected $casts = [
        'precio'     => 'decimal:2',
        'starts_at'  => 'datetime',
        'ends_at'    => 'datetime',
        'draw_at'    => 'datetime',
        'is_edit_locked' => 'boolean',
        'quick_selections' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function numeros(): HasMany
    {
        return $this->hasMany(RifaNumero::class);
    }

    public function specialPrizes(): HasMany
{
    return $this->hasMany(SpecialPrize::class)->orderBy('draw_at')->orderBy('id');
}


    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
