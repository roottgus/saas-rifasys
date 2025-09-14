<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Filament Tenancy
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// Spatie Permission
use Spatie\Permission\Traits\HasRoles;

// (opcional, solo para tipar y usar abajo)
use App\Models\Tenant;
use Illuminate\Support\Collection;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, Notifiable, HasRoles;

    // Asegura el guard correcto para permisos/roles
    protected string $guard_name = 'web';

    /** @var list<string> */
    protected $fillable = ['name','email','password'];

    /** @var list<string> */
    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ================================
    //  Filament Tenancy
    // ================================
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user');
    }

    /**
     * Controla acceso por panel:
     * - admin: solo super_admin
     * - tenant: super_admin / tenant_admin / tenant_demo
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin'  => $this->hasRole('super_admin'),

            'tenant' => $this->hasRole('super_admin') // super_admin entra siempre
                || (
                    $this->hasAnyRole(['tenant_admin', 'tenant_demo'])
                    && $this->tenants()->exists()       // debe pertenecer a algún tenant
                ),

            default  => false,
        };
    }

    /**
     * Lista de tenants accesibles:
     * - super_admin ve TODOS los tenants en el panel tenant
     * - resto: solo los vinculados por pivote
     *
     * @return \Illuminate\Support\Collection<int, \App\Models\Tenant>
     */
    public function getTenants(Panel $panel): Collection
    {
        if ($panel->getId() === 'tenant' && $this->hasRole('super_admin')) {
            return Tenant::query()->orderBy('name')->get();
        }

        return $this->tenants()->get();
    }

    /**
     * ¿Puede acceder a un tenant específico?
     * - super_admin: sí
     * - resto: solo si está vinculado
     */
    public function canAccessTenant(EloquentModel $tenant): bool
    {
        if ($this->hasRole('super_admin')) {
            return true;
        }

        return $this->tenants()->whereKey($tenant->getKey())->exists();
    }
}
