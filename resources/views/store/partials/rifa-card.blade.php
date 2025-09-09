{{-- resources/views/store/partials/rifa-card.blade.php --}}
@php
    // Recibe: $rifa, $tenant, $primary
    $sold  = $rifa->numeros()->where('estado','pagado')->count();
    $total = $rifa->total_numeros ?? 0;
@endphp
<div class="bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col items-center max-w-xs w-full relative">
    {{-- Badge ACTIVA TITILANDO --}}
    @if($rifa->estado === 'activa')
        <span class="badge-activa-animada absolute top-3 left-3 z-10 px-3 py-1 rounded-full text-xs font-extrabold uppercase text-white shadow-lg"
              style="background: {{ $primary }};">
            <span class="inline-block align-middle mr-1 animate-pulse">
                <span style="display:inline-block;width:13px;height:13px;background:#fff;border-radius:50%;box-shadow:0 0 10px 5px {{ $primary }}88"></span>
            </span>
            Activa
        </span>
    @endif
    {{-- Banner de rifa --}}
    <div class="flex justify-center p-3">
        @if($rifa->banner_path)
            <div class="bg-white rounded-2xl shadow-lg border-4 border-yellow-300 flex items-center justify-center" style="width:200px; height:200px;">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($rifa->banner_path) }}"
                     alt="Banner rifa"
                     class="block w-full h-full object-cover rounded-2xl" />
            </div>
        @else
            <div class="h-48 w-full bg-gray-100 flex items-center justify-center text-2xl text-gray-400 rounded-2xl">
                Sin imagen
            </div>
        @endif
    </div>
    <div class="p-3 flex flex-col items-center">
        <div class="font-extrabold text-lg text-[var(--primary)] mb-2 text-center">{{ $rifa->nombre }}</div>
        {{-- KPI mini --}}
        <div class="w-full flex flex-col items-center gap-1 my-1">
            <div class="w-full max-w-xs bg-gradient-to-t from-yellow-50 to-yellow-100 rounded-xl shadow-sm border border-yellow-200 py-3 px-2 flex flex-col items-center mb-1">
                <div class="text-xs font-bold text-yellow-700 flex items-center gap-1 uppercase mb-1">
                    <i class="fas fa-calendar-day text-yellow-500"></i> Sorteo
                </div>
                <div class="text-lg font-black text-yellow-700 leading-tight">
                    {{ $rifa->ends_at?->format('d/m/Y') }}
                </div>
                <div class="text-xs text-yellow-600 opacity-80 font-semibold">
                    {{ $rifa->ends_at?->format('H:i') }} hrs
                </div>
            </div>
            <div class="w-full max-w-xs flex flex-row gap-2 mx-auto">
                <div class="flex-1 flex flex-col items-center justify-center bg-blue-50 rounded-lg shadow border border-blue-100 py-2 px-1 min-w-[70px]">
                    <div class="text-[10px] font-bold text-blue-700 flex items-center gap-1 uppercase mb-0.5">
                        <i class="fas fa-ticket-alt text-xs text-blue-500"></i> Vendidos
                    </div>
                    <div class="text-base font-extrabold text-blue-800 leading-tight">
                        {{ $sold }}
                    </div>
                    <div class="text-[10px] text-blue-700 opacity-80 font-semibold -mt-0.5">
                        Boletos
                    </div>
                </div>
                <div class="flex-1 flex flex-col items-center justify-center bg-green-50 rounded-lg shadow border border-green-100 py-2 px-1 min-w-[70px]">
                    <div class="text-[10px] font-bold text-green-700 flex items-center gap-1 uppercase mb-0.5">
                        <i class="fas fa-check-circle text-xs text-green-500"></i> Disp.
                    </div>
                    <div class="text-base font-extrabold text-green-800 leading-tight">
                        {{ $total - $sold }}
                    </div>
                    <div class="text-[10px] text-green-700 opacity-80 font-semibold -mt-0.5">
                        Restantes
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ url('/t/'.$tenant->slug.'/r/'.$rifa->slug) }}"
           class="mt-2 bg-[var(--primary)] hover:bg-[var(--primary)]/90 text-white font-semibold rounded-lg px-6 py-2 text-base shadow-md transition w-fit mx-auto">
            Ver Evento
        </a>
    </div>
</div>
