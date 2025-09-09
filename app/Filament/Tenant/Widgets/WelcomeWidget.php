<?php

// ============================================
// Widget 1: WelcomeWidget.php
// Ubicación: app/Filament/Tenant/Widgets/WelcomeWidget.php
// ============================================

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends Widget
{
    protected static ?int $sort = 0;
    protected static string $view = 'filament.tenant.widgets.welcome';
    protected int | string | array $columnSpan = 'full';
    
    protected function getViewData(): array
    {
        $user = Auth::user();
        $tenant = $user->currentTenant;
        
        // Determinar saludo según la hora
        $hour = now()->hour;
        $greeting = match(true) {
            $hour < 12 => 'Buenos días',
            $hour < 19 => 'Buenas tardes',
            default => 'Buenas noches',
        };
        
        return [
            'greeting' => $greeting,
            'userName' => $user->name,
            'tenantName' => $tenant->name,
            'lastLogin' => $user->last_login_at?->diffForHumans() ?? 'Primera vez',
            'plan' => $tenant->plan ?? 'Básico', // Ajusta según tu modelo
            'daysActive' => $tenant->created_at->diffInDays(now()),
        ];
    }
}