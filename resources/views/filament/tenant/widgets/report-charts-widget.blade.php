<x-filament::widget>
    <div class="report-charts-grid grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Chart 1 -->
        <div class="report-chart-card bg-white rounded-xl shadow-sm border p-4 flex flex-col">
            <div class="font-semibold mb-2 text-gray-800">Ventas por Día</div>
            <canvas id="chartVentasDia" height="110"></canvas>
        </div>
        <!-- Chart 2 -->
        <div class="report-chart-card bg-white rounded-xl shadow-sm border p-4 flex flex-col">
            <div class="font-semibold mb-2 text-gray-800">Vendidos vs Reservados</div>
            <canvas id="chartVendRes" height="110"></canvas>
        </div>
        <!-- Chart 3 -->
        <div class="report-chart-card bg-white rounded-xl shadow-sm border p-4 flex flex-col">
            <div class="font-semibold mb-2 text-gray-800">Ingresos por Método</div>
            <canvas id="chartIngresosMetodo" height="110"></canvas>
        </div>
        <!-- Chart 4 -->
        <div class="report-chart-card bg-white rounded-xl shadow-sm border p-4 flex flex-col">
            <div class="font-semibold mb-2 text-gray-800">Órdenes por Día de Semana</div>
            <canvas id="chartOrdenesSemana" height="110"></canvas>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ventasDiaLabels = @json($ventasDiaLabels);
        const ventasDiaData = @json($ventasDiaData);
        const vendResLabels = @json($vendResLabels);
        const vendResData = @json($vendResData);
        const ingresosMetodoLabels = @json($ingresosMetodoLabels);
        const ingresosMetodoData = @json($ingresosMetodoData);
        const ordenesSemanaLabels = @json($ordenesSemanaLabels);
        const ordenesSemanaData = @json($ordenesSemanaData);

        // Chart 1: Ventas por Día
        if(document.getElementById('chartVentasDia')){
            new Chart(document.getElementById('chartVentasDia'), {
                type: 'line',
                data: {
                    labels: ventasDiaLabels,
                    datasets: [{
                        label: 'Ventas',
                        data: ventasDiaData,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        backgroundColor: 'rgba(59,130,246,0.10)',
                        borderColor: '#2563eb',
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#2563eb',
                    }]
                },
                options: { plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
            });
        }
        // Chart 2: Vendidos vs Reservados
        if(document.getElementById('chartVendRes')){
            new Chart(document.getElementById('chartVendRes'), {
                type: 'doughnut',
                data: {
                    labels: vendResLabels,
                    datasets: [{
                        data: vendResData,
                        backgroundColor: ['#10b981', '#f59e0b'],
                        borderWidth: 1,
                    }]
                },
                options: { plugins:{legend:{position:'bottom'}}, cutout:'65%' }
            });
        }
        // Chart 3: Ingresos por Método
        if(document.getElementById('chartIngresosMetodo')){
            new Chart(document.getElementById('chartIngresosMetodo'), {
                type: 'bar',
                data: {
                    labels: ingresosMetodoLabels,
                    datasets: [{
                        label: 'Ingresos',
                        data: ingresosMetodoData,
                        borderWidth: 2,
                        backgroundColor: '#6366f1'
                    }]
                },
                options: { plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
            });
        }
        // Chart 4: Órdenes por Día de Semana
        if(document.getElementById('chartOrdenesSemana')){
            new Chart(document.getElementById('chartOrdenesSemana'), {
                type: 'bar',
                data: {
                    labels: ordenesSemanaLabels,
                    datasets: [{
                        label: 'Órdenes',
                        data: ordenesSemanaData,
                        borderWidth: 2,
                        backgroundColor: '#3b82f6'
                    }]
                },
                options: { plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
            });
        }
    </script>
</x-filament::widget>
