{{-- resources/views/store/partials/rifa-hero.blade.php --}}
<div class="w-full pt-0 pb-12 min-h-[85vh] bg-transparent">
  <div class="max-w-6xl mx-auto px-3 sm:px-8 pt-8 grid grid-cols-1 lg:grid-cols-[420px_1fr] gap-10 items-center">

    {{-- Banner GRANDE --}}
    <div class="flex flex-col items-center justify-start">
      @if($rifa->banner_path)
        <div class="overflow-hidden rounded-2xl shadow-lg bg-transparent"
             style="min-height:440px; max-height:600px; aspect-ratio:4/5; display:flex; align-items:center;">
          <img
            src="{{ Storage::url($rifa->banner_path) }}"
            alt="Banner {{ $rifa->titulo }}"
            class="w-full h-full object-cover"
            loading="lazy"
          >
        </div>
      @endif
    </div>

    {{-- Info / contenido --}}
    <div class="flex flex-col gap-6 items-center justify-center w-full text-[#181A20]">

      {{-- Título CENTRAL CON PRIMARY --}}
      <h1 class="w-full text-center text-4xl md:text-5xl font-extrabold leading-tight mb-2 text-[var(--primary)]">
        {{ $rifa->titulo }}
      </h1>

      {{-- Fecha y hora centradas --}}
      <div class="flex flex-col items-center justify-center w-full mb-2">
        <div class="flex flex-wrap justify-center items-center gap-3 text-lg md:text-xl font-bold">
          <span class="flex items-center gap-2 bg-[#f7f7f7] border border-[#eee] px-4 py-1.5 rounded-xl shadow-sm text-[#222]">
            <i class="fa-solid fa-calendar-days"></i>
            {{ optional($rifa->ends_at)->format('d M Y') ?? '—' }}
          </span>
          <span class="flex items-center gap-2 bg-[#f7f7f7] border border-[#eee] px-4 py-1.5 rounded-xl shadow-sm text-[#222]">
            <i class="fa-solid fa-clock"></i>
            {{ optional($rifa->ends_at)->format('H:i') ?? '—' }}
          </span>
        </div>
        {{-- Lotería centrada debajo --}}
        @if($rifa->lottery_name)
          <span class="mt-3 px-4 py-1.5 rounded-xl bg-[#e8eafd] border border-[#eee] shadow-sm font-semibold text-[var(--primary)] text-center block mx-auto">
            {{ $rifa->lottery_name }}@if($rifa->lottery_type) · {{ $rifa->lottery_type }}@endif
          </span>
        @endif
      </div>

      {{-- Barra de progreso flat --}}
      <div class="w-full flex flex-col gap-1">
        <div class="w-full h-4 rounded-full bg-[#eee] border border-[#e5e7eb] overflow-hidden relative">
          <div class="h-full rounded-full transition-all duration-700"
               style="width: {{ $percent }}%; background: linear-gradient(90deg, var(--primary) 0%, #fca311 100%);">
            <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 font-bold text-xs text-white drop-shadow"
                  style="min-width: 40px;">{{ $percent }}%</span>
          </div>
        </div>
        <div class="flex justify-between text-xs text-[#2b2e32] font-semibold mt-0.5 px-1">
          <span>{{ $paid }} vendidos</span>
          <span>{{ $available }} disponibles ({{ $percent }}%)</span>
        </div>
      </div>

      {{-- Descripción --}}
      @if($rifa->descripcion)
        <p class="mt-2 text-base text-[#222] opacity-90 whitespace-pre-line">{{ $rifa->descripcion }}</p>
      @endif

      {{-- Precio y min/max --}}
      <div class="flex flex-wrap gap-3 mt-2">
        <span class="px-3 py-1 rounded-xl bg-[#fafafa] border border-[#eaeaea]">
          Precio: <strong class="text-[var(--primary)]">${{ number_format($price, 2) }}</strong>
        </span>
        <span class="px-3 py-1 rounded-xl bg-[#fafafa] border border-[#eaeaea]">
          Mín: <strong class="text-[var(--primary)]">{{ $minSel }}</strong>
        </span>
        <span class="px-3 py-1 rounded-xl bg-[#fafafa] border border-[#eaeaea]">
          Máx: <strong class="text-[var(--primary)]">{{ $maxSel }}</strong>
        </span>
      </div>

      {{-- Premios especiales --}}
      @if($premios->isNotEmpty())
        <div class="mt-4 bg-[#f4f8ff] rounded-xl p-4 shadow-sm border border-[#eaeaea] w-full max-w-xl">
          <div class="flex items-center gap-2 text-base font-bold mb-2 text-[var(--primary)]">
            <i class="fa-solid fa-gift"></i>
            Premios especiales
          </div>
          <ul class="divide-y divide-[#eaeaea]">
            @foreach($premios as $p)
              <li class="py-2 flex items-start justify-between gap-3">
                <div>
                  <div class="font-semibold text-[var(--primary)]">{{ $p->title }}</div>
                  <div class="text-xs opacity-70">
                    Lotería: <strong>{{ $p->lottery_name }}</strong>
                    @if($p->lottery_type) · Tipo: <strong>{{ $p->lottery_type }}</strong>@endif
                  </div>
                </div>
                <div class="text-xs text-right opacity-80">
                  {{ optional($p->draw_at)->format('d M Y H:i') ?? '—' }}
                </div>
              </li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Configuración del Store ANTES del botón --}}
      <script>
      window.Store = window.Store || {};
      window.Store.Rifa = {
          postUrl: '{{ route("store.reserve", ["tenant" => $tenant->slug ?? $tenant->id, "rifa" => $rifa->slug]) }}',
          price: {{ $rifa->precio ?? 0 }},
          min: {{ $rifa->min_por_compra ?? 1 }},
          max: {{ $rifa->max_por_compra ?? 999 }},
          minutes: 240,
          pageSize: 200
      };
      
      </script>

      {{-- CTA --}}
      <div class="mt-8 flex justify-center w-full">
        <button
          id="btnBuyNow"
          type="button"
          onclick="
const sheet = document.getElementById('numbersSheet');
const backdrop = document.getElementById('numbersBackdrop');
const panel = document.getElementById('numbersPanel');
if (sheet && backdrop && panel) {
  sheet.classList.remove('hidden');
  requestAnimationFrame(() => {
    backdrop.style.opacity = '1';
    panel.style.transform = 'translateX(-50%) translateY(0)';
    panel.setAttribute('aria-hidden', 'false');
  });
}
"
          class="relative text-lg md:text-xl font-extrabold px-10 py-4 rounded-2xl shadow transition-all duration-300
                 bg-gradient-to-br from-[var(--primary)] to-[#0030b5]
                 hover:from-[#a21b24] hover:to-[#233e92]
                 focus:outline-none focus:ring-4 focus:ring-[var(--primary)]/30
                 uppercase tracking-widest"
        >
          Comprar boletos
          <span class="absolute -right-3 -top-3 bg-white rounded-full shadow text-red-600 font-black px-2 py-1 text-xs border-2 border-red-400 animate-bounce">¡Participa!</span>
        </button>
      </div>

    </div>
  </div>
</div>