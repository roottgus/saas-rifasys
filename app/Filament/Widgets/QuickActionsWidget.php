<?php
// ============================================
// Ubicación: app/Filament/Widgets/QuickActionsWidget.php
// ============================================

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 0;
    protected static string $view = 'filament.widgets.quick-actions';
    protected int | string | array $columnSpan = 'full';
    
    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'Nuevo Participante',
                    'icon' => 'heroicon-o-user-plus',
                    'url' => '/admin/users/create',
                    'color' => 'primary',
                ],
                [
                    'label' => 'Generar Reporte',
                    'icon' => 'heroicon-o-document-chart-bar',
                    'url' => '/admin/reports',
                    'color' => 'success',
                ],
                [
                    'label' => 'Configurar Sorteo',
                    'icon' => 'heroicon-o-cog-6-tooth',
                    'url' => '/admin/home-settings',
                    'color' => 'warning',
                ],
                [
                    'label' => 'Ver Estadísticas',
                    'icon' => 'heroicon-o-chart-pie',
                    'url' => '/admin/analytics',
                    'color' => 'info',
                ],
            ],
        ];
    }
}