@extends('layouts.store')

@section('content')
@php
    /** @var \App\Models\Tenant $tenant */
    /** @var \App\Models\Order|null $order */
    /** @var \App\Models\Rifa|null $rifa */
    $bannerRifas = $tenant->rifas()
        ->whereIn('estado', ['activa', 'finalizada'])
        ->orderByRaw("FIELD(estado,'activa','finalizada') ASC")
        ->orderByDesc('ends_at')
        ->get();
    
    $primary = $tenant->primary_color ?? '#1d4ed8';
@endphp

{{-- Background animado --}}
<div class="fixed inset-0 bg-gradient-to-br from-gray-50 via-blue-50/20 to-gray-50 -z-10">
    <div class="absolute inset-0 overflow-hidden">
        <div class="floating-circle absolute top-20 left-10 w-72 h-72 rounded-full opacity-5" style="background: {{ $primary }}"></div>
        <div class="floating-circle-reverse absolute bottom-20 right-10 w-96 h-96 rounded-full opacity-5" style="background: {{ $primary }}"></div>
    </div>
</div>

<div class="min-h-screen flex items-center justify-center py-8 px-4">
    <div class="w-full max-w-lg">
        
        {{-- Header animado --}}
        <div class="text-center mb-6 animate-fade-in">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 shadow-xl"
                 style="background: linear-gradient(135deg, {{ $primary }} 0%, {{ $primary }}dd 100%);">
                <i class="fas fa-search text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold mb-2" style="color: {{ $primary }}">
                Verificador de Órdenes
            </h1>
            <p class="text-gray-600">Consulta el estado de tu compra al instante</p>
        </div>

        {{-- Main Card con glassmorphism --}}
        <div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden border border-white/50">
            
            {{-- Top gradient bar --}}
            <div class="h-2" style="background: linear-gradient(90deg, {{ $primary }} 0%, {{ $primary }}dd 50%, {{ $primary }} 100%);"></div>
            
            <div class="p-6 md:p-8">
                
                {{-- Security Badge --}}
                <div class="flex justify-center mb-6">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 border border-green-200">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-semibold text-green-700">Sistema 100% Seguro</span>
                        <i class="fas fa-shield-alt text-green-600"></i>
                    </div>
                </div>

                {{-- Search Form --}}
                <form method="GET" action="{{ route('store.verify', ['tenant' => $tenant]) }}" 
                      class="w-full"
                      x-data="{ code: '{{ $code ?? '' }}' }">
                    
                    <div class="mb-6">
                        <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">
                            Ingresa tu código de orden
                        </label>
                        
                        <div class="relative group">
                            <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                 style="background: linear-gradient(135deg, {{ $primary }}20 0%, {{ $primary }}10 100%);"></div>
                            
                            <div class="relative flex gap-2">
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-ticket-alt text-gray-400"></i>
                                    </div>
                                    <input
                                        type="text"
                                        id="code"
                                        name="code"
                                        x-model="code"
                                        @input="$event.target.value = $event.target.value.toUpperCase().replace(/[^A-Z0-9-]/g,'')"
                                        placeholder="Ej: ABC12345"
                                        autocomplete="off"
                                        autocapitalize="characters"
                                        spellcheck="false"
                                        class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-[var(--primary)] focus:ring-4 focus:ring-[var(--primary)]/20 transition-all duration-300 text-lg font-mono font-bold text-gray-800 placeholder-gray-400"
                                        style="--tw-ring-color: {{ $primary }}20;"
                                        required
                                    >
                                </div>
                                
                                <button type="submit"
                                        class="px-6 py-4 rounded-2xl font-bold text-white shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center gap-2"
                                        style="background: linear-gradient(135deg, {{ $primary }} 0%, {{ $primary }}dd 100%);">
                                    <i class="fas fa-search"></i>
                                    <span class="hidden sm:inline">Verificar</span>
                                </button>
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            El código aparece en tu correo de confirmación
                        </p>
                    </div>
                </form>

                {{-- Resultado con animación --}}
                @if(isset($code) && $code !== '')
                    @if($order)
                        <div class="animate-slide-up">
                            {{-- Success Result Card --}}
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-green-100 bg-gradient-to-br from-green-50 to-emerald-50">
                                
                                {{-- Status Header --}}
                                @php
                                    $status = $order->status instanceof \BackedEnum ? $order->status->value : $order->status;
                                    $statusConfig = [
                                        'paid' => [
                                            'color' => 'green',
                                            'icon' => 'fa-check-circle',
                                            'text' => 'PAGADO',
                                            'bg' => 'from-green-500 to-emerald-600'
                                        ],
                                        'submitted' => [
                                            'color' => 'blue',
                                            'icon' => 'fa-clock',
                                            'text' => 'EN VERIFICACIÓN',
                                            'bg' => 'from-blue-500 to-indigo-600'
                                        ],
                                        'pending' => [
                                            'color' => 'yellow',
                                            'icon' => 'fa-hourglass-half',
                                            'text' => 'PENDIENTE',
                                            'bg' => 'from-yellow-500 to-orange-600'
                                        ],
                                        'expired' => [
                                            'color' => 'red',
                                            'icon' => 'fa-times-circle',
                                            'text' => 'EXPIRADO',
                                            'bg' => 'from-red-500 to-pink-600'
                                        ],
                                        'cancelled' => [
                                            'color' => 'gray',
                                            'icon' => 'fa-ban',
                                            'text' => 'CANCELADO',
                                            'bg' => 'from-gray-500 to-gray-600'
                                        ],
                                    ];
                                    $config = $statusConfig[$status] ?? $statusConfig['pending'];
                                @endphp
                                
                                <div class="p-4 text-white text-center"
                                     style="background: linear-gradient(135deg, {{ $primary }} 0%, {{ $primary }}dd 100%);">
                                    <div class="flex items-center justify-center gap-3">
                                        <i class="fas {{ $config['icon'] }} text-2xl animate-pulse"></i>
                                        <span class="text-xl font-bold">{{ $config['text'] }}</span>
                                    </div>
                                </div>
                                
                                {{-- Order Details --}}
                                <div class="p-6">
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                                            <div class="text-xs text-gray-500 mb-1">Código</div>
                                            <div class="font-mono font-bold text-lg" style="color: {{ $primary }}">
                                                {{ $order->code }}
                                            </div>
                                        </div>
                                        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                                            <div class="text-xs text-gray-500 mb-1">Total</div>
                                            <div class="font-bold text-lg text-gray-800">
                                                ${{ number_format((float)$order->total_amount, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Rifa Info --}}
                                    <div class="bg-white rounded-xl p-4 shadow-sm mb-4">
                                        <div class="text-xs text-gray-500 mb-2">Rifa</div>
                                        <div class="font-semibold text-gray-800">
                                            {{ $order->rifa->titulo ?? 'Sin título' }}
                                        </div>
                                    </div>
                                    
                                    {{-- Tickets --}}
                                    <div class="bg-white rounded-xl p-4 shadow-sm">
                                        <div class="text-xs text-gray-500 mb-3">Tus números</div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($order->items->pluck('numero')->sort() as $numero)
                                                <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-full text-white font-bold text-sm shadow-sm"
                                                      style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
                                                    #{{ str_pad($numero, 4, '0', STR_PAD_LEFT) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- QR Code --}}
                            @if(isset($qrCode) && $qrCode)
                                <div class="mt-4 text-center">
                                    <div class="inline-block p-4 bg-white rounded-2xl shadow-lg border border-gray-100">
                                        <div class="p-2 bg-gray-50 rounded-xl">
                                            {!! $qrCode !!}
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">
                                            Código QR para compartir
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- Error State --}}
                        <div class="animate-shake">
                            <div class="rounded-2xl border-2 border-red-200 bg-red-50 p-6 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-red-800 mb-2">Orden no encontrada</h3>
                                <p class="text-sm text-red-600">
                                    No existe una orden con el código <strong>{{ $code }}</strong>
                                </p>
                                <button onclick="window.location.reload()"
                                        class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                                    Intentar nuevamente
                                </button>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Help Section --}}
                <div class="mt-8 p-4 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                 style="background: {{ $primary }}20;">
                                <i class="fas fa-question-circle text-lg" style="color: {{ $primary }}"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 mb-1">¿Necesitas ayuda?</h4>
                            <p class="text-sm text-gray-600 mb-3">
                                Si tu orden aparece como pendiente o en verificación, el proceso puede tomar algunos minutos.
                            </p>
                            <a href="https://wa.me/{{ $contact?->whatsapp }}"
                               target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-full font-semibold text-sm hover:bg-green-600 transition shadow-md hover:shadow-lg">
                                <i class="fab fa-whatsapp"></i>
                                Contactar soporte
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Footer --}}
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-lock"></i>
                        Verificación segura
                    </span>
                    <span>Powered by Rifasys</span>
                </div>
            </div>
        </div>

       @if($bannerRifas->count())
    <div class="mt-8">
        <h3 class="text-center text-sm font-semibold text-gray-600 mb-4">
            Rifas disponibles
        </h3>
        <div class="rifas-static-gallery grid grid-cols-2 md:grid-cols-3 gap-4 justify-center">
            @foreach($bannerRifas as $item)
                @php
                    $finalizada = $item->ends_at && \Carbon\Carbon::parse($item->ends_at)->isPast();
                @endphp
                <div class="rifa-card relative rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 bg-white mx-auto"
                     style="width:150px;max-width:94vw;">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->banner_path) }}"
                         alt="{{ $item->nombre }}"
                         class="">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-2 text-white">
                        <h4 class="font-bold text-xs truncate">{{ $item->nombre }}</h4>
                        <p class="text-[10px] opacity-90">
                            Sorteo: {{ optional($item->ends_at)->format('d/m/Y') }}
                        </p>
                    </div>
                    {{-- Badge: lógica correcta usando fecha --}}
                    @if($finalizada)
                        <span class="absolute top-1 right-1 px-1 py-0.5 bg-red-600 text-white text-[10px] font-bold rounded-full">
                            Finalizada
                        </span>
                    @elseif($item->estado === 'activa')
                        <span class="absolute top-1 right-1 px-1 py-0.5 bg-green-500 text-white text-[10px] font-bold rounded-full">
                            Activa
                        </span>
                    @elseif($item->estado === 'pausada')
                        <span class="absolute top-1 right-1 px-1 py-0.5 bg-yellow-400 text-white text-[10px] font-bold rounded-full">
                            Pausada
                        </span>
                    @endif

                </div>
            @endforeach
        </div>
    </div>
@endif



<style>
/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-up {
    animation: slideUp 0.5s ease-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

/* Floating circles */
.floating-circle {
    animation: float 20s infinite ease-in-out;
}

.floating-circle-reverse {
    animation: float-reverse 25s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    25% { transform: translate(50px, -30px) scale(1.1); }
    50% { transform: translate(-30px, 50px) scale(0.9); }
    75% { transform: translate(30px, 30px) scale(1.05); }
}

@keyframes float-reverse {
    0%, 100% { transform: translate(0, 0) scale(1); }
    25% { transform: translate(-50px, 30px) scale(0.95); }
    50% { transform: translate(30px, -50px) scale(1.1); }
    75% { transform: translate(-30px, -30px) scale(1); }
}

/* Swiper customization */
.verify-swiper .swiper-pagination-bullet {
    background: #cbd5e1;
    opacity: 1;
}

.verify-swiper .swiper-pagination-bullet-active {
    background: var(--primary);
}

/* Focus styles */
input:focus {
    outline: none;
}

/* Glassmorphism effect */
.bg-white\/95 {
    background: rgba(255, 255, 255, 0.95);
}


.rifas-static-gallery {
    margin-left: auto;
    margin-right: auto;
    max-width: 700px;
}
.rifa-card {
    /* ya con width:220px arriba; */
    min-width: 180px;
    box-shadow: 0 4px 24px 0 rgba(0,0,0,0.09), 0 1.5px 5px 0 rgba(0,0,0,0.06);
    border-radius: 1rem;
    overflow: hidden;
    background: #fff;
    position: relative;
}
@@media (max-width: 639px) {
    .rifas-static-gallery {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }
    .rifa-card {
        width: 44vw !important;
        min-width: 120px;
        max-width: 160px;
    }
}
@media (min-width: 640px) {
    .rifas-static-gallery {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }
    .rifa-card {
        width: 150px !important;
        min-width: 130px;
        max-width: 170px;
    }
}

@media (min-width: 640px) {
    .rifas-static-gallery {
        justify-content: center !important;
        display: flex !important;
        gap: 16px;
    }
}
.rifa-card {
    margin-left: auto;
    margin-right: auto;
}


</style>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalSlides = {{ $bannerRifas->count() }};
    const swiperContainer = document.querySelector('.verify-swiper');
    if (!swiperContainer) return;

    let swiperInstance;

    function getConfig() {
        return {
            slidesPerView: window.innerWidth < 640 ? 'auto' : (window.innerWidth < 1024 ? 2 : 3),
            centeredSlides: window.innerWidth < 640, // solo centrado en móvil
            spaceBetween: 20,
            loop: totalSlides > (window.innerWidth < 640 ? 1 : window.innerWidth < 1024 ? 2 : 3),
            autoplay: totalSlides > 1 ? { delay: 4000, disableOnInteraction: false } : false,
            pagination: {
                el: swiperContainer.querySelector('.swiper-pagination'),
                clickable: true,
            },
        };
    }

    function initSwiper() {
        if (swiperInstance && swiperInstance.destroy) {
            swiperInstance.destroy(true, true);
        }
        swiperInstance = new Swiper(swiperContainer, getConfig());
    }

    initSwiper();

    window.addEventListener('resize', function() {
        // Reinicializa al cambiar de tamaño (evita bugs en responsivo)
        setTimeout(initSwiper, 120);
    });
});
</script>
@endpush
