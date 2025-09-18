{{-- resources/views/sections/how-it-works.blade.php --}}
<section id="como-funciona" class="relative py-24 bg-gradient-to-b from-white to-slate-50">
    {{-- halo decorativo sutil --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 -top-40 -z-10 h-64 blur-3xl"
         style="background: radial-gradient(60% 60% at 50% 40%, rgba(59,130,246,.15), rgba(124,58,237,.10), transparent 70%);">
    </div>

    <div class="container mx-auto px-6">
        {{-- Encabezado --}}
        <header class="text-center max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight">
                ¿Cómo <span class="text-gradient">funciona?</span>
            </h2>
            <p class="mt-4 text-lg text-slate-600">
                Lanza tu rifa en minutos: proceso guiado, pagos integrados y todo listo para vender.
            </p>
        </header>

        {{-- Steps + línea conectora en desktop --}}
        <div class="relative mt-16">
            <div aria-hidden="true"
                 class="hidden md:block absolute left-1/2 top-8 -z-10 h-px w-[92%] -translate-x-1/2
                        bg-gradient-to-r from-blue-200 via-indigo-200 to-fuchsia-200">
            </div>

            @php
                $steps = [
                    [
                        'title' => 'Regístrate',
                        'description' => 'Crea tu cuenta gratis y configura tu perfil.',
                        'icon' => 'fas fa-user-plus',
                        'bg' => 'from-blue-600 to-indigo-600',
                        'accent' => 'blue',
                    ],
                    [
                        'title' => 'Crea tu rifa',
                        'description' => 'Define premio, precio y números en segundos.',
                        'icon' => 'fas fa-ticket-alt',
                        'bg' => 'from-indigo-600 to-fuchsia-600',
                        'accent' => 'indigo',
                    ],
                    [
                        'title' => 'Comparte y vende',
                        'description' => 'Enlace listo para redes, QR y WhatsApp.',
                        'icon' => 'fas fa-share-alt',
                        'bg' => 'from-fuchsia-600 to-pink-600',
                        'accent' => 'fuchsia',
                    ],
                    [
                        'title' => 'Sortea y paga',
                        'description' => 'Resultados transparentes y comprobante al ganador.',
                        'icon' => 'fas fa-trophy',
                        'bg' => 'from-amber-500 to-orange-500',
                        'accent' => 'amber',
                    ],
                ];
            @endphp

            <ol role="list" class="grid gap-6 md:grid-cols-4">
                @foreach ($steps as $i => $s)
                    <li class="group relative rounded-2xl bg-white p-6 ring-1 ring-slate-200/80 shadow-sm
           hover:shadow-lg transition duration-300 reveal text-center"

                        style="animation-delay: {{ $i * 120 }}ms">
                        {{-- Badge numerado --}}
                        <div class="flex items-center justify-center -mt-14 mb-3">
                            <span class="inline-flex h-16 w-16 items-center justify-center rounded-full
                                         bg-gradient-to-br {{ $s['bg'] }} text-white text-2xl font-bold
                                         ring-8 ring-white shadow-lg">
                                {{ $i + 1 }}
                            </span>
                        </div>

                        {{-- Icono temático --}}
                        <div class="mb-3 flex justify-center text-2xl text-{{ $s['accent'] }}-600/90">
                            <i class="{{ $s['icon'] }}" aria-hidden="true"></i>
                        </div>

                        <h3 class="text-lg font-semibold text-slate-900">{{ $s['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            {{ $s['description'] }}
                        </p>

                        {{-- Hover sutil --}}
                        <div aria-hidden="true"
                             class="pointer-events-none absolute inset-0 rounded-2xl opacity-0
                                    group-hover:opacity-100 transition
                                    ring-1 ring-{{ $s['accent'] }}-500/20"></div>
                    </li>
                @endforeach
            </ol>
        </div>

        {{-- CTA final: visual (sin enlace) + demo en vivo (sí es enlace) --}}
<div class="mt-14 flex flex-col sm:flex-row items-center justify-center gap-4">
    <div class="inline-flex items-center justify-center gap-2 rounded-xl px-7 py-3 font-semibold
                text-white bg-gradient-to-r from-blue-600 to-indigo-600
                cursor-default select-none text-lg shadow-md">
        <i class="fas fa-bolt" aria-hidden="true"></i>
        ¡Empieza Hoy Mismo!
    </div>
    <a href="https://rifasys.com/t/rifasys" target="_blank" rel="noopener noreferrer"
       aria-label="Abrir demo en vivo de Rifasys en una nueva pestaña"
       class="group inline-flex items-center gap-2 px-7 py-3 rounded-xl font-semibold
              text-slate-800 bg-white border border-slate-200 hover:border-slate-300
              hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-slate-300 transition">
        <span class="relative h-5 w-5">
            <span class="absolute inset-0 rounded-full bg-red-500/90 animate-ping" aria-hidden="true"></span>
            <i class="fas fa-play-circle relative" aria-hidden="true"></i>
        </span>
        Ver demo en vivo
    </a>
</div>


    {{-- utilidades locales: gradiente de texto + animación reveal --}}
    <style>
       
        @keyframes fadeUp{to{opacity:1;transform:none}}
        .reveal{opacity:0;transform:translateY(10px);animation:fadeUp .6s ease-out both}
        @media (prefers-reduced-motion: reduce){
            .reveal{animation:none;opacity:1;transform:none}
        }
    </style>
</section>
