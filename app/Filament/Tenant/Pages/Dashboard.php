<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Panel Principal'; // Nombre del menú
    protected static ?string $title = 'Dashboard Principal';            // Título grande arriba

    // Si el header no cambia, fuerza el método getTitle():
    public function getTitle(): string
    {
        return static::$title ?? 'Panel Principal';
    }
}
