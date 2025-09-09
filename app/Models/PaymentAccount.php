<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAccount extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'tipo',
        'etiqueta',
        'banco',
        'titular',
        'documento',
        'numero',
        'iban',
        'email',
        'wallet',
        'red',
        'notes',
        'requiere_voucher',
        'activo',
        'logo',
        'status',
        'orden',
        'usd_enabled',
        'bs_enabled',
        'monedas',
        'tasa_bs',
    ];

    protected $casts = [
        'activo'           => 'boolean',
        'requiere_voucher' => 'boolean',
        'usd_enabled'      => 'boolean',
        'bs_enabled'       => 'boolean',
        'monedas'          => 'array',
        'tasa_bs'          => 'decimal:2',
    ];

    /**
     * Relación con el tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope para cuentas activas
     */
    public function scopeActive($query)
    {
        return $query->where('activo', true)
                     ->where('status', 'activo');
    }

    /**
     * Scope para cuentas que aceptan USD
     */
    public function scopeAcceptsUsd($query)
    {
        return $query->where('usd_enabled', true);
    }

    /**
     * Scope para cuentas que aceptan Bolívares
     */
    public function scopeAcceptsBs($query)
    {
        return $query->where('bs_enabled', true)
                     ->whereNotNull('tasa_bs')
                     ->where('tasa_bs', '>', 0);
    }

    /**
     * Verifica si esta cuenta puede procesar pagos en Bolívares
     */
    public function canProcessBs(): bool
    {
        return (bool) $this->bs_enabled && 
               $this->tasa_bs !== null && 
               $this->tasa_bs > 0;
    }

    /**
     * Verifica si esta cuenta puede procesar pagos en USD
     */
    public function canProcessUsd(): bool
    {
        // Asegurar que siempre retorne un booleano
        return (bool) $this->usd_enabled;
    }

    /**
     * Obtiene las monedas aceptadas como array
     */
    public function getAcceptedCurrencies(): array
    {
        $currencies = [];
        
        if ($this->canProcessUsd()) {
            $currencies[] = 'USD';
        }
        
        if ($this->canProcessBs()) {
            $currencies[] = 'VES';
        }
        
        return $currencies;
    }

    /**
     * Convierte un monto de USD a Bolívares
     */
    public function convertUsdToBs(float $amount): ?float
    {
        if (!$this->canProcessBs()) {
            return null;
        }
        
        return round($amount * $this->tasa_bs, 2);
    }

    /**
     * Formatea el monto en la moneda especificada
     */
    public function formatAmount(float $amount, string $currency = 'USD'): string
    {
        if ($currency === 'VES' || $currency === 'BS') {
            $bsAmount = $this->convertUsdToBs($amount);
            if ($bsAmount === null) {
                return 'N/A';
            }
            return 'Bs. ' . number_format($bsAmount, 2, ',', '.');
        }
        
        return '$' . number_format($amount, 2, '.', ',');
    }

    /**
     * Boot del modelo
     */
    protected static function booted(): void
    {
        // Sincronizar el campo monedas con los flags
        static::saving(function (self $model) {
            // Asegurar que los valores booleanos no sean null
            $model->usd_enabled = (bool) $model->usd_enabled;
            $model->bs_enabled = (bool) $model->bs_enabled;
            
            $monedas = [];
            
            if ($model->usd_enabled) {
                $monedas[] = 'usd';
            }
            
            if ($model->bs_enabled) {
                $monedas[] = 'ves';
            }
            
            $model->monedas = $monedas;
            
            // Si se deshabilita Bs, limpiar la tasa
            if (!$model->bs_enabled) {
                $model->tasa_bs = null;
            }
        });
    }
}