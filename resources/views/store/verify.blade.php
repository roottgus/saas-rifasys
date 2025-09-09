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
@endphp

<div class="w-full flex flex-col items-center pt-10 pb-8">

  {{-- Slider de rifas (tarjeta compacta pro) --}}
  @if($bannerRifas->count())
    <div class="swiper w-full max-w-[280px] mb-5">
      <div class="swiper-wrapper">
        @foreach($bannerRifas as $item)
          <div class="swiper-slide">
            <div class="relative rounded-2xl border border-gray-100 bg-white shadow-[0_10px_40px_rgba(17,24,39,.12)] p-3">
              {{-- Título --}}
              <div class="text-center text-[13px] font-extrabold text-slate-900 mb-2 truncate">
                {{ $item->nombre ?? $item->titulo }}
              </div>
              {{-- Banner --}}
              <div class="relative">
                <img
                  src="{{ \Illuminate\Support\Facades\Storage::url($item->banner_path) }}"
                  alt="Banner rifa"
                  class="w-full h-auto object-contain rounded-xl ring-1 ring-gray-100"
                />
                @if($item->estado === 'activa')
                  <span class="absolute top-2 left-2 px-2 py-0.5 bg-emerald-600 text-white text-[10px] font-bold rounded-full shadow uppercase tracking-wide">Activa</span>
                @elseif($item->estado === 'finalizada')
                  <span class="absolute top-2 left-2 px-2 py-0.5 bg-gray-600 text-white text-[10px] font-bold rounded-full shadow uppercase tracking-wide">Finalizada</span>
                @endif
              </div>
              {{-- Fecha --}}
              <div class="text-center mt-2 text-[12px] text-gray-600">
                <span class="font-semibold">Fecha de sorteo:</span>
                <span class="font-bold text-indigo-700">{{ optional($item->ends_at)->format('d/m/Y') }}</span>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      @if($bannerRifas->count() > 1)
        <div class="swiper-pagination mt-2"></div>
      @endif
    </div>
  @endif

  {{-- Verificador (card corporativa) --}}
  <div class="w-full max-w-[420px] mx-auto bg-white/95 rounded-2xl shadow-[0_10px_40px_rgba(17,24,39,.12)] px-6 py-7 border border-gray-100">
    {{-- Header --}}
    <div class="flex flex-col items-center text-center mb-4">
      <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1 text-[11px] font-semibold text-gray-700 shadow-sm mb-2">
        <svg class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 1a5 5 0 0 0-5 5v3H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-2V6a5 5 0 0 0-5-5Zm-3 8V6a3 3 0 0 1 6 0v3H9Z"/></svg>
        <span>Sistema seguro</span>
      </div>
      <h1 class="text-[22px] font-black text-[var(--primary)] leading-tight tracking-tight">Verificador de Orden</h1>
      <p class="text-gray-500 text-[14.5px] mt-1">Consulta el estado de tu compra</p>
    </div>

    {{-- Formulario --}}
    <form method="GET" action="{{ route('store.verify', ['tenant' => $tenant]) }}" class="w-full mt-1"
          x-data
          x-init="$el.querySelector('#code')?.addEventListener('input', e => e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9-]/g,''))">
      <label for="code" class="font-semibold text-xs mb-1 text-gray-700 block">Código de orden</label>

      <div class="flex w-full gap-2">
        <div class="relative flex-1">
          <span class="pointer-events-none absolute left-3 top-2.5 text-gray-400">
            <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 4H4a2 2 0 0 0-2 2v2h20V6a2 2 0 0 0-2-2Zm2 6H2v8a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-8ZM6 18H4v-2h2v2Zm4 0H8v-2h2v2Z"/></svg>
          </span>
          <input
            id="code" name="code" value="{{ $code ?? '' }}"
            placeholder="ABCD1234"
            autocomplete="off" autocapitalize="characters" spellcheck="false"
            class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 bg-gray-50 text-[15px] text-gray-900 focus:ring-2 focus:ring-[var(--primary)]/60 focus:bg-white outline-none transition"
            required
          >
        </div>

        <button type="submit"
          class="px-3 py-2 rounded-lg font-extrabold bg-[var(--primary)] hover:brightness-110 text-white shadow transition flex items-center gap-2 text-sm">
          <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m21.71 20.29-3.4-3.39A8.92 8.92 0 1 0 19 19l3.39 3.4a1 1 0 0 0 1.41-1.41ZM4 10a6 6 0 1 1 6 6 6 6 0 0 1-6-6Z"/></svg>
          Verificar
        </button>
      </div>
      <p class="text-xs text-gray-400 mt-1">Ingresa el código que aparece en tu comprobante o checkout.</p>
    </form>

    {{-- Resultado --}}
    @if(isset($code) && $code !== '')
      @if($order)
        <div class="w-full mt-6">
          <div class="rounded-xl bg-emerald-50/70 border border-emerald-200 p-4 shadow-sm">
            <div class="font-bold text-gray-700 mb-2 text-center">Resultado</div>
            <div class="grid grid-cols-1 gap-1.5 text-[15px]">
              <div>Código: <strong class="font-extrabold">{{ $order->code }}</strong></div>
              <div>Rifa: <strong>{{ $order->rifa->titulo ?? '-' }}</strong></div>
              <div>
                Estado:
                @php
                  $status = $order->status instanceof \BackedEnum ? $order->status->value : $order->status;
                  $color = match($status) {
                      'paid'      => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                      'submitted' => 'bg-blue-100 text-blue-800 ring-blue-200',
                      'pending'   => 'bg-amber-100 text-amber-800 ring-amber-200',
                      'expired'   => 'bg-red-100 text-red-800 ring-red-200',
                      'cancelled' => 'bg-gray-200 text-gray-700 ring-gray-300',
                      default     => 'bg-gray-200 text-gray-700 ring-gray-300',
                  };
                  $statusText = match($status) {
                      'paid'      => 'PAGADO',
                      'submitted' => 'EN VERIFICACIÓN',
                      'pending'   => 'PENDIENTE',
                      'expired'   => 'EXPIRADO',
                      'cancelled' => 'CANCELADO',
                      default     => strtoupper($status ?? '-'),
                  };
                @endphp
                <span class="px-2 py-0.5 rounded text-xs font-black ring-1 {{ $color }}">{{ $statusText }}</span>
              </div>
              <div>Boletos: <strong>{{ $order->items->pluck('numero')->sort()->implode(', ') }}</strong></div>
              <div>Total: <strong>${{ number_format((float)$order->total_amount, 2) }}</strong></div>
            </div>
          </div>

          {{-- QR Code Section --}}
          @if(isset($qrCode) && $qrCode)
            <div class="w-full mt-4">
              <div class="rounded-xl bg-gray-50/70 border border-gray-200 p-4 shadow-sm text-center">
                <div class="font-bold text-gray-700 mb-3">Código QR</div>
                <div class="inline-block p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                  {!! $qrCode !!}
                </div>
                <p class="text-xs text-gray-500 mt-2">
                  Comparte este código para verificar la orden
                </p>
              </div>
            </div>
          @endif
        </div>
      @else
        <div class="w-full mt-6">
          <div class="rounded-xl bg-red-50/80 border border-red-200 px-4 py-3 text-[14px] text-center font-bold text-red-700 shadow-sm">
            No encontramos una orden con el código <strong>{{ $code }}</strong> para este sitio.
          </div>
        </div>
      @endif
    @endif

    {{-- Seguridad y soporte --}}
    <div class="w-full mt-6">
      <div class="rounded-xl bg-gradient-to-b from-gray-50 to-white border border-gray-100 px-4 py-4 text-[13px] text-center shadow-sm">
        <span class="font-bold text-yellow-800 flex items-center gap-2 justify-center mb-1">
          <i class="fas fa-shield-alt"></i>
          Seguridad y soporte
        </span>
        <span class="text-gray-700">
          Si tu orden aparece como <span class="font-bold text-blue-800">PENDIENTE</span> o <span class="font-bold text-blue-800">EN VERIFICACIÓN</span>,
          la validación puede demorar algunos minutos.<br>
          ¿Tienes dudas? <a href="https://wa.me/{{ $contact?->whatsapp }}" class="text-green-700 underline hover:text-green-800" target="_blank" rel="noopener">Contáctanos por WhatsApp</a>.
        </span>
        <div class="text-xs text-gray-400 mt-2">Powered by Rifasys • Sistema 100% seguro</div>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- Swiper styles/scripts + ajustes visuales --}}
@push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <style>
    /* bullets más finos y corporativos */
    .swiper-pagination-bullet{ background:#cbd5e1; opacity:1; }
    .swiper-pagination-bullet-active{ background:var(--primary); }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (document.querySelector('.swiper')) {
        new Swiper('.swiper', {
          slidesPerView: 1,
          spaceBetween: 10,
          loop: {{ $bannerRifas->count() > 1 ? 'true' : 'false' }},
          pagination: { el: '.swiper-pagination', clickable: true },
          autoplay: { delay: 3600, disableOnInteraction: false },
          effect: "slide",
          speed: 450,
        });
      }
    });
  </script>
@endpush