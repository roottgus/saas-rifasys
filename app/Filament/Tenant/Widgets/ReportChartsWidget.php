<?php

namespace App\Filament\Tenant\Widgets;

use App\Models\Order;
use App\Models\RifaNumero;
use App\Models\Tenant;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportChartsWidget extends Widget
{
    protected static string $view = 'filament.tenant.widgets.report-charts-widget';

    protected array|string|int $columnSpan = 2;

    // Estados que cuentan como “pagado” (normaliza a minúsculas)
    private array $PAID = ['paid','pagada','aprobado','aprobada','verificado','verificada','completed','completado','confirmado'];

    // Estados que cuentan como “vendido”
    private array $NUM_VENDIDOS = ['vendido','pagado','paid'];

    protected function getViewData(): array
    {
        // 1) Detectar tenant robustamente
        $routeTenant = request()->route('tenant');
        $tenant = ($routeTenant instanceof Tenant)
            ? $routeTenant
            : (auth()->user()?->currentTenant ?? null);

        $tenantId = $tenant?->id;

        // Helper para comparar status case-insensitive
        $paid = $this->PAID;
        $paidLower = array_map('mb_strtolower', $paid);

        if (!$tenantId) {
            return [
                'ventasDiaLabels'      => [],
                'ventasDiaData'        => [],
                'vendResLabels'        => ['Vendidos', 'Reservados'],
                'vendResData'          => [0, 0],
                'ingresosMetodoLabels' => [],
                'ingresosMetodoData'   => [],
                'ordenesSemanaLabels'  => ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
                'ordenesSemanaData'    => [0,0,0,0,0,0,0],
            ];
        }

        // ————— Ajusta el rango amplio (últimos 365 días) para que aparezcan datos si tus órdenes son viejas
        $desde = Carbon::now()->subDays(364)->startOfDay();

        // 2) Ventas por día (últimos 7 días)
        $dias = collect(range(0, 6))->map(fn($i) => Carbon::now()->subDays(6 - $i)->format('Y-m-d'));

        $ventasDiaRaw = Order::select(DB::raw('DATE(created_at) as fecha'), DB::raw('SUM(total_amount) as total'))
            ->where('tenant_id', $tenantId)
            ->whereRaw('LOWER(status) IN ("'.implode('","', $paidLower).'")') // case-insensitive
            ->whereDate('created_at', '>=', $dias->first())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'fecha');

        $ventasDiaLabels = $dias->map(fn($d) => Carbon::parse($d)->isoFormat('ddd D'))->toArray();
        $ventasDiaData   = $dias->map(fn($d) => (float) ($ventasDiaRaw[$d] ?? 0))->toArray();

        // 3) Vendidos vs Reservados (globales)
        $vendidos   = RifaNumero::where('tenant_id', $tenantId)
                        ->whereIn('estado', $this->NUM_VENDIDOS)
                        ->count();

        $reservados = RifaNumero::where('tenant_id', $tenantId)
                        ->where('estado', 'reservado')
                        ->count();

        $vendResLabels = ['Vendidos', 'Reservados'];
        $vendResData   = [$vendidos, $reservados];

        // 4) Ingresos por método de pago (últimos 365 días)
        $ingresosMetodoRaw = Order::select('payment_method', DB::raw('SUM(total_amount) as total'))
            ->where('tenant_id', $tenantId)
            ->whereRaw('LOWER(status) IN ("'.implode('","', $paidLower).'")')
            ->whereDate('created_at', '>=', $desde)
            ->groupBy('payment_method')
            ->get();

        $ingresosMetodoLabels = $ingresosMetodoRaw->pluck('payment_method')
            ->map(fn($v) => $v ?: '(sin método)')
            ->toArray();

        $ingresosMetodoData   = $ingresosMetodoRaw->pluck('total')
            ->map(fn($v) => (float) $v)
            ->toArray();

        // 5) Órdenes pagadas por día de semana (últimos 365 días)
        $ordenesSemanaRaw = Order::select(DB::raw('DAYOFWEEK(created_at) as dow'), DB::raw('COUNT(*) as n'))
            ->where('tenant_id', $tenantId)
            ->whereRaw('LOWER(status) IN ("'.implode('","', $paidLower).'")')
            ->whereDate('created_at', '>=', $desde)
            ->groupBy(DB::raw('DAYOFWEEK(created_at)'))
            ->pluck('n', 'dow');

        $ordenesSemanaLabels = ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];
        $ordenesSemanaData   = collect(range(1,7))->map(fn($i) => (int) ($ordenesSemanaRaw[$i] ?? 0))->toArray();

        return compact(
            'ventasDiaLabels','ventasDiaData',
            'vendResLabels','vendResData',
            'ingresosMetodoLabels','ingresosMetodoData',
            'ordenesSemanaLabels','ordenesSemanaData'
        );
    }
}
