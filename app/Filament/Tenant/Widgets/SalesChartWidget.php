<?php

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
        $data = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::now()->subDays($daysAgo);
            return [
                'date' => $date->format('D'),
                'sales' => rand(500, 2000),
            ];
        });

        return [
            'datasets' => [[
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
            ]],
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
                'legend' => ['display' => false],
                // ❌ sin callbacks ni funciones
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                    'displayColors' => false,
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
                    // ❌ sin ticks.callback
                ],
                'x' => [
                    'grid' => ['display' => false],
                    'ticks' => ['font' => ['size' => 12]],
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
