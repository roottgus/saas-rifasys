{{-- resources/views/filament/tenant/widgets/report-kpis-widget.blade.php --}}
<x-filament::widget>
    <div class="bg-gradient-to-r from-primary-600 to-cyan-600 p-6 rounded-2xl shadow-xl mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-white mb-1 flex items-center gap-2">
                <x-heroicon-o-presentation-chart-bar class="w-7 h-7 text-white opacity-80" />
                KPIs de Ventas & Operación
            </h2>
            <div class="text-cyan-100 font-medium">Indicadores clave en tiempo real para tu análisis empresarial</div>
        </div>
        <div class="hidden sm:flex items-center gap-4">
            <span class="text-white/70 text-xs">Última actualización: {{ now()->format('d/m/Y H:i') }}</span>
            <button wire:click="$refresh" class="bg-white/10 hover:bg-white/20 text-white rounded-lg px-3 py-1 text-xs font-bold transition shadow-sm">
                <x-heroicon-o-arrow-path class="w-4 h-4 mr-1 inline" /> Actualizar
            </button>
        </div>
    </div>
    <div wire:loading.class="opacity-40 pointer-events-none transition" class="relative">
        {{-- Aquí Filament renderiza automáticamente los KPIs --}}
        {{-- Puedes agregar animación, loading, o decoraciones aquí si lo deseas --}}
    </div>
</x-filament::widget>
