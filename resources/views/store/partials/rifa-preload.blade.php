<div id="preAnim" class="pre-anim fixed inset-0 z-[100] grid place-items-center bg-black/30 backdrop-blur-sm hidden" aria-hidden="true">
  <div class="bg-white/100 shadow-2xl rounded-2xl px-7 py-6 flex flex-col items-center gap-4 animate-fadein w-[320px] max-w-[90vw]">
    {{-- Icono SVG animado central (más grande) --}}
    <div class="mb-1 flex justify-center">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 64 64"
        width="80"
        height="80"
        class="animate-bounce drop-shadow-xl"
        style="color: var(--primary); animation-duration:1.2s;"
      >
        <!-- Fondo suave del ticket -->
        <rect x="10" y="18" width="44" height="28" rx="5" fill="currentColor" fill-opacity="0.11"/>
        <!-- Cuerpo principal -->
        <rect x="14" y="22" width="36" height="20" rx="3" fill="currentColor" fill-opacity="0.85"/>
        <!-- Detalles blancos (círculos y rectángulos simulando números y troquel) -->
        <circle cx="20" cy="32" r="3" fill="#fff"/>
        <rect x="26" y="29" width="12" height="6" rx="2" fill="#fff" />
        <circle cx="44" cy="32" r="3" fill="#fff"/>
        <rect x="30" y="40" width="4" height="2" rx="1" fill="#fff" opacity="0.9"/>
        <rect x="30" y="22" width="4" height="2" rx="1" fill="#fff" opacity="0.9"/>
      </svg>
    </div>
    <div class="text-center">
      <div class="font-black text-lg text-[var(--primary)]">Preparando tus números…</div>
      <div class="text-xs text-[var(--primary)]/70 mt-1">Esto tarda menos de un segundo</div>
    </div>
    {{-- Barra de progreso animada --}}
    <div class="w-full h-2 rounded-full border-2 border-[var(--primary)] bg-gradient-to-r from-[var(--primary)]/20 via-[var(--primary)]/10 to-[var(--primary)]/30 overflow-hidden mt-2 mb-1">
      <div class="h-full bg-gradient-to-r from-[var(--primary)] to-[var(--primary)]/80 animate-progressbar"></div>
    </div>
  </div>
</div>

