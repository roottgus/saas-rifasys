<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// >>> Añadidos para Filament Tenancy <<<
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ================================
    //  Filament Tenancy (requerido)
    // ================================

    /** Relación many-to-many: usuarios ↔ tenants (tabla pivote tenant_user) */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Tenant::class, 'tenant_user');
    }

    /** ¿Puede este usuario acceder a un panel Filament? (aquí, sí) */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /** Lista de tenants a los que el usuario tiene acceso (para Filament) */
    public function getTenants(Panel $panel): \Illuminate\Support\Collection
    {
        return $this->tenants()->get();
    }

    /** ¿Puede acceder a un tenant específico? (verifica relación en pivote) */
    public function canAccessTenant(EloquentModel $tenant): bool
    {
        return $this->tenants()->whereKey($tenant->getKey())->exists();
    }
}
