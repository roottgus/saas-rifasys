{{-- resources/views/sections/testimonials.blade.php --}}
<section id="testimonios" class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <header class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
                Empresas y organizaciones que <span class="text-blue-600">confían</span> en Rifasys
            </h2>
            <p class="mt-2 text-slate-500 text-base md:text-lg max-w-2xl mx-auto">
                Aliados, fundaciones y emprendedores usan Rifasys para impulsar sus sorteos digitales.
            </p>
        </header>
        @php
            $clientes = [
                ['name' => 'Fundación Sonrisas', 'logo' => 'fundacion-sonrisas.png'],
                ['name' => 'EmprendeYa',         'logo' => 'emprendeya.png'],
                ['name' => 'GanaMás Eventos',     'logo' => 'ganamas-eventos.png'],
                ['name' => 'Fundación Luz',      'logo' => 'fundacion-luz.png'],
                ['name' => 'Club Solidario',      'logo' => 'club-solidario.png'],
                ['name' => 'Eventos Jireh',       'logo' => 'eventos-jireh.png'],
            ];
        @endphp
        <ul class="flex flex-wrap items-center justify-center gap-x-10 gap-y-6">
            @foreach($clientes as $cli)
                <li>
                    <img src="{{ asset('images/clientes/'.$cli['logo']) }}"
                         alt="Logo {{ $cli['name'] }}"
                         title="{{ $cli['name'] }}"
                         class="h-12 md:h-14 w-auto object-contain grayscale hover:grayscale-0 opacity-80 hover:opacity-100 transition duration-300 mx-auto"
                         style="max-width:150px;">
                </li>
            @endforeach
        </ul>
    </div>
</section>
