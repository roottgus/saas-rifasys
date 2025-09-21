<?php

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Análisis de Ventas - Últimos 7 Días';
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = [
        'lg' => 2,
        'xl' => 3,
    ];
    
    protected static ?string $maxHeight = '300px';
    
    // Polling para actualización en tiempo real (opcional)
    protected static ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        // Obtener datos de ventas
        $salesData = $this->getSalesData();
        
        // Preparar datos para el gráfico
        $labels = [];
        $currentWeekData = [];
        $previousWeekData = [];
        
        foreach ($salesData as $sale) {
            $labels[] = $sale['label'];
            $currentWeekData[] = $sale['current'];
            $previousWeekData[] = $sale['previous'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Esta Semana',
                    'data' => $currentWeekData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.08)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2.5,
                    'tension' => 0.3,
                    'fill' => true,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointHoverBackgroundColor' => 'rgb(37, 99, 235)',
                    'pointHoverBorderColor' => '#ffffff',
                    'pointHoverBorderWidth' => 3,
                ],
                [
                    'label' => 'Semana Anterior',
                    'data' => $previousWeekData,
                    'backgroundColor' => 'transparent',
                    'borderColor' => 'rgba(156, 163, 175, 0.5)',
                    'borderWidth' => 2,
                    'tension' => 0.3,
                    'fill' => false,
                    'pointRadius' => 3,
                    'pointHoverRadius' => 5,
                    'pointBackgroundColor' => 'rgb(156, 163, 175)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'borderDash' => [5, 5],
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getSalesData(): array
    {
        // Intentar obtener datos reales de la base de datos
        if ($this->shouldUseRealData()) {
            try {
                return $this->getRealSalesData();
            } catch (\Exception $e) {
                // Si hay error, usar datos de demostración
                return $this->getDemoSalesData();
            }
        }
        
        // Por defecto, usar datos de demostración
        return $this->getDemoSalesData();
    }
    
    protected function shouldUseRealData(): bool
    {
        // Verificar si la tabla 'orders' existe
        try {
            return DB::getSchemaBuilder()->hasTable('orders');
        } catch (\Exception $e) {
            return false;
        }
    }
    
    protected function getRealSalesData(): array
    {
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $previousDate = Carbon::now()->subDays($i + 7);
            
            // Ventas del día actual
            $currentSales = DB::table('orders')
                ->whereDate('created_at', $date->toDateString())
                ->whereIn('status', ['completed', 'processing', 'shipped'])
                ->sum('total_amount') ?? 0;
            
            // Ventas del mismo día la semana anterior
            $previousSales = DB::table('orders')
                ->whereDate('created_at', $previousDate->toDateString())
                ->whereIn('status', ['completed', 'processing', 'shipped'])
                ->sum('total_amount') ?? 0;
            
            $data[] = [
                'label' => $date->format('D'),
                'date' => $date->format('d M'),
                'current' => round($currentSales, 2),
                'previous' => round($previousSales, 2),
            ];
        }
        
        return $data;
    }
    
    protected function getDemoSalesData(): array
    {
        $data = [];
        $days = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayIndex = $date->dayOfWeek === 0 ? 6 : $date->dayOfWeek - 1;
            
            // Generar datos más realistas
            $baseAmount = 1500;
            $variation = rand(-400, 600);
            
            // Más ventas los viernes y sábados
            if ($date->dayOfWeek == 5 || $date->dayOfWeek == 6) {
                $variation += rand(300, 500);
            }
            
            // Lunes típicamente más bajo
            if ($date->dayOfWeek == 1) {
                $variation -= rand(200, 400);
            }
            
            $currentAmount = max(500, $baseAmount + $variation);
            $previousAmount = max(400, $baseAmount + rand(-300, 400));
            
            $data[] = [
                'label' => $days[$dayIndex],
                'date' => $date->format('d M'),
                'current' => $currentAmount,
                'previous' => $previousAmount,
            ];
        }
        
        return $data;
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'align' => 'end',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 15,
                        'font' => [
                            'size' => 11,
                        ],
                        'color' => '#6b7280'
                    ]
                ],
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                    'backgroundColor' => 'rgba(17, 24, 39, 0.95)',
                    'titleColor' => '#f3f4f6',
                    'bodyColor' => '#e5e7eb',
                    'borderColor' => 'rgba(229, 231, 235, 0.1)',
                    'borderWidth' => 1,
                    'padding' => 12,
                    'bodySpacing' => 8,
                    'titleSpacing' => 6,
                    'cornerRadius' => 8,
                    'titleFont' => [
                        'size' => 13,
                        'weight' => '600',
                    ],
                    'bodyFont' => [
                        'size' => 12,
                        'weight' => '400',
                    ],
                    'displayColors' => true,
                    'boxWidth' => 8,
                    'boxHeight' => 8,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(156, 163, 175, 0.08)',
                        'drawBorder' => false,
                    ],
                    'border' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'color' => '#9ca3af',
                        'padding' => 8,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                        'drawBorder' => false,
                    ],
                    'border' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'color' => '#6b7280',
                        'padding' => 8,
                    ],
                ],
            ],
            'animation' => [
                'duration' => 750,
                'easing' => 'easeInOutCubic',
            ],
        ];
    }
    
    // Sobrescribir el método para agregar información extra
    public function getHeading(): ?string
    {
        $salesData = $this->getSalesData();
        $totalCurrent = array_sum(array_column($salesData, 'current'));
        $totalPrevious = array_sum(array_column($salesData, 'previous'));
        
        $formatted = '$' . number_format($totalCurrent, 2);
        
        return "Análisis de Ventas - Últimos 7 Días ({$formatted})";
    }
    
    public function getDescription(): ?string
    {
        $salesData = $this->getSalesData();
        $totalCurrent = array_sum(array_column($salesData, 'current'));
        $totalPrevious = array_sum(array_column($salesData, 'previous'));
        
        if ($totalPrevious > 0) {
            $percentageChange = (($totalCurrent - $totalPrevious) / $totalPrevious) * 100;
        } else {
            $percentageChange = 0;
        }
        
        $icon = $percentageChange >= 0 ? '↑' : '↓';
        $class = $percentageChange >= 0 ? 'success' : 'danger';
        
        return sprintf(
            '<span class="text-%s">%s %.1f%% vs semana anterior</span>',
            $class,
            $icon,
            abs($percentageChange)
        );
    }
}