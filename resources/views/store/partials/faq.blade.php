{{-- resources/views/store/partials/faq.blade.php --}}
@php
    $primary = $primary ?? '#1d4ed8'; // Si no lo envías, default
@endphp
<section class="w-full py-16 bg-gradient-to-b from-white to-blue-50/60">
    <div class="max-w-3xl mx-auto flex flex-col items-center">
        {{-- ICONO ANIMADO FAQ --}}
        <div class="mb-2">
            <span class="inline-flex items-center justify-center rounded-full shadow-lg" style="background:linear-gradient(120deg,{{ $primary }} 60%,#fff 100%); width:68px; height:68px;">
                <i class="fa-solid fa-circle-question text-white text-3xl animate-bounce"></i>
            </span>
        </div>
        <h2 class="text-3xl md:text-4xl font-extrabold text-[var(--primary)] mb-1 tracking-tight text-center drop-shadow">
            Preguntas Frecuentes
        </h2>
        <div class="text-gray-600 text-base md:text-lg mb-8 text-center max-w-lg">
            Las dudas más comunes antes de participar. <b>¡Estamos para ayudarte!</b>
        </div>

        {{-- PREGUNTAS (cada ítem con su propio marco completo) --}}
<div x-data="{ open: null }" class="w-full max-w-3xl space-y-3">
  @foreach([
      ['q'=>'¿Cómo puedo participar en una rifa?','a'=>'Selecciona la rifa de tu interés, escoge tus números disponibles, realiza el pago y confirma tu participación. ¡Es sencillo y rápido!'],
      ['q'=>'¿Cuándo y cómo se realiza el sorteo?','a'=>'El sorteo se realiza en la fecha indicada en cada evento, generalmente en vivo a través de redes sociales o plataformas oficiales. Te notificaremos si resultas ganador.'],
      ['q'=>'¿Qué métodos de pago aceptan?','a'=>'Aceptamos transferencias bancarias, pago móvil, Zelle, Binance y otros métodos especificados en cada rifa. Consulta en la sección "Cuentas de Pago".'],
      ['q'=>'¿Cómo sé si gané?','a'=>'Publicamos los resultados en el sitio y te notificamos directamente si eres uno de los ganadores. Además, puedes verificar tu ticket con nuestro verificador online.'],
      ['q'=>'¿Puedo pedir reembolso?','a'=>'No se realizan reembolsos una vez procesado el pago y confirmada tu participación, salvo que la rifa sea cancelada por motivos ajenos a tu voluntad.'],
  ] as $i => $faq)
    <div
      class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden transition ring-0"
      :class="open === {{ $i }} ? 'ring-1 ring-[var(--primary)]/40' : ''"
    >
      <button
        @click="open === {{ $i }} ? open = null : open = {{ $i }}"
        type="button"
        class="w-full flex items-center justify-between px-6 py-5 text-left font-semibold text-slate-800 hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--primary)]/60"
        :class="open === {{ $i }} ? 'bg-blue-50/70 text-[var(--primary)]' : ''"
        :aria-expanded="open === {{ $i }}"
      >
        <span class="flex items-center">
          <i class="fa-solid fa-circle-question mr-2 text-blue-400"></i>
          {{ $faq['q'] }}
        </span>
        <i class="fa-solid transition-transform duration-200"
           :class="open === {{ $i }} ? 'fa-chevron-up rotate-180' : 'fa-chevron-down'"></i>
      </button>

      {{-- Contenido dentro del mismo marco/borde --}}
      <div x-show="open === {{ $i }}" x-transition
           class="px-6 pb-5 text-gray-600 text-[15px]">
        {{ $faq['a'] }}
      </div>
    </div>
  @endforeach
</div>

        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
