<?php

namespace App\Filament\Tenant\Widgets;

use App\Models\Order;
use App\Models\RifaNumero;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;



class ReportKpisWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Protección: tenantId solo si hay usuario y tenant
        $tenantId = auth()->user()?->currentTenant?->id;

        if (!$tenantId) {
            // Devuelve un stat de advertencia, sin explotar el widget
            return [
                Stat::make('Sin tenant activo', 'Sin datos')->color('danger')
            ];
        }

        return [
            Stat::make('Ingresos Totales', '$' . number_format($this->getIngresosTotales($tenantId), 2, '.', ',')) 
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Órdenes Pagadas', number_format($this->getCantidadOrdenes($tenantId)))
                ->icon('heroicon-o-receipt-percent')
                ->color('info'),

            Stat::make('Ticket Promedio', '$' . number_format($this->getTicketPromedio($tenantId), 2, '.', ','))
                ->icon('heroicon-o-currency-dollar')
                ->color('primary'),

            Stat::make('Números Vendidos', number_format($this->getTotalVendidos($tenantId)))
                ->icon('heroicon-o-ticket')
                ->color('warning'),

            Stat::make('Números Reservados', number_format($this->getTotalReservados($tenantId)))
                ->icon('heroicon-o-clock')
                ->color('danger'),
        ];
    }

    // --- Métodos KPI ---
    protected function getIngresosTotales($tenantId): float
    {
        return Order::where('tenant_id', $tenantId)
            ->where('status', 'paid')
            ->sum('total_amount');
    }

    protected function getCantidadOrdenes($tenantId): int
    {
        return Order::where('tenant_id', $tenantId)
            ->where('status', 'paid')
            ->count();
    }

    protected function getTicketPromedio($tenantId): float
    {
        $count = $this->getCantidadOrdenes($tenantId);
        return $count > 0 ? ($this->getIngresosTotales($tenantId) / $count) : 0;
    }

    protected function getTotalVendidos($tenantId): int
    {
        return RifaNumero::where('tenant_id', $tenantId)
            ->where('estado', 'vendido')
            ->count();
    }

    protected function getTotalReservados($tenantId): int
    {
        return RifaNumero::where('tenant_id', $tenantId)
            ->where('estado', 'reservado')
            ->count();
    }
}
