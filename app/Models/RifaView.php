<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RifaView extends Model
{
    use HasFactory;

    protected $table = 'rifa_views';

    protected $fillable = [
        'rifa_id',
        'user_id',
        'ip',
        'user_agent',
    ];

    /**
     * Boot del modelo para auto-asignar tenant_id
     */
    protected static function booted()
    {
        static::creating(function ($view) {
            if (app()->has('currentTenant')) {
                $tenant = app('currentTenant');
                $view->tenant_id = $tenant->id;
            }
        });
    }

    /**
     * Scope para filtrar automáticamente por tenant
     */
    public function scopeForTenant($query)
    {
        if (app()->has('currentTenant')) {
            $tenant = app('currentTenant');
            return $query->where('tenant_id', $tenant->id);
        }
        return $query;
    }

    /**
     * Relación con la rifa
     */
    public function rifa()
    {
        return $this->belongsTo(Rifa::class);
    }

    /**
     * Relación con el usuario (opcional)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
