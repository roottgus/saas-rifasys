<?php

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Filament\Facades\Filament;

class TenantStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    
    protected function getStats(): array
    {
        $tenant = Filament::getTenant();
        if (!$tenant) {
            return [
                Stat::make('Sin datos', 'N/A')
                    ->description('Seleccione un negocio')
                    ->color('gray'),
            ];
        }

        // Rifas activas
        $rifasActivas = \App\Models\Rifa::where('tenant_id', $tenant->id)
            ->where('estado', 'activa')
            ->count();

        // Números vendidos: estado 'pagado' (tu enum real)
        $numerosVendidos = \App\Models\RifaNumero::whereHas('rifa', function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id);
        })->where('estado', 'pagado')->count();

        // Ingresos del mes actual: status 'paid' (tu enum real)
        $ingresosMes = \App\Models\Order::where('tenant_id', $tenant->id)
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Participantes únicos: teléfonos distintos en órdenes pagadas
        $participantes = \App\Models\Order::where('tenant_id', $tenant->id)
            ->where('status', 'paid')
            ->distinct('customer_phone')
            ->count('customer_phone');

        return [
            Stat::make('Rifas Activas', $rifasActivas)
                ->description('Activas ahora mismo')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary')
                ->chart([0, 0, 0, 0, 0, 0, $rifasActivas]),

            Stat::make('Números Vendidos', Number::format($numerosVendidos))
                ->description($this->getPorcentajeVendidos($tenant, $numerosVendidos))
                ->descriptionIcon('heroicon-m-ticket')
                ->color('success')
                ->chart([0, 0, 0, 0, 0, 0, $numerosVendidos]),

            Stat::make('Ingresos del Mes', '$' . Number::format($ingresosMes, 2))
                ->description($this->getCrecimientoIngresosMes($tenant))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning')
                ->chart([0, 0, 0, 0, 0, 0, $ingresosMes]),

            Stat::make('Participantes', Number::format($participantes))
                ->description($this->getNuevosParticipantesSemana($tenant))
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->chart([0, 0, 0, 0, 0, 0, $participantes]),
        ];
    }

    /**
     * Porcentaje de números vendidos en todas las rifas del tenant
     */
    private function getPorcentajeVendidos($tenant, $numerosVendidos): string
    {
        $totalNumeros = \App\Models\RifaNumero::whereHas('rifa', function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id);
        })->count();

        $porcentaje = $totalNumeros > 0 ? round(($numerosVendidos / $totalNumeros) * 100) : 0;
        return "{$porcentaje}% de capacidad";
    }

    /**
     * Crecimiento de ingresos contra el mes anterior (status 'paid')
     */
    private function getCrecimientoIngresosMes($tenant): string
    {
        $mesActual = now()->month;
        $anioActual = now()->year;
        $mesAnterior = $mesActual == 1 ? 12 : $mesActual - 1;
        $anioAnterior = $mesActual == 1 ? $anioActual - 1 : $anioActual;

        $actual = \App\Models\Order::where('tenant_id', $tenant->id)
            ->where('status', 'paid')
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $anioActual)
            ->sum('total_amount');

        $anterior = \App\Models\Order::where('tenant_id', $tenant->id)
            ->where('status', 'paid')
            ->whereMonth('created_at', $mesAnterior)
            ->whereYear('created_at', $anioAnterior)
            ->sum('total_amount');

        if ($anterior == 0) return 'Sin historial';
        $variacion = round((($actual - $anterior) / $anterior) * 100);
        $icon = $variacion >= 0 ? '+' : '';
        return "{$icon}{$variacion}% vs mes anterior";
    }

    /**
     * Nuevos participantes esta semana (por teléfono, status 'paid')
     */
    private function getNuevosParticipantesSemana($tenant): string
    {
        $inicioSemana = now()->startOfWeek();
        $finSemana = now()->endOfWeek();

        $nuevos = \App\Models\Order::where('tenant_id', $tenant->id)
            ->where('status', 'paid')
            ->whereBetween('created_at', [$inicioSemana, $finSemana])
            ->distinct('customer_phone')
            ->count('customer_phone');

        return "$nuevos nuevos esta semana";
    }
}
