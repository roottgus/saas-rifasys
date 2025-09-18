{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- SEO Meta Tags --}}
    <title>@yield('title', 'Rifasys - Tu Negocio de Rifas Online | Plataforma #1')</title>
    <meta name="description" content="@yield('description', 'Vende, gestiona y crece con la plataforma más completa para rifas digitales. Automatiza todo el proceso y maximiza tus ganancias.')">
    <meta name="keywords" content="@yield('keywords', 'rifas online, sorteos digitales, plataforma rifas, gestión rifas, vender rifas')">
    
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Rifasys - Plataforma #1 de Rifas Digitales')">
    <meta property="og:description" content="@yield('og_description', 'Sistema completo con pagos integrados, sorteos transparentes y análisis en tiempo real.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    
    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('twitter_title', 'Rifasys - Plataforma #1 de Rifas Digitales')">
    <meta property="twitter:description" content="@yield('twitter_description', 'Sistema completo con pagos integrados y sorteos transparentes.')">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    
    {{-- Preload critical fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Assets compilados con Vite (Tailwind via PostCSS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Font Awesome Icons - Version correcta --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
          crossorigin="anonymous" 
          referrerpolicy="no-referrer" />
    
    {{-- Estilos personalizados --}}
    @includeWhen(View::exists('partials.styles'), 'partials.styles')
    
    {{-- Stack para estilos adicionales --}}
    @stack('styles')
    @stack('head')
    
    {{-- PWA Support REMOVIDO (no manifest, no theme-color) --}}
    {{-- <link rel="manifest" href="{{ asset('manifest.json') }}"> --}}
    {{-- <meta name="theme-color" content="#1e3a8a"> --}}
</head>
<body class="bg-gray-50 text-slate-800 antialiased font-['Poppins']">
    {{-- Skip to content (Accesibilidad) --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded">
        Saltar al contenido principal
    </a>
    
    {{-- Navigation --}}
    @include('partials.navbar')
    
    {{-- Main Content --}}
    <main id="main-content" class="min-h-screen">
        @yield('content')
    </main>
    
    {{-- Footer --}}
    @include('partials.footer')
    
    {{-- Back to Top Button --}}
    <button id="back-to-top" 
            class="fixed bottom-8 right-8 bg-blue-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 opacity-0 invisible z-50"
            aria-label="Volver arriba">
        <i class="fas fa-chevron-up"></i>
    </button>
    
    {{-- Scripts globales --}}
    @includeWhen(View::exists('partials.scripts'), 'partials.scripts')
    
    {{-- Back to top functionality --}}
    <script>
        // Back to top button
        (function() {
            const backToTop = document.getElementById('back-to-top');
            if (!backToTop) return;
            
            window.addEventListener('scroll', () => {
                if (window.scrollY > 500) {
                    backToTop.classList.remove('opacity-0', 'invisible');
                    backToTop.classList.add('opacity-100', 'visible');
                } else {
                    backToTop.classList.add('opacity-0', 'invisible');
                    backToTop.classList.remove('opacity-100', 'visible');
                }
            });
            
            backToTop.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        })();
    </script>
    
    {{-- Stack para scripts adicionales --}}
    @stack('scripts')
    
    {{-- Google Analytics (opcional - reemplaza con tu ID) --}}
    @if(config('app.env') === 'production')
    <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID');
    </script>
    @endif
</body>
</html>
