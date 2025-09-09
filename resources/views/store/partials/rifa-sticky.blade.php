{{-- Sticky checkout (móvil, solo visible con selección) --}}
<div
  id="stickyBar"
  x-data="{ show: false, count: 0, total: 0 }"
  x-init="
    // Escucha eventos globales personalizados para actualizar la barra
    window.addEventListener('selection:update', e => {
      count = e.detail.count;
      total = e.detail.total;
      show = count > 0;
    });
  "
  :class="show ? 'translate-y-0' : 'translate-y-full'"
  class="fixed inset-x-0 bottom-0 z-40 sm:hidden transition-transform duration-200"
  aria-hidden="true"
>
  <div class="mx-auto max-w-6xl px-4 pb-3">
    <div class="flex items-center gap-3 rounded-2xl border border-black/10 bg-white/90 backdrop-blur p-3 shadow-lg">
      <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-black/10">
        <span class="text-xs opacity-70">SELECCIONADOS</span>
        <strong x-text="count"></strong>
      </div>
      <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-black/10">
        <span class="text-xs opacity-70">TOTAL</span>
        <strong x-text="'$' + total.toFixed(2)"></strong>
      </div>
      <button
        class="btn btn-primary ml-auto"
        type="button"
        :disabled="count === 0"
        @click="window.dispatchEvent(new CustomEvent('checkout:open'))"
      >
        Continuar
      </button>
    </div>
  </div>
</div>
