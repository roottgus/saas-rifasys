{{-- resources/views/sections/logos-carousel.blade.php --}}
<section class="bg-white py-10 overflow-hidden">
    <div class="container mx-auto px-6">
        <p class="text-center text-gray-600 mb-8 font-semibold tracking-wide">LOTERIAS NACIONALES-INTERNACIONALES</p>

        @php
            // Puedes mover esto a config/lotteries.php y usar config('lotteries.logos')
            $logos = [
                ['name' => 'Conalot', 'file' => 'conalot.png'],
                ['name' => 'Super Gana',      'file' => 'super-gana.png'],
                ['name' => 'Triple Caracas',      'file' => 'triple-caracas.png'],
                ['name' => 'Triple Chance',     'file' => 'triple-chance.png'],
                ['name' => 'Triple Tachira',             'file' => 'triple-tachira.png'],
                ['name' => 'Triple Zamorano',     'file' => 'triple-zamorano.png'],
                ['name' => 'Triple Zulia',             'file' => 'triple-zulia.png'],
            ];
        @endphp

        <div class="relative">
            <div class="logos-track flex items-center gap-12 will-change-transform">
                {{-- Set 1 --}}
                <div class="flex items-center gap-12">
                    @foreach ($logos as $logo)
                        <div class="bg-gray-50 px-6 py-4 rounded-xl border border-gray-100 shadow-sm/0 hover:shadow-sm transition">
                            <img
    src="{{ asset('images/loterias/' . $logo['file']) }}"
    alt="{{ $logo['name'] }}"
    title="{{ $logo['name'] }}"
    class="h-12 w-auto object-contain transition"
    loading="lazy"
/>

                        </div>
                    @endforeach
                </div>

                {{-- Set 2 (duplicado para loop infinito) --}}
                <div class="flex items-center gap-12" aria-hidden="true">
                    @foreach ($logos as $logo)
                        <div class="bg-gray-50 px-6 py-4 rounded-xl border border-gray-100 shadow-sm/0">
                            <img
    src="{{ asset('images/loterias/' . $logo['file']) }}"
    alt="{{ $logo['name'] }}"
    title="{{ $logo['name'] }}"
    class="h-12 w-auto object-contain transition"
    loading="lazy"
/>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Estilos del carrusel (puedes moverlos a tu CSS/Tailwind @layer) --}}
    <style>
        @keyframes logos-marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); } /* 50% porque duplicamos el set */
        }
        .logos-track {
            width: max-content;
            animation: logos-marquee 22s linear infinite;
        }
        .logos-track:hover {
            animation-play-state: paused; /* pausa al hover para UX */
        }
    </style>
</section>
