<?php

// ============================================
// Widget 2: TenantStatsWidget.php
// Ubicación: app/Filament/Tenant/Widgets/TenantStatsWidget.php
// ============================================

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class TenantStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    
    protected function getStats(): array
    {
        $tenant = auth()->user()->currentTenant;
        
        // Aquí debes ajustar según tus modelos reales
        // Estos son ejemplos para un sistema de rifas
        
        return [
            Stat::make('Rifas Activas', $this->getActiveRaffles())
                ->description('2 próximas a finalizar')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary')
                ->chart([3, 4, 4, 5, 6, 7, 8])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:ring-2 hover:ring-primary-500 transition-all duration-300',
                ]),
                
            Stat::make('Números Vendidos', Number::format($this->getSoldTickets()))
                ->description('85% de capacidad')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('success')
                ->chart([65, 70, 75, 80, 85, 82, 85])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:ring-2 hover:ring-success-500 transition-all duration-300',
                ]),
                
            Stat::make('Ingresos del Mes', '$' . Number::format($this->getMonthlyRevenue()))
                ->description('+23% vs mes anterior')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning')
                ->chart([20, 25, 30, 35, 40, 45, 52])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:ring-2 hover:ring-warning-500 transition-all duration-300',
                ]),
                
            Stat::make('Participantes', Number::format($this->getParticipants()))
                ->description('48 nuevos esta semana')
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->chart([120, 125, 130, 135, 140, 145, 150])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:ring-2 hover:ring-info-500 transition-all duration-300',
                ]),
        ];
    }
    
    private function getActiveRaffles(): int
    {
        // Reemplaza con tu lógica real
        return 8;
    }
    
    private function getSoldTickets(): int
    {
        // Reemplaza con tu lógica real
        return 3456;
    }
    
    private function getMonthlyRevenue(): float
    {
        // Reemplaza con tu lógica real
        return 12543.50;
    }
    
    private function getParticipants(): int
    {
        // Reemplaza con tu lógica real
        return 567;
    }
}