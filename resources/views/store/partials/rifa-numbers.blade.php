{{-- ========== PRE-ANIMACIÓN (overlay para “Suerte” y “Comprar boletos”) ========== --}}
<div id="preNumbers" class="fixed inset-0 z-[80] hidden grid place-items-center bg-black/40 backdrop-blur-sm" aria-hidden="true">
  <div class="bg-white/95 rounded-2xl shadow-2xl p-6 w-full max-w-xs flex flex-col items-center gap-4">
    <div class="flex flex-col items-center mb-2">
      {{-- SVG ICONO animado, grande y azul --}}
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 64 64" width="80" height="80"
           class="animate-bounce drop-shadow-xl" style="color: var(--primary); animation-duration:1.2s;">
        <rect x="10" y="18" width="44" height="28" rx="5" fill="currentColor" fill-opacity="0.11"/>
        <rect x="14" y="22" width="36" height="20" rx="3" fill="currentColor" fill-opacity="0.85"/>
        <circle cx="20" cy="32" r="3" fill="#fff"/>
        <rect x="26" y="29" width="12" height="6" rx="2" fill="#fff" />
        <circle cx="44" cy="32" r="3" fill="#fff"/>
        <rect x="30" y="40" width="4" height="2" rx="1" fill="#fff" opacity="0.9"/>
        <rect x="30" y="22" width="4" height="2" rx="1" fill="#fff" opacity="0.9"/>
      </svg>
      <div class="font-bold pre-anim__title mt-2 text-[var(--primary)]">Preparando tus números…</div>
      <div class="text-xs pre-anim__sub text-[var(--primary)]/70">Esto tarda menos de un segundo</div>
    </div>
    <div class="w-full h-2 rounded-full border-2 border-[var(--primary)] bg-gradient-to-r from-[var(--primary)]/20 via-[var(--primary)]/10 to-[var(--primary)]/30 overflow-hidden mt-2 mb-1">
      <div class="h-full bg-gradient-to-r from-[var(--primary)] to-[var(--primary)]/80 animate-progressbar"></div>
    </div>
  </div>
</div>

{{-- ========== BOTTOM-SHEET MODAL (mobile/desktop) ========== --}}
<div id="numbersSheet" class="fixed inset-0 z-[70] hidden backdrop-blur-sm" aria-hidden="true">
  <div id="numbersBackdrop" class="absolute inset-0 bg-black/60 opacity-0 transition-opacity"></div>

  <div id="numbersPanel"
     class="fixed bottom-0 bg-white rounded-t-2xl shadow-2xl max-h-[88vh] overflow-y-auto border-t border-gray-200 w-full
            sm:w-[480px] md:w-[680px] lg:w-[820px] xl:w-[900px]
            transition-all duration-200"
     style="left: 50% !important; transform: translateX(-50%) translateY(100%) !important; z-index: 75 !important;"
     role="dialog" aria-modal="true" aria-labelledby="numbersTitle">

    {{-- Sticky header --}}
    <div class="sticky top-0 bg-white z-20 pb-2 border-b border-gray-100">
      <div class="flex items-center justify-between w-full pt-2 px-2 gap-2">
        <span class="opacity-10 flex items-center">
          <svg width="95" height="90" viewBox="0 0 512 512" fill="none" stroke="#2061c9" xmlns="http://www.w3.org/2000/svg">
            <path fill="none" stroke="#cccccc" stroke-miterlimit="10" stroke-width="60" d="M366.05,146a46.7,46.7,0,0,1-2.42-63.42,3.87,3.87,0,0,0-.22-5.26L319.28,33.14a3.89,3.89,0,0,0-5.5,0l-70.34,70.34a23.62,23.62,0,0,0-5.71,9.24h0a23.66,23.66,0,0,1-14.95,15h0a23.7,23.7,0,0,0-9.25,5.71L33.14,313.78a3.89,3.89,0,0,0,0,5.5l44.13,44.13a3.87,3.87,0,0,0,5.26.22,46.69,46.69,0,0,1,65.84,65.84,3.87,3.87,0,0,0,.22,5.26l44.13,44.13a3.89,3.89,0,0,0,5.5,0l180.4-180.39a23.7,23.7,0,0,0,5.71-9.25h0a23.66,23.66,0,0,1,14.95-15h0a23.62,23.62,0,0,0,9.24-5.71l70.34-70.34a3.89,3.89,0,0,0,0-5.5l-44.13-44.13a3.87,3.87,0,0,0-5.26-.22A46.7,46.7,0,0,1,366.05,146Z"></path>
          </svg>
        </span>

        <div class="flex-1 flex flex-col items-center justify-center gap-2">
          <div class="uppercase text-[20px] font-black tracking-wide mb-1">LISTA DE BOLETOS</div>
          <div class="flex items-center justify-center gap-3">
            <button type="button" id="qtyDec" class="w-10 h-10 rounded-full border-2 border-blue-200 text-blue-700 font-black text-2xl bg-white flex items-center justify-center">−</button>
            <div class="flex flex-col items-center">
              <div class="w-12 h-12 rounded-full bg-gradient-to-b from-blue-600 to-blue-900 text-white font-black flex items-center justify-center" id="hSelCount">0</div>
              <div class="text-[12px] font-black opacity-70 uppercase">BOLETOS</div>
            </div>
            <button type="button" id="qtyInc" class="w-10 h-10 rounded-full border-2 border-blue-200 text-blue-700 font-black text-2xl bg-white flex items-center justify-center">+</button>
          </div>
        </div>

        <span class="opacity-10 flex items-center">
          <svg width="95" height="90" viewBox="0 0 512 512" fill="none" stroke="#2061c9" xmlns="http://www.w3.org/2000/svg">
            <path fill="none" stroke="#cccccc" stroke-miterlimit="10" stroke-width="60" d="M366.05,146a46.7,46.7,0,0,1-2.42-63.42,3.87,3.87,0,0,0-.22-5.26L319.28,33.14a3.89,3.89,0,0,0-5.5,0l-70.34,70.34a23.62,23.62,0,0,0-5.71,9.24h0a23.66,23.66,0,0,1-14.95,15h0a23.7,23.7,0,0,0-9.25,5.71L33.14,313.78a3.89,3.89,0,0,0,0,5.5l44.13,44.13a3.87,3.87,0,0,0,5.26.22,46.69,46.69,0,0,1,65.84,65.84,3.87,3.87,0,0,0,.22,5.26l44.13,44.13a3.89,3.89,0,0,0,5.5,0l180.4-180.39a23.7,23.7,0,0,0,5.71-9.25h0a23.66,23.66,0,0,1,14.95-15h0a23.62,23.62,0,0,0,9.24-5.71l70.34-70.34a3.89,3.89,0,0,0,0-5.5l-44.13-44.13a3.87,3.87,0,0,0-5.26-.22A46.7,46.7,0,0,1,366.05,146Z"></path>
          </svg>
        </span>
      </div>

      {{-- BUSCADOR (Alpine) --}}
      <div x-data="{ open: false, search: '' }" class="flex items-center justify-center w-full px-4 mb-2 relative z-10">
        {{-- Tu buscador original aquí --}}
      </div>

      <div class="flex justify-center my-2">
        <button id="btnLuck" type="button"
          class="h-10 px-6 rounded-full border-2 font-black transition flex items-center gap-2 text-base bg-white hover:bg-blue-50"
          style="border-color:var(--primary); color:var(--primary);">
          <i class="fa-solid fa-star" style="color:var(--primary);"></i>
          ELEGIR A LA SUERTE
          <i class="fa-solid fa-star" style="color:var(--primary);"></i>
        </button>
      </div>
    </div>

    {{-- GRID (paginado por JS) --}}
    @php
      $totalCount = $nums instanceof \Illuminate\Support\Collection ? $nums->count() : count($nums);
      $pad = $totalCount >= 10000 ? 4 : ($totalCount >= 1000 ? 3 : ($totalCount >= 100 ? 2 : 0));
    @endphp

    <div id="numbersGrid" class="mt-2 grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-1.5 bg-white rounded-xl p-2 border border-blue-100 shadow-sm"
         aria-label="Números disponibles" tabindex="0" style="outline: none;">
      @foreach($nums as $n)
        @php
          $raw   = $n->estado;
          $state = $raw instanceof \BackedEnum ? $raw->value : (string) $raw;
          $num   = (int) $n->numero;
          $label = $pad ? str_pad((string)$num, $pad, '0', STR_PAD_LEFT) : (string)$num;
          $isFree = $state === 'disponible';
        @endphp
        <button
          class="num text-base font-black rounded-lg border select-none transition h-11
            @if($state==='disponible') bg-white border-blue-100 text-gray-800 hover:bg-blue-50 cursor-pointer @endif
            @if($state==='reservado') bg-yellow-100 border-yellow-200 text-yellow-700 opacity-80 cursor-not-allowed @endif
            @if($state==='pagado')    bg-gray-100 border-gray-200 text-gray-400 opacity-60 cursor-not-allowed @endif
            @if($state==='selected')  bg-[var(--primary)] text-white border-[var(--primary)] ring-2 ring-[var(--primary)] brightness-110 shadow @endif"
          style="min-width: 44px"
          type="button"
          data-num="{{ $num }}"
          data-label="{{ $label }}"
          data-state="{{ $state }}"
          @disabled(! $isFree)
          aria-pressed="false"
          aria-label="Número {{ $label }} {{ $state }}"
        >{{ $label }}</button>
      @endforeach
    </div>

    {{-- Paginador --}}
    <div class="pager flex items-center justify-center gap-2 mt-2 mb-1">
      <button class="w-8 h-8 rounded-full border bg-white text-xl text-blue-800" data-act="first" aria-label="Primera">«</button>
      <button class="w-8 h-8 rounded-full border bg-white text-xl text-blue-800" data-act="prev" aria-label="Anterior">‹</button>
      <div class="min-w-[80px] px-2 py-1 rounded-full bg-blue-700 text-white font-bold text-xs pager-info"><span class="pi-text">Pag: 1/1</span></div>
      <button class="w-8 h-8 rounded-full border bg-white text-xl text-blue-800" data-act="next" aria-label="Siguiente">›</button>
      <button class="w-8 h-8 rounded-full border bg-white text-xl text-blue-800" data-act="last" aria-label="Última">»</button>
    </div>

    {{-- Footer --}}
    <div class="sticky bottom-0 left-0 right-0 bg-white/95 z-20 mt-4 flex items-center justify-between gap-3 rounded-b-2xl border-t border-gray-100 p-3">
      <div class="flex flex-col items-center min-w-[104px] bg-white px-4 py-1.5 rounded-xl border border-gray-100 shadow">
        <div class="font-black text-lg text-black"><span id="selCount">0</span> de {{ $maxSel }}</div>
        <div class="text-[11px] opacity-70 font-black uppercase">Seleccionados</div>
      </div>
      <div class="flex flex-col items-end min-w-[100px]">
        <span class="text-xs opacity-70 uppercase">Total</span>
        <span class="font-black text-xl text-blue-700" id="totalAmount">$0.00</span>
      </div>

      {{-- Fallback visual si no hay endpoint --}}
      @php $hasEndpoint = !empty($reserveUrl); @endphp
      <button id="btnReserve" type="button"
        class="h-11 px-7 rounded-full bg-gradient-to-b from-blue-600 to-blue-800 text-white font-black shadow-xl hover:brightness-110 transition disabled:opacity-60"
        {{ $hasEndpoint ? '' : 'disabled' }}>
        Continuar
      </button>
    </div>
  </div>
</div>



{{-- ========== MODAL PROFESIONAL: RESERVAR O PAGAR AHORA ========== --}}
@include('store.partials.modal-reserva-pago')

{{-- Helpers JS para abrir/cerrar sheet + fallback de postUrl --}}
<script>
(function () {
  const sheet    = document.getElementById('numbersSheet');
  const backdrop = document.getElementById('numbersBackdrop');
  const panel    = document.getElementById('numbersPanel');

  // Estados de transición
  if (panel && !panel.style.transform) panel.style.transform = 'translateY(100%)';

  function openSheet() {
    if (!sheet || !panel || !backdrop) return;
    sheet.classList.remove('hidden');
    requestAnimationFrame(() => {
      backdrop.style.opacity = '1';
      panel.style.transform  = 'translateY(0)';
      panel.setAttribute('aria-hidden','false');
    });
  }
  function closeSheet() {
    if (!sheet || !panel || !backdrop) return;
    backdrop.style.opacity = '0';
    panel.style.transform  = 'translateY(100%)';
    panel.setAttribute('aria-hidden','true');
    setTimeout(() => sheet.classList.add('hidden'), 200);
  }

  window.__openNumbersSheet  = openSheet;
  window.__closeNumbersSheet = closeSheet;

  // Cerrar si clic en backdrop
  backdrop?.addEventListener('click', closeSheet);
})();
</script>