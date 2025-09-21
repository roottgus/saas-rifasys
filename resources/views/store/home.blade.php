@extends('layouts.store')

@section('content')
@php
    /** @var \App\Models\Tenant|null $tenant */
    $tenant = (isset($tenant) && $tenant instanceof \App\Models\Tenant)
        ? $tenant
        : (request()->route('tenant') instanceof \App\Models\Tenant ? request()->route('tenant') : null);

    $home    = $home   ?? ($tenant?->homeSettings()->first());
    $brand   = $brand  ?? ($tenant?->brandSettings()->first());

    // NUEVO: Todas las rifas activas
    $rifas = $rifas ?? ($tenant?->rifas()->where('estado', 'activa')->orderByDesc('ends_at')->get());

    // Banner de portada SOLO de la portada (no de la rifa)
    $bannerPath = $home?->banner_path ?: null;
    $bannerUrl  = $bannerPath ? \Illuminate\Support\Facades\Storage::url($bannerPath) : null;

    $heroTitle = trim($home?->titulo ?? '');
    $heroSub   = trim($home?->subtitulo ?? '');
    $descripcionEmpresa = trim($home?->descripcion ?? '');
    $primary = $brand?->color_primary ?? '#1d4ed8';
@endphp

<style>
@keyframes titilar {
  0%, 100% { opacity: 1; box-shadow: 0 0 18px 5px #22d3ee77, 0 0 0 0 #fff0; }
  50%      { opacity: 0.55; box-shadow: 0 0 32px 10px #fff9, 0 0 0 2px #fff3; }
}
.badge-activa-animada {
  animation: titilar 1.1s infinite alternate;
  box-shadow: 0 2px 14px 0 #fff2, 0 0 18px 2px #34d39955;
  letter-spacing: 0.04em;
}
</style>

{{-- HERO igual a la competencia: imagen izquierda, texto derecha, SIN recuadro blanco --}}
<section class="w-full bg-[#f3f4f6] py-10 md:py-14">
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row 
                items-center justify-center gap-8 px-6 md:px-8">
        {{-- Imagen/banner a la izquierda --}}
        @if($bannerUrl)
        <div class="flex-shrink-0 w-[240px] md:w-[280px] flex items-center justify-center mb-4 md:mb-0">
            <img src="{{ $bannerUrl }}" alt="Banner portada"
                class="rounded-2xl object-cover w-full h-auto max-h-[220px] md:max-h-[260px]"
                style="box-shadow:
                    0 0 16px 6px {{ $primary }}AA,   /* Glow exterior primario */
                    0 6px 36px 0 #0003;              /* Sombra negra muy suave para realce */
                "
            />
        </div>
        @endif

        {{-- Info a la derecha, ahora todo centrado --}}
        <div class="flex-1 flex flex-col items-center justify-center text-center gap-2 max-w-xl">
            @if($heroTitle)
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-1">
                    {{ $heroTitle }}
                </h1>
            @endif
            @if($heroSub)
                <div class="text-xl font-bold text-[var(--primary)] mb-1">
                    {{ $heroSub }}
                </div>
            @endif

            {{-- Descripción personalizada SI HAY --}}
            @if($descripcionEmpresa)
                <div class="text-base md:text-lg text-gray-600 font-medium mb-1 max-w-lg mx-auto text-center">
                    {!! nl2br(e($descripcionEmpresa)) !!}
                </div>
            @endif

            {{-- Texto motivador SIEMPRE (debajo de la descripción o solo) --}}
            <div class="text-base md:text-lg text-[var(--primary)] font-semibold mb-3 max-w-lg mx-auto text-center">
                ¡Bienvenido! Compra tu ticket y participa!
            </div>

            @if($home?->lugar)
                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">{{ $home->lugar }}</div>
            @endif

            <a href="#disponibles"
               class="mt-3 bg-[var(--primary)] hover:bg-[var(--primary)]/90 text-white text-lg font-bold px-8 py-3 rounded-xl shadow transition">
                Lista de Disponibles
            </a>
        </div>
    </div>
</section>




{{-- DISPONIBLES: abajo, centrado, grid/slider de rifas --}}
<div class="max-w-6xl mx-auto w-full px-4 bg-white pt-8" id="disponibles">
    <div class="text-center mb-6">
        <span class="block text-base font-bold text-gray-700">¡Participa!</span>
        <h2 class="text-2xl md:text-3xl font-black text-[var(--primary)] mt-1 mb-3 tracking-wide">DISPONIBLES</h2>
    </div>

    {{-- Slider solo móvil (md:hidden) --}}
    <div class="block md:hidden">
        <div class="swiper-rifas swiper px-1 pb-4 relative">
            <div class="swiper-wrapper">
                @forelse($rifas as $rifa)
                    <div class="swiper-slide py-2">
                        @include('store.partials.rifa-card', [
                            'rifa'   => $rifa,
                            'tenant' => $tenant,
                            'primary'=> $primary
                        ])
                    </div>
                @empty
                    <div class="rounded-xl bg-white border p-8 text-center text-xl font-bold text-gray-600 shadow w-full">
                        No hay rifas activas en este momento.<br>
                        ¡Vuelve pronto o explora sorteos finalizados!
                    </div>
                @endforelse
            </div>
            {{-- FLECHAS SWIPER --}}
            <div class="swiper-button-prev !left-0 !top-1/2 !-translate-y-1/2 !text-[var(--primary)] !bg-white !rounded-full !shadow-lg !w-9 !h-9 flex items-center justify-center z-20"></div>
            <div class="swiper-button-next !right-0 !top-1/2 !-translate-y-1/2 !text-[var(--primary)] !bg-white !rounded-full !shadow-lg !w-9 !h-9 flex items-center justify-center z-20"></div>
            <div class="swiper-pagination mt-1"></div>
        </div>
    </div>
    {{-- Grid desktop/tablet (hidden en móvil) --}}
    <div class="hidden md:flex flex-wrap justify-center gap-8">
        @forelse($rifas as $rifa)
            @include('store.partials.rifa-card', [
                'rifa'   => $rifa,
                'tenant' => $tenant,
                'primary'=> $primary
            ])
        @empty
            <div class="rounded-xl bg-white border p-8 text-center text-xl font-bold text-gray-600 shadow w-full">
                No hay rifas activas en este momento.<br>
                ¡Vuelve pronto o explora sorteos finalizados!
            </div>
        @endforelse
    </div>
</div>

{{-- FAQ genérico debajo de rifas --}}
@include('store.partials.faq', ['primary' => $primary])

{{-- BADGE de seguridad y Cards de Confianza --}}
<div class="w-full max-w-5xl mx-auto flex flex-col items-center justify-center gap-6 mt-6 px-3">
    <!-- Security Badge -->
    <div class="flex flex-wrap justify-center items-center gap-2 text-gray-500">
        <i class="fas fa-shield-alt text-lg text-green-500"></i>
        <span class="text-xs font-semibold">Pago Seguro y Encriptado</span>
        <i class="fab fa-cc-visa text-lg"></i>
        <i class="fab fa-cc-mastercard text-lg"></i>
        <i class="fab fa-cc-paypal text-lg"></i>
    </div>
    <!-- Info Cards - fila horizontal en mobile -->
    <div class="grid grid-cols-3 gap-2 w-full max-w-xl">
        <div class="bg-white/80 rounded-xl p-3 text-center shadow border border-blue-100 flex flex-col items-center">
            <i class="fas fa-trophy text-xl text-yellow-500 mb-1 animate-bounce"></i>
            <div class="font-bold text-xs text-gray-800">Premios<br>Garantizados</div>
        </div>
        <div class="bg-white/80 rounded-xl p-3 text-center shadow border border-blue-100 flex flex-col items-center">
            <i class="fas fa-users text-xl text-blue-500 mb-1 animate-pulse"></i>
            <div class="font-bold text-xs text-gray-800">+10,000<br>Usuarios</div>
        </div>
        <div class="bg-white/80 rounded-xl p-3 text-center shadow border border-blue-100 flex flex-col items-center">
            <i class="fas fa-certificate text-xl text-green-500 mb-1 animate-spin-slow"></i>
            <div class="font-bold text-xs text-gray-800">100%<br>Confiable</div>
        </div>
    </div>
</div>



@endsection
