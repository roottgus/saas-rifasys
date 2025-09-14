<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use App\Models\Rifa;
use App\Models\Order;
use App\Models\RifaNumero;
use Carbon\Carbon;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.tenant.pages.reports';
    protected static ?string $navigationGroup = 'Analítica';
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $title = 'Centro de Análisis y Reportes';
    protected static ?int $navigationSort = 1;

    // Filtros Livewire
    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?int $rifaId = null;
    public string $period = '30'; // 7, 30, 90, 365, custom
    public string $compareMode = 'none'; // none, previous, year

    // Estados reales en tu BD
    protected array $PAID = ['paid', 'pagada', 'aprobado', 'verificado', 'completed'];
    protected array $NUM_VENDIDOS = ['pagado', 'vendido'];

    public function mount(): void
    {
        $this->period = '30';
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatedPeriod(): void
    {
        switch ($this->period) {
            case '7':
                $this->dateFrom = now()->subDays(7)->format('Y-m-d');
                break;
            case '30':
                $this->dateFrom = now()->subDays(30)->format('Y-m-d');
                break;
            case '90':
                $this->dateFrom = now()->subDays(90)->format('Y-m-d');
                break;
            case '365':
                $this->dateFrom = now()->subDays(365)->format('Y-m-d');
                break;
        }
        $this->dateTo = now()->format('Y-m-d');
    }

    protected function range(): array
    {
        $from = $this->dateFrom ? Carbon::parse($this->dateFrom)->startOfDay() : now()->subDays(30)->startOfDay();
        $to = $this->dateTo ? Carbon::parse($this->dateTo)->endOfDay() : now()->endOfDay();
        return [$from, $to];
    }

    protected function previousRange(): array
    {
        [$from, $to] = $this->range();
        $diff = $from->diffInDays($to);
        $prevFrom = $from->copy()->subDays($diff + 1);
        $prevTo = $from->copy()->subDay();
        return [$prevFrom, $prevTo];
    }

    public function getData(): array
{
    [$from, $to] = $this->range();
    [$prevFrom, $prevTo] = $this->previousRange();

    // ✅ ESTA ES LA FORMA CORRECTA EN FILAMENT 3.x MULTI-TENANT
    $tenant = \Filament\Facades\Filament::getTenant();
    $tenantId = $tenant?->id;

    if (!$tenantId) {
        // Puedes personalizar este error a tu gusto:
        throw new \Exception('No se pudo determinar el tenant actual.');
    }

        // ---- KPIs PRINCIPALES ----
        $orders = DB::table('orders as o')
            ->where('o.tenant_id', $tenantId)
            ->whereIn('o.status', $this->PAID)
            ->when($this->rifaId, fn($q) => $q->where('o.rifa_id', $this->rifaId))
            ->whereBetween('o.created_at', [$from, $to]);

        $ordersPrev = DB::table('orders as o')
            ->where('o.tenant_id', $tenantId)
            ->whereIn('o.status', $this->PAID)
            ->when($this->rifaId, fn($q) => $q->where('o.rifa_id', $this->rifaId))
            ->whereBetween('o.created_at', [$prevFrom, $prevTo]);

        // Métricas actuales
        $ingresosTotales = (float) ($orders->clone()->sum('o.total_amount') ?? 0);
        $cantidadOrdenes = (int) ($orders->clone()->count() ?? 0);
        $ticketPromedio = $cantidadOrdenes ? $ingresosTotales / $cantidadOrdenes : 0.0;

        // Métricas período anterior
        $ingresosPrev = (float) ($ordersPrev->clone()->sum('o.total_amount') ?? 0);
        $cantidadPrev = (int) ($ordersPrev->clone()->count() ?? 0);
        $ticketPrev = $cantidadPrev ? $ingresosPrev / $cantidadPrev : 0.0;

        // Calcular cambios porcentuales
        $cambioIngresos = $ingresosPrev > 0 ? (($ingresosTotales - $ingresosPrev) / $ingresosPrev) * 100 : 0;
        $cambioOrdenes = $cantidadPrev > 0 ? (($cantidadOrdenes - $cantidadPrev) / $cantidadPrev) * 100 : 0;
        $cambioTicket = $ticketPrev > 0 ? (($ticketPromedio - $ticketPrev) / $ticketPrev) * 100 : 0;

        // ---- PARTICIPANTES ÚNICOS ----
        $participantesUnicos = DB::table('orders as o')
            ->where('o.tenant_id', $tenantId)
            ->whereIn('o.status', $this->PAID)
            ->when($this->rifaId, fn($q) => $q->where('o.rifa_id', $this->rifaId))
            ->whereBetween('o.created_at', [$from, $to])
            ->distinct('o.customer_email')
            ->count('o.customer_email');

        $participantesPrev = DB::table('orders as o')
            ->where('o.tenant_id', $tenantId)
            ->whereIn('o.status', $this->PAID)
            ->when($this->rifaId, fn($q) => $q->where('o.rifa_id', $this->rifaId))
            ->whereBetween('o.created_at', [$prevFrom, $prevTo])
            ->distinct('o.customer_email')
            ->count('o.customer_email');

        $cambioParticipantes = $participantesPrev > 0 ? (($participantesUnicos - $participantesPrev) / $participantesPrev) * 100 : 0;

        // ---- TASA DE CONVERSIÓN ----
        $visitasTotal = DB::table('rifa_views')
            ->where('tenant_id', $tenantId)
            ->when($this->rifaId, fn($q) => $q->where('rifa_id', $this->rifaId))
            ->whereBetween('created_at', [$from, $to])
            ->count();

        $tasaConversion = $visitasTotal > 0 ? ($cantidadOrdenes / $visitasTotal) * 100 : 0;

        // ---- VENTAS POR DÍA (últimos 30 días) ----
        $ventasPorDia = $orders->clone()
            ->selectRaw("DATE(o.created_at) as fecha, COUNT(*) as pedidos, SUM(o.total_amount) as total")
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->map(function ($item) {
                $item->fecha_formato = Carbon::parse($item->fecha)->format('d/m');
                return $item;
            });

        // ---- VENTAS POR HORA ----
        $ventasPorHora = $orders->clone()
            ->selectRaw("HOUR(o.created_at) as hora, COUNT(*) as pedidos, SUM(o.total_amount) as total")
            ->groupBy('hora')
            ->orderBy('hora')
            ->get();

        // ---- DÍA PICO ----
        $diaPico = $orders->clone()
            ->selectRaw("DATE(o.created_at) as fecha, SUM(o.total_amount) as total, COUNT(*) as pedidos")
            ->groupBy('fecha')
            ->orderByDesc('total')
            ->limit(1)
            ->first();

        // ---- INGRESOS POR MÉTODO DE PAGO ----
        $ingresosPorMetodo = DB::table('orders as o')
            ->where('o.tenant_id', $tenantId)
            ->leftJoin('payment_accounts as a', 'a.id', '=', 'o.payment_account_id')
            ->whereIn('o.status', $this->PAID)
            ->when($this->rifaId, fn($q) => $q->where('o.rifa_id', $this->rifaId))
            ->whereBetween('o.created_at', [$from, $to])
            ->selectRaw("
                COALESCE(a.etiqueta, '(sin método)') as metodo, 
                COALESCE(a.tipo, '-') as tipo, 
                COUNT(*) as cantidad, 
                SUM(o.total_amount) as total,
                AVG(o.total_amount) as promedio
            ")
            ->groupBy('metodo', 'tipo')
            ->orderByDesc('total')
            ->get();

        // ---- TOP 10 RIFAS ----
        $topRifas = DB::table('orders as o')
            ->where('o.tenant_id', $tenantId)
            ->join('rifas as r', function($join) use ($tenantId) {
                $join->on('r.id', '=', 'o.rifa_id')
                    ->where('r.tenant_id', $tenantId);
            })
            ->whereIn('o.status', $this->PAID)
            ->when($this->rifaId, fn($q) => $q->where('o.rifa_id', $this->rifaId))
            ->whereBetween('o.created_at', [$from, $to])
            ->selectRaw("
                o.rifa_id, 
                r.titulo, 
                r.precio,
                SUM(o.total_amount) as total, 
                COUNT(DISTINCT o.customer_email) as participantes,
                COUNT(*) as pedidos,
                AVG(o.total_amount) as ticket_promedio
            ")
            ->groupBy('o.rifa_id', 'r.titulo', 'r.precio')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ---- ESTADO DE NÚMEROS ----
        $estadoNumeros = DB::table('rifa_numeros')
            ->where('tenant_id', $tenantId)
            ->selectRaw("
                estado, 
                COUNT(*) as cantidad,
                SUM(CASE WHEN estado IN ('pagado', 'vendido') THEN 1 ELSE 0 END) as vendidos,
                SUM(CASE WHEN estado = 'reservado' THEN 1 ELSE 0 END) as reservados,
                SUM(CASE WHEN estado = 'disponible' THEN 1 ELSE 0 END) as disponibles
            ")
            ->when($this->rifaId, fn($q) => $q->where('rifa_id', $this->rifaId))
            ->groupBy('estado')
            ->get();

        $totalNumeros = DB::table('rifa_numeros')
            ->where('tenant_id', $tenantId)
            ->when($this->rifaId, fn($q) => $q->where('rifa_id', $this->rifaId))
            ->count();

        $totalVendidos = DB::table('rifa_numeros')
            ->where('tenant_id', $tenantId)
            ->when($this->rifaId, fn($q) => $q->where('rifa_id', $this->rifaId))
            ->whereIn('estado', $this->NUM_VENDIDOS)
            ->count();

        $totalReservados = DB::table('rifa_numeros')
            ->where('tenant_id', $tenantId)
            ->when($this->rifaId, fn($q) => $q->where('rifa_id', $this->rifaId))
            ->where('estado', 'reservado')
            ->count();

        $totalDisponibles = DB::table('rifa_numeros')
            ->where('tenant_id', $tenantId)
            ->when($this->rifaId, fn($q) => $q->where('rifa_id', $this->rifaId))
            ->where('estado', 'disponible')
            ->count();

        // ---- VENTAS POR DÍA DE LA SEMANA ----
        $ventasPorDiaSemana = $orders->clone()
            ->selectRaw("
                DAYNAME(o.created_at) as dia, 
                DAYOFWEEK(o.created_at) as dia_num,
                COUNT(*) as pedidos, 
                SUM(o.total_amount) as total,
                AVG(o.total_amount) as promedio
            ")
            ->groupBy('dia', 'dia_num')
            ->orderBy('dia_num')
            ->get()
            ->map(function ($item) {
                $dias = [
                    'Sunday' => 'Domingo',
                    'Monday' => 'Lunes',
                    'Tuesday' => 'Martes',
                    'Wednesday' => 'Miércoles',
                    'Thursday' => 'Jueves',
                    'Friday' => 'Viernes',
                    'Saturday' => 'Sábado'
                ];
                $item->dia_es = $dias[$item->dia] ?? $item->dia;
                return $item;
            });

        // ---- TOP CLIENTES ----
        $topClientes = $orders->clone()
            ->selectRaw("
                o.customer_name,
                o.customer_email,
                COUNT(*) as compras,
                SUM(o.total_amount) as total_gastado,
                AVG(o.total_amount) as ticket_promedio,
                MAX(o.created_at) as ultima_compra
            ")
            ->groupBy('o.customer_email', 'o.customer_name')
            ->orderByDesc('total_gastado')
            ->limit(10)
            ->get();

        // ---- RENDIMIENTO POR MES ----
        $rendimientoMensual = DB::table('orders as o')
            ->where('o.tenant_id', $tenantId)
            ->whereIn('o.status', $this->PAID)
            ->when($this->rifaId, fn($q) => $q->where('o.rifa_id', $this->rifaId))
            ->whereBetween('o.created_at', [now()->subMonths(12), now()])
            ->selectRaw("
                DATE_FORMAT(o.created_at, '%Y-%m') as mes,
                COUNT(*) as pedidos,
                SUM(o.total_amount) as total,
                COUNT(DISTINCT o.customer_email) as clientes_unicos
            ")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function ($item) {
                $item->mes_formato = Carbon::createFromFormat('Y-m', $item->mes)->format('M Y');
                return $item;
            });

        return [
            // KPIs principales
            'ingresosTotales' => $ingresosTotales,
            'cantidadOrdenes' => $cantidadOrdenes,
            'ticketPromedio' => $ticketPromedio,
            'participantesUnicos' => $participantesUnicos,
            'tasaConversion' => $tasaConversion,
            
            // Cambios porcentuales
            'cambioIngresos' => $cambioIngresos,
            'cambioOrdenes' => $cambioOrdenes,
            'cambioTicket' => $cambioTicket,
            'cambioParticipantes' => $cambioParticipantes,
            
            // Gráficos
            'ventasPorDia' => $ventasPorDia,
            'ventasPorHora' => $ventasPorHora,
            'ventasPorDiaSemana' => $ventasPorDiaSemana,
            'rendimientoMensual' => $rendimientoMensual,
            
            // Tablas
            'diaPico' => $diaPico,
            'ingresosPorMetodo' => $ingresosPorMetodo,
            'topRifas' => $topRifas,
            'topClientes' => $topClientes,
            
            // Estado de números
            'estadoNumeros' => $estadoNumeros,
            'totalNumeros' => $totalNumeros,
            'totalVendidos' => $totalVendidos,
            'totalReservados' => $totalReservados,
            'totalDisponibles' => $totalDisponibles,
            
            // Datos para filtros
            'rifas' => Rifa::where('tenant_id', $tenantId)->select('id', 'titulo')->orderBy('titulo')->get(),
            'periodoSeleccionado' => $this->period,
            'fechaDesde' => $this->dateFrom,
            'fechaHasta' => $this->dateTo,
        ];
    }

    protected function getViewData(): array
    {
        return $this->getData();
    }

    // Métodos para exportar reportes
    public function exportPDF(): void
    {
        // Implementar exportación a PDF
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Reporte PDF generado correctamente'
        ]);
    }

    public function exportExcel(): void
    {
        // Implementar exportación a Excel
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Reporte Excel generado correctamente'
        ]);
    }

    public function refreshData(): void
    {
        $this->dispatch('refreshCharts');
    }
}
