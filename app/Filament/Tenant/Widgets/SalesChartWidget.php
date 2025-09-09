<?php

// ============================================
// Widget 4: SalesChartWidget.php
// Ubicación: app/Filament/Tenant/Widgets/SalesChartWidget.php
// ============================================

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class SalesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Ventas de los Últimos 7 Días';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = [
        'lg' => 2,
        'xl' => 3,
    ];
    protected static ?string $maxHeight = '250px';
    
    protected function getData(): array
    {
        // Generar datos de ejemplo para los últimos 7 días
        $data = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::now()->subDays($daysAgo);
            return [
                'date' => $date->format('D'),
                'sales' => rand(500, 2000), // Reemplaza con datos reales
            ];
        });
        
        return [
            'datasets' => [
                [
                    'label' => 'Ventas ($)',
                    'data' => $data->pluck('sales')->toArray(),
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'borderColor' => 'rgb(99, 102, 241)',
                    'borderWidth' => 3,
                    'tension' => 0.4,
                    'fill' => true,
                    'pointRadius' => 5,
                    'pointHoverRadius' => 8,
                    'pointBackgroundColor' => 'rgb(99, 102, 241)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                    'backgroundColor' => 'rgba(17, 24, 39, 0.8)',
                    'titleColor' => '#fff',
                    'bodyColor' => '#fff',
                    'borderColor' => 'rgb(99, 102, 241)',
                    'borderWidth' => 1,
                    'padding' => 12,
                    'displayColors' => false,
                    'callbacks' => [
                        'label' => "function(context) {
                            return '$' + context.parsed.y.toLocaleString();
                        }",
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(156, 163, 175, 0.1)',
                        'drawBorder' => false,
                    ],
                    'ticks' => [
                        'callback' => "function(value) {
                            return '  + value.toLocaleString();
                        }",
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeInOutQuart',
            ],
        ];
    }
} 
