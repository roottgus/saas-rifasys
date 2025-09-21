<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pais',
        'moneda_principal',
        'tasa_bs',
        'slug',
        'domain',
        'branding_json',
        'notify_email',
        'status',
        'plan',        // <-- NUEVO
        'max_rifas',   // <-- NUEVO
    ];

    protected $casts = [
        'branding_json' => 'array',
        'tasa_bs'       => 'float',
    ];

    /** Usar el slug en las URLs (ej: /panel/rifasys) */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Mutator para guardar el slug siempre en minúsculas y sin espacios */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = strtolower(str_replace(' ', '-', $value));
    }

    /** === Relaciones de SaaS === */

    /** Usuarios con acceso a este tenant */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_user');
    }

    /** Rifas */
    public function rifas(): HasMany
    {
        return $this->hasMany(Rifa::class);
    }

    /**
     * Contratos de servicio generados para este tenant
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(\App\Models\Contract::class);
    }

    /** === CMS === */
    public function brandSettings(): HasMany
    {
        return $this->hasMany(BrandSetting::class);
    }
    public function homeSettings(): HasMany
    {
        return $this->hasMany(HomeSetting::class);
    }
    public function legalSettings(): HasMany
    {
        return $this->hasMany(LegalSetting::class);
    }
    public function contactSettings(): HasMany
    {
        return $this->hasMany(ContactSetting::class);
    }
    public function faqItems(): HasMany
    {
        return $this->hasMany(FaqItem::class);
    }

    /** Footer personalizado (SOLO UNO por tenant) */
    public function footerSetting(): HasOne
    {
        return $this->hasOne(\App\Models\FooterSetting::class);
    }

    /** Métodos de pago / cuentas */
    public function paymentAccounts(): HasMany
    {
        return $this->hasMany(PaymentAccount::class);
    }

    /** Órdenes */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /** Helpers de branding y configuración */

    public function getPrimaryColorAttribute()
    {
        return $this->branding_json['primary'] ?? '#2563EB';
    }

    public function usaBolivares(): bool
    {
        // Si la moneda principal es VES, se debe mostrar/manejar Bs
        return $this->moneda_principal === 'VES';
    }

    /** === Límite de rifas según plan (pro!) === */
    public function getRifasLimitAttribute()
    {
        // Si hay override manual
        if (!is_null($this->max_rifas)) {
            return $this->max_rifas;
        }

        // Por plan
        return match($this->plan) {
            'plus'    => 1,
            'master'  => 2,
            'premium' => null, // null es ilimitado
            default   => 1,
        };
    }
}
