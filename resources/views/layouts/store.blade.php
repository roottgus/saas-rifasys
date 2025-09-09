{{-- resources/views/layouts/store.blade.php --}}
@php
    /** @var \App\Models\Tenant|null $tenant */
    $tenant = (isset($tenant) && $tenant instanceof \App\Models\Tenant)
        ? $tenant
        : (request()->route('tenant') instanceof \App\Models\Tenant ? request()->route('tenant') : null);

    // Settings por tenant (null-safe)
    $brand = $brand ?? ($tenant?->brandSettings()->first());
    $contact = $contact ?? ($tenant?->contactSettings()->first());

    // Tokens de marca (fallbacks)
    $primary = $brand?->color_primary ?: '#1d4ed8';
    $isDark = ($brand?->mode ?? 'light') === 'dark';
    $bg = $isDark ? '#0b0b0f' : '#f9fafb'; // BLANCO CÁLIDO
    $text = $isDark ? '#f3f4f6' : '#0b1220';

    // Base de navegación
    $base = $tenant ? url('/t/'.$tenant->slug) : url('/');

    // Rutas activas (soporta prefijo /t/{slug})
    $path = trim(request()->path(), '/');
    $homeActive = $tenant
        ? $path === ('t/'.$tenant->slug)
        : $path === '';
    $verifyActive = $tenant
        ? str_starts_with($path, 't/'.$tenant->slug.'/verify')
        : str_starts_with($path, 'verify');

    // Imagen de fondo tileada (por rifa, tenant, o global)
    $bgImage = $bgImage ?? ($brand?->bg_image_path ? \Illuminate\Support\Facades\Storage::url($brand->bg_image_path) : null);

@endphp
<!doctype html>
<html lang="es" class="h-full" data-scope="store">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? ($tenant?->name ?? 'Rifas') }}</title>
    <meta name="theme-color" content="{{ $primary }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <!-- Fuente Inter de Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>

    <style>[x-cloak]{display:none!important;}</style>

    @vite([
        'resources/css/app.css',
        'resources/js/store/index.js',
    ])
    @stack('styles')

    {{-- Variables de marca dinámicas --}}
    <style>
        :root{
            --primary: {{ $primary }};
            --bg: {{ $bg }};
            --text: {{ $text }};
        }
    </style>
</head>
<body
    class="min-h-full text-[var(--text)] antialiased"
    style="
        background: url('{{ $bgImage }}') center repeat;
        background-size: 420px 320px;
        background-attachment: fixed;
    "
>
    @stack('body-bg')

   {{-- ====== TOPBAR ====== --}}
<header class="sticky top-0 z-50 w-full">
    <div class="w-full h-[7px] bg-[var(--primary)]"
         style="box-shadow: 0 4px 16px 0 rgba(0,0,0,0.25), 0 -4px 16px 0 rgba(0,0,0,0.21);"></div>
    <div class="relative bg-white border-b border-gray-200 flex flex-col items-center pb-2"
         style="box-shadow: 0 9px 32px -4px rgba(0,0,0,0.19), 0 -9px 32px -4px rgba(0,0,0,0.17);">

        <div class="max-w-7xl mx-auto w-full flex items-center px-3 sm:px-8 pt-5 pb-1 relative min-h-[60px]">

            {{-- Botón hamburguesa solo móvil --}}
            <button id="openDrawer"
                class="md:hidden inline-flex items-center justify-center rounded-full px-2.5 py-2.5 border border-[var(--primary)]/30 text-[var(--primary)] bg-white shadow-sm hover:bg-[var(--primary)]/10 transition absolute left-2 top-2 z-20"
                aria-label="Abrir menú" aria-expanded="false" aria-controls="drawer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- FLEX: MENU COMPLETO IZQUIERDA + LOGO CENTRADO --}}
            <div class="flex w-full items-center relative">

                {{-- MENU IZQUIERDA (INICIO, VERIFICADOR, CONTACTO) --}}
                <nav class="hidden md:flex items-center gap-4 pr-3">
                    <a href="{{ $base }}"
                       class="flex items-center gap-2 px-4 py-2 group hover:text-[var(--primary)] transition {{ $homeActive ? 'text-[var(--primary)] font-bold' : 'text-gray-700' }}">
                        <i class="fa-solid fa-house text-xl"></i>
                        <div class="flex flex-col leading-tight">
                            <span class="font-extrabold text-[13px] uppercase tracking-wide">INICIO</span>
                            <span class="text-[11px] font-normal text-gray-400 group-hover:text-[var(--primary)]">Panel principal</span>
                        </div>
                    </a>
                    <a href="{{ $base.'/verify' }}"
                       class="flex items-center gap-2 px-4 py-2 group hover:text-[var(--primary)] transition {{ $verifyActive ? 'text-[var(--primary)] font-bold' : 'text-gray-700' }}">
                        <i class="fa-solid fa-magnifying-glass text-xl"></i>
                        <div class="flex flex-col leading-tight">
                            <span class="font-extrabold text-[13px] uppercase tracking-wide">VERIFICADOR</span>
                            <span class="text-[11px] font-normal text-gray-400 group-hover:text-[var(--primary)]">Verifica tu ticket</span>
                        </div>
                    </a>
                    <a href="{{ $contact?->whatsapp ?? '#' }}"
                       class="flex items-center gap-2 px-4 py-2 group hover:text-[var(--primary)] transition text-gray-700">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                        <div class="flex flex-col leading-tight">
                            <span class="font-extrabold text-[13px] uppercase tracking-wide">CONTACTO</span>
                            <span class="text-[11px] font-normal text-gray-400 group-hover:text-[var(--primary)]">WhatsApp / Soporte</span>
                        </div>
                    </a>
                </nav>

                {{-- LOGO CENTRADO FLOTANTE --}}
                @if($brand?->logo_path)
                    <div class="absolute left-1/2 -translate-x-1/2" style="top:-5px; z-index: 30;">
                        <span class="rounded-full blur-xl opacity-50 absolute
                            w-28 h-28 sm:w-28 sm:h-28 lg:w-32 lg:h-32"
                            style="background: radial-gradient(circle, rgba(60,130,255,0.12) 60%, transparent 100%); top: 8px; left: 0; right: 0; margin: auto; z-index: 0;">
                        </span>
                        <img
    src="{{ \Illuminate\Support\Facades\Storage::url($brand->logo_path) }}"
    alt="Logo {{ $tenant?->name }}"
    class="rounded-full ring-4 bg-white object-contain select-none pointer-events-none relative z-10 w-24 h-24 shadow-2xl"
    style="
        border: 4px solid {{ $primary }};
        box-shadow: 0 8px 40px 0 rgba(32,42,62,0.22), 0 0px 0px 0px #fff, 0 1.5px 40px 4px {{ $primary }}44;
    "
/>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="w-full h-[7px] bg-[var(--primary)]"
         style="box-shadow: 0 8px 24px 0 rgba(0,0,0,0.30), 0 -8px 24px 0 rgba(0,0,0,0.27);"></div>
</header>

{{-- Drawer móvil --}}
<div id="drawer" class="fixed inset-0 z-50 hidden md:hidden bg-black/40" aria-hidden="true">
    <div class="absolute right-0 top-0 h-full w-72 bg-white shadow-2xl p-4 flex flex-col">
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-semibold" id="drawerTitle">{{ $tenant?->name ?? 'Rifas' }}</div>
            <button id="closeDrawer" class="rounded-full border px-3 py-1 text-sm" aria-label="Cerrar">✕</button>
        </div>
        <div class="flex flex-col gap-2 flex-1">
            <a href="{{ $base }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg font-semibold text-[var(--text)] hover:bg-[var(--primary)]/10 transition {{ $homeActive ? 'bg-[var(--primary)]/10' : '' }}">
                <i class="fa-solid fa-house text-xl w-7 text-[var(--primary)]"></i>
                <span>
                    INICIO
                    <span class="block text-xs font-normal text-gray-500">Panel principal</span>
                </span>
            </a>
            <a href="{{ $base.'/verify' }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg font-semibold text-[var(--text)] hover:bg-[var(--primary)]/10 transition {{ $verifyActive ? 'bg-[var(--primary)]/10' : '' }}">
                <i class="fa-solid fa-magnifying-glass text-xl w-7 text-[var(--primary)]"></i>
                <span>
                    VERIFICADOR
                    <span class="block text-xs font-normal text-gray-500">Verifica tu ticket</span>
                </span>
            </a>
            <a href="{{ $contact?->whatsapp ?? '#' }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg font-semibold text-[var(--text)] hover:bg-[var(--primary)]/10 transition">
                <i class="fa-brands fa-whatsapp text-xl w-7 text-green-500"></i>
                <span>
                    CONTACTO
                    <span class="block text-xs font-normal text-gray-500">WhatsApp / Soporte</span>
                </span>
            </a>
        </div>
    </div>
</div>



    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const openBtn  = document.getElementById('openDrawer');
        const closeBtn = document.getElementById('closeDrawer');
        const drawer   = document.getElementById('drawer');
        const html     = document.documentElement;
        const header   = document.querySelector('header');
        const main     = document.querySelector('main');
        const isInDrawer = (el) => !!(el && drawer && drawer.contains(el));
        const setOpen = (open) => {
          if (!drawer) return;
          if (open) {
            drawer.classList.remove('hidden');
            drawer.removeAttribute('aria-hidden');
            openBtn?.setAttribute('aria-expanded', 'true');
            header?.setAttribute('inert', '');
            main?.setAttribute('inert', '');
            html.classList.add('overflow-y-hidden');
            closeBtn?.focus();
          } else {
            if (isInDrawer(document.activeElement)) {
              openBtn?.focus();
            }
            drawer.setAttribute('aria-hidden', 'true');
            drawer.classList.add('hidden');
            openBtn?.setAttribute('aria-expanded', 'false');
            header?.removeAttribute('inert');
            main?.removeAttribute('inert');
            html.classList.remove('overflow-y-hidden');
          }
        };
        openBtn?.addEventListener('click', (e) => { e.preventDefault(); setOpen(true); });
        closeBtn?.addEventListener('click', () => setOpen(false));
        drawer?.addEventListener('click', (e) => { if (e.target === drawer) setOpen(false); });
        window.addEventListener('keydown', (e) => { if (e.key === 'Escape') setOpen(false); });
      });
    </script>

    {{-- ======= MAIN CENTRAL ÚNICO "GLASS" ======= --}}
    <main class="min-h-screen w-full pt-6 md:pt-10 pb-10">
    @yield('content')
</main>


    @include('store.partials.terms-modal')
    @include('store.partials.modal-reserva-pago')
    <x-store.footer :contact="$contact" :brand="$brand" />
    <x-store.whatsapp-float :contact="$contact" />

    @stack('modals')

       <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    @stack('scripts')

</body>
</html>
