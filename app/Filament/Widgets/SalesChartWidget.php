<?php
// ============================================
// Ubicación: app/Filament/Widgets/SalesChartWidget.php
// ============================================

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class SalesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Ventas de Números - Últimos 30 días';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';
    
    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
            ],
            'tooltip' => [
                'enabled' => true,
                'mode' => 'index',
                'intersect' => false,
            ],
        ],
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'grid' => [
                    'display' => true,
                    'color' => 'rgba(0, 0, 0, 0.05)',
                ],
            ],
            'x' => [
                'grid' => [
                    'display' => false,
                ],
            ],
        ],
        'animation' => [
            'duration' => 1000,
        ],
    ];
    
    protected function getData(): array
    {
        // Datos de ejemplo - reemplaza con tu lógica real
        $data = collect(range(1, 30))->map(function ($day) {
            return [
                'day' => Carbon::now()->subDays(30 - $day)->format('d/m'),
                'ventas' => rand(20, 100),
                'meta' => 80,
            ];
        });
        
        return [
            'datasets' => [
                [
                    'label' => 'Números Vendidos',
                    'data' => $data->pluck('ventas')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
                [
                    'label' => 'Meta Diaria',
                    'data' => $data->pluck('meta')->toArray(),
                    'borderColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderWidth' => 2,
                    'borderDash' => [5, 5],
                    'tension' => 0,
                    'fill' => false,
                    'pointRadius' => 0,
                ],
            ],
            'labels' => $data->pluck('day')->toArray(),
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getFilters(): ?array
    {
        return [
            'week' => 'Última semana',
            'month' => 'Último mes',
            'quarter' => 'Último trimestre',
            'year' => 'Último año',
        ];
    }
}