{{-- resources/views/filament/tenant/pages/reports.blade.php --}}
<x-filament-panels::page>
    
    <div class="reports-analytics-container">
        <!-- Header con Filtros -->
        <div class="analytics-header">
            <div class="header-content">
                <div class="header-info">
                    <h1 class="analytics-title">
                        <svg class="title-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Centro de Análisis y Reportes
                    </h1>
                    <p class="analytics-subtitle">Dashboard ejecutivo con métricas en tiempo real</p>
                </div>
                
                <div class="header-actions">
                    <button wire:click="exportPDF" class="btn-export btn-pdf">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        PDF
                    </button>
                    <button wire:click="exportExcel" class="btn-export btn-excel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                        Excel
                    </button>
                    <button wire:click="refreshData" class="btn-refresh">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Filtros -->
<div class="filters-bar">
    <div class="filter-group">
        <label class="filter-label">Período</label>
        <select wire:model.live="period"
                class="filter-select"
                style="min-width:150px;max-width:220px;width:auto;">
            <option value="7">Últimos 7 días</option>
            <option value="30">Últimos 30 días</option>
            <option value="90">Últimos 90 días</option>
            <option value="365">Último año</option>
            <option value="custom">Personalizado</option>
        </select>
    </div>

                
                @if($period === 'custom')
                <div class="filter-group">
                    <label class="filter-label">Desde</label>
                    <input type="date" wire:model.live="dateFrom" class="filter-input">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Hasta</label>
                    <input type="date" wire:model.live="dateTo" class="filter-input">
                </div>
                @endif
                
                <div class="filter-group">
                    <label class="filter-label">Rifa</label>
                    <select wire:model.live="rifaId" class="filter-select">
                        <option value="">Todas las rifas</option>
                        @foreach($rifas as $rifa)
                            <option value="{{ $rifa->id }}">{{ $rifa->titulo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- KPIs Dashboard -->
        <div class="kpis-grid">
            <!-- Ingresos Totales -->
            <div class="kpi-card kpi-revenue">
                <div class="kpi-header">
                    <div class="kpi-icon-wrapper">
                        <svg class="kpi-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="kpi-label">Ingresos Totales</span>
                </div>
                <div class="kpi-value">${{ number_format($ingresosTotales, 2) }}</div>
                <div class="kpi-change {{ $cambioIngresos >= 0 ? 'positive' : 'negative' }}">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($cambioIngresos >= 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                    <span>{{ number_format(abs($cambioIngresos), 1) }}%</span>
                    <span class="change-label">vs período anterior</span>
                </div>
                <div class="kpi-sparkline">
                    <canvas id="sparklineRevenue"></canvas>
                </div>
            </div>

            <!-- Órdenes -->
            <div class="kpi-card kpi-orders">
                <div class="kpi-header">
                    <div class="kpi-icon-wrapper">
                        <svg class="kpi-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="kpi-label">Total Órdenes</span>
                </div>
                <div class="kpi-value">{{ number_format($cantidadOrdenes) }}</div>
                <div class="kpi-change {{ $cambioOrdenes >= 0 ? 'positive' : 'negative' }}">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($cambioOrdenes >= 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                    <span>{{ number_format(abs($cambioOrdenes), 1) }}%</span>
                    <span class="change-label">vs período anterior</span>
                </div>
                <div class="kpi-sparkline">
                    <canvas id="sparklineOrders"></canvas>
                </div>
            </div>

            <!-- Ticket Promedio -->
            <div class="kpi-card kpi-ticket">
                <div class="kpi-header">
                    <div class="kpi-icon-wrapper">
                        <svg class="kpi-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <span class="kpi-label">Ticket Promedio</span>
                </div>
                <div class="kpi-value">${{ number_format($ticketPromedio, 2) }}</div>
                <div class="kpi-change {{ $cambioTicket >= 0 ? 'positive' : 'negative' }}">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($cambioTicket >= 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                    <span>{{ number_format(abs($cambioTicket), 1) }}%</span>
                    <span class="change-label">vs período anterior</span>
                </div>
                <div class="kpi-sparkline">
                    <canvas id="sparklineTicket"></canvas>
                </div>
            </div>

            <!-- Participantes -->
            <div class="kpi-card kpi-participants">
                <div class="kpi-header">
                    <div class="kpi-icon-wrapper">
                        <svg class="kpi-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="kpi-label">Participantes Únicos</span>
                </div>
                <div class="kpi-value">{{ number_format($participantesUnicos) }}</div>
                <div class="kpi-change {{ $cambioParticipantes >= 0 ? 'positive' : 'negative' }}">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($cambioParticipantes >= 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                    <span>{{ number_format(abs($cambioParticipantes), 1) }}%</span>
                    <span class="change-label">vs período anterior</span>
                </div>
                <div class="kpi-sparkline">
                    <canvas id="sparklineParticipants"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráficos Principales -->
        <div class="charts-grid">
            <!-- Gráfico de Ventas por Día -->
            <div class="chart-card chart-full">
                <div class="chart-header">
                    <h3 class="chart-title">Evolución de Ventas</h3>
                    <div class="chart-actions">
                        <button class="chart-btn active" data-chart="sales" data-type="line">Línea</button>
                        <button class="chart-btn" data-chart="sales" data-type="bar">Barras</button>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Estado de Números -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Estado de Números</h3>
                </div>
                <div class="chart-body">
                    <canvas id="numbersChart"></canvas>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-color" style="background: #10b981;"></span>
                        <span>Vendidos: {{ number_format($totalVendidos) }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #f59e0b;"></span>
                        <span>Reservados: {{ number_format($totalReservados) }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #6b7280;"></span>
                        <span>Disponibles: {{ number_format($totalDisponibles) }}</span>
                    </div>
                </div>
            </div>

            <!-- Métodos de Pago -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Métodos de Pago</h3>
                </div>
                <div class="chart-body">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>

            <!-- Ventas por Día de Semana -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Ventas por Día de Semana</h3>
                </div>
                <div class="chart-body">
                    <canvas id="weekdayChart"></canvas>
                </div>
            </div>

            <!-- Ventas por Hora -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Distribución Horaria</h3>
                </div>
                <div class="chart-body">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tablas de Datos -->
        <div class="tables-grid">
            <!-- Top Rifas -->
            <div class="table-card">
                <div class="table-header">
                    <h3 class="table-title">Top 10 Rifas</h3>
                    <span class="table-badge">Mejor Rendimiento</span>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Rifa</th>
                                <th>Ingresos</th>
                                <th>Órdenes</th>
                                <th>Participantes</th>
                                <th>Ticket Prom.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topRifas as $index => $rifa)
                            <tr>
                                <td>
                                    <div class="table-item">
                                        <span class="item-rank">{{ $index + 1 }}</span>
                                        <span class="item-name">{{ Str::limit($rifa->titulo, 30) }}</span>
                                    </div>
                                </td>
                                <td class="text-success">${{ number_format($rifa->total, 2) }}</td>
                                <td>{{ number_format($rifa->pedidos) }}</td>
                                <td>{{ number_format($rifa->participantes) }}</td>
                                <td>${{ number_format($rifa->ticket_promedio, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Clientes -->
            <div class="table-card">
                <div class="table-header">
                    <h3 class="table-title">Top 10 Clientes</h3>
                    <span class="table-badge">Mejores Compradores</span>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Total Gastado</th>
                                <th>Compras</th>
                                <th>Ticket Prom.</th>
                                <th>Última Compra</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topClientes as $index => $cliente)
                            <tr>
                                <td>
                                    <div class="table-item">
                                        <span class="item-rank">{{ $index + 1 }}</span>
                                        <div>
                                            <span class="item-name">{{ $cliente->customer_name }}</span>
                                            <span class="item-email">{{ $cliente->customer_email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-success">${{ number_format($cliente->total_gastado, 2) }}</td>
                                <td>{{ $cliente->compras }}</td>
                                <td>${{ number_format($cliente->ticket_promedio, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($cliente->ultima_compra)->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Insights -->
        @if($diaPico)
        <div class="insights-section">
            <h3 class="insights-title">Insights Clave</h3>
            <div class="insights-grid">
                <div class="insight-card">
                    <div class="insight-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <h4>Día Pico de Ventas</h4>
                        <p class="insight-value">{{ \Carbon\Carbon::parse($diaPico->fecha)->format('d/m/Y') }}</p>
                        <p class="insight-detail">${{ number_format($diaPico->total, 2) }} en {{ $diaPico->pedidos }} órdenes</p>
                    </div>
                </div>

                <div class="insight-card">
                    <div class="insight-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <h4>Mejor Horario</h4>
                        <p class="insight-value">{{ $ventasPorHora->sortByDesc('total')->first()->hora ?? 0 }}:00 hrs</p>
                        <p class="insight-detail">Hora con mayor volumen de ventas</p>
                    </div>
                </div>

                <div class="insight-card">
                    <div class="insight-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <h4>Tasa de Conversión</h4>
                        <p class="insight-value">{{ number_format($tasaConversion, 1) }}%</p>
                        <p class="insight-detail">De visitantes a compradores</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Scripts para Gráficos -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración global
            Chart.defaults.font.family = 'Inter';
            
            // Datos
            const ventasPorDia = @json($ventasPorDia);
            const ventasPorHora = @json($ventasPorHora);
            const ventasPorDiaSemana = @json($ventasPorDiaSemana);
            const ingresosPorMetodo = @json($ingresosPorMetodo);
            
            // Gráfico de Ventas por Día
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: ventasPorDia.map(d => d.fecha_formato),
                    datasets: [{
                        label: 'Ingresos',
                        data: ventasPorDia.map(d => d.total),
                        borderColor: '#00367C',
                        backgroundColor: 'rgba(0, 54, 124, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
            
            // Gráfico de Estado de Números
            const numbersCtx = document.getElementById('numbersChart').getContext('2d');
            new Chart(numbersCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Vendidos', 'Reservados', 'Disponibles'],
                    datasets: [{
                        data: [{{ $totalVendidos }}, {{ $totalReservados }}, {{ $totalDisponibles }}],
                        backgroundColor: ['#10b981', '#f59e0b', '#6b7280']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
            
            // Gráfico de Métodos de Pago
            const paymentCtx = document.getElementById('paymentChart').getContext('2d');
            new Chart(paymentCtx, {
                type: 'pie',
                data: {
                    labels: ingresosPorMetodo.map(m => m.metodo),
                    datasets: [{
                        data: ingresosPorMetodo.map(m => m.total),
                        backgroundColor: ['#00367C', '#0052CC', '#3b82f6', '#60a5fa', '#93c5fd']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
            
            // Gráfico por Día de Semana
            const weekdayCtx = document.getElementById('weekdayChart').getContext('2d');
            new Chart(weekdayCtx, {
                type: 'bar',
                data: {
                    labels: ventasPorDiaSemana.map(d => d.dia_es),
                    datasets: [{
                        label: 'Ventas',
                        data: ventasPorDiaSemana.map(d => d.total),
                        backgroundColor: '#00367C'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
            
            // Gráfico por Hora
            const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
            new Chart(hourlyCtx, {
                type: 'bar',
                data: {
                    labels: Array.from({length: 24}, (_, i) => i + ':00'),
                    datasets: [{
                        label: 'Órdenes',
                        data: Array.from({length: 24}, (_, i) => {
                            const hora = ventasPorHora.find(h => h.hora == i);
                            return hora ? hora.pedidos : 0;
                        }),
                        backgroundColor: 'rgba(0, 54, 124, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
    @endpush
</x-filament-panels::page>