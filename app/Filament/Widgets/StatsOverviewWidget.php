<?php
// ============================================
// Widget 1: StatsOverviewWidget.php
// Ubicación: app/Filament/Widgets/StatsOverviewWidget.php
// ============================================

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\HomeSetting;
use Carbon\Carbon;
use Illuminate\Support\Number;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = false;
    
    protected int | string | array $columnSpan = 'full';
    
    protected function getStats(): array
    {
        // Aquí debes ajustar según tus modelos reales
        $totalParticipants = User::count(); // O el modelo que uses para participantes
        $ticketsSold = 1250; // Reemplaza con tu lógica real
        $totalRevenue = 45750; // Reemplaza con tu lógica real
        $daysUntilDraw = $this->getDaysUntilDraw();
        
        return [
            Stat::make('Participantes Totales', Number::format($totalParticipants))
                ->description('32 nuevos esta semana')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 12, 10, 15, 20, 18, 25])
                ->extraAttributes([
                    'class' => 'cursor-pointer transition-all hover:ring-2 hover:ring-primary-500',
                ]),
            
            Stat::make('Números Vendidos', Number::format($ticketsSold))
                ->description('75% del total disponible')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('info')
                ->chart([65, 70, 73, 75, 78, 80, 75])
                ->extraAttributes([
                    'class' => 'cursor-pointer transition-all hover:ring-2 hover:ring-info-500',
                ]),
            
            Stat::make('Ingresos Totales', '$' . Number::format($totalRevenue))
                ->description('Meta: $60,000')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning')
                ->chart([30, 35, 40, 42, 45, 43, 45])
                ->extraAttributes([
                    'class' => 'cursor-pointer transition-all hover:ring-2 hover:ring-warning-500',
                ]),
            
            Stat::make('Días para el Sorteo', $daysUntilDraw['days'])
                ->description($daysUntilDraw['description'])
                ->descriptionIcon($daysUntilDraw['icon'])
                ->color($daysUntilDraw['color'])
                ->chart([1, 1, 1, 1, 1, 1, 1])
                ->extraAttributes([
                    'class' => 'cursor-pointer transition-all hover:ring-2 hover:ring-danger-500',
                ]),
        ];
    }
    
    private function getDaysUntilDraw(): array
    {
        $homeSetting = HomeSetting::first();
        
        if (!$homeSetting || !$homeSetting->countdown_at) {
            return [
                'days' => 'Sin definir',
                'description' => 'Configura la fecha del sorteo',
                'icon' => 'heroicon-m-exclamation-triangle',
                'color' => 'gray'
            ];
        }
        
        $drawDate = Carbon::parse($homeSetting->countdown_at, $homeSetting->time_zone);
        $now = Carbon::now($homeSetting->time_zone);
        
        if ($drawDate->isPast()) {
            return [
                'days' => 'Finalizado',
                'description' => 'El sorteo ha concluido',
                'icon' => 'heroicon-m-check-circle',
                'color' => 'success'
            ];
        }
        
        $diff = $now->diffInDays($drawDate);
        
        return [
            'days' => $diff,
            'description' => $drawDate->format('d/m/Y H:i'),
            'icon' => 'heroicon-m-clock',
            'color' => $diff <= 3 ? 'danger' : 'primary'
        ];
    }
}