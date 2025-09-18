<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Firma Digital de Contrato') | Rifasys - Publicidad en Red C.A.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Plataforma segura de firma digital de contratos - Rifasys by Publienred C.A.">
    <meta name="keywords" content="firma digital, contrato electrónico, rifas, sorteos, Costa Rica">
    <meta name="author" content="Publicidad en Red C.A.">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    
    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="Firma Digital de Contrato - Rifasys">
    <meta property="og:description" content="Complete y firme su contrato de servicio de manera segura y digital">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('img/og-image.jpg') }}">
    
    {{-- Assets compilados con Vite (Tailwind via PostCSS) --}}
    @vite(['resources/css/app.css'])
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    
    @yield('head')
    
    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(120, 150, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 120, 200, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(120, 255, 200, 0.15) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Animated background shapes */
        .bg-shape {
            position: fixed;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }
        
        .bg-shape:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-duration: 25s;
        }
        
        .bg-shape:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: -100px;
            right: -100px;
            animation-duration: 30s;
            animation-delay: -5s;
        }
        
        .bg-shape:nth-child(3) {
            width: 250px;
            height: 250px;
            top: 50%;
            left: -125px;
            animation-duration: 35s;
            animation-delay: -10s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.5;
            }
            33% {
                transform: translateY(-30px) rotate(120deg);
                opacity: 0.3;
            }
            66% {
                transform: translateY(30px) rotate(240deg);
                opacity: 0.5;
            }
        }
        
        /* Main container with glass effect */
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 100px rgba(120, 150, 255, 0.3);
            position: relative;
            z-index: 10;
        }
        
        /* Signature canvas specific styles */
        .firma-canvas {
            touch-action: none !important;
            cursor: crosshair;
            border: 2px dashed #e2e8f0;
            transition: border-color 0.3s ease;
        }
        
        .firma-canvas:hover {
            border-color: #cbd5e1;
        }
        
        .firma-canvas.signing {
            border-color: #10b981;
            border-style: solid;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(241, 245, 249, 0.5);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b4199 100%);
        }
        
        /* Loading animation */
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid #fff;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }
        
        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        
        /* Print styles */
        @media print {
            body {
                background: white !important;
            }
            
            body::before,
            .bg-shape,
            footer {
                display: none !important;
            }
            
            .main-container {
                box-shadow: none !important;
                background: white !important;
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .main-container {
                margin: 0;
                border-radius: 0;
                min-height: 100vh;
            }
        }
        
        /* Focus styles for accessibility */
        button:focus-visible,
        input:focus-visible,
        textarea:focus-visible,
        select:focus-visible {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
        
        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        
        /* Animations for elements */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
    
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col">
    {{-- Animated background shapes --}}
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    
    {{-- Main Content Wrapper --}}
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
        <div class="main-container rounded-2xl sm:rounded-3xl overflow-hidden animate-fade-in-up">
            @yield('content')
        </div>
        
        {{-- Additional content area --}}
        @yield('additional_content')
    </main>
    
    {{-- Footer --}}
    <footer class="relative z-10 py-8 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                {{-- Left side - Company info --}}
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-white/90 font-semibold">Rifasys - Sistema de Rifas Digital</p>
                        <p class="text-white/60 text-sm">Plataforma segura de contratos digitales</p>
                    </div>
                </div>
                
                {{-- Center - Trust badges --}}
                <div class="flex items-center space-x-6 text-white/60 text-sm">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        SSL Seguro
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
                        </svg>
                        Datos Protegidos
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H4v10h12V5h-2a1 1 0 100-2 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 011-1h.01a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h.01a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h.01a1 1 0 110 2H8a1 1 0 01-1-1zm3-6a1 1 0 011-1h3a1 1 0 110 2h-3a1 1 0 01-1-1zm0 3a1 1 0 011-1h3a1 1 0 110 2h-3a1 1 0 01-1-1zm0 3a1 1 0 011-1h3a1 1 0 110 2h-3a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Validez Legal
                    </span>
                </div>
                
                {{-- Right side - Copyright --}}
                <div class="text-white/60 text-sm text-right">
                    <p>© {{ date('Y') }} <a href="https://publienred.com" class="text-white/80 hover:text-white underline transition-colors" target="_blank">Publicidad en Red C.A.</a></p>
                    <p class="text-xs mt-1">Todos los derechos reservados</p>
                </div>
            </div>
            
            {{-- Additional footer links --}}
            <div class="mt-6 pt-6 border-t border-white/10">
                <div class="flex flex-wrap justify-center items-center gap-4 text-white/60 text-xs">
                    <a href="#" class="hover:text-white transition-colors">Términos de Servicio</a>
                    <span class="text-white/30">•</span>
                    <a href="#" class="hover:text-white transition-colors">Política de Privacidad</a>
                    <span class="text-white/30">•</span>
                    <a href="#" class="hover:text-white transition-colors">Ayuda y Soporte</a>
                    <span class="text-white/30">•</span>
                    <a href="mailto:soporte@rifasys.com" class="hover:text-white transition-colors">soporte@rifasys.com</a>
                </div>
            </div>
        </div>
    </footer>
    
    {{-- Toast notification container --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    {{-- Global Scripts --}}
    <script>
        // Toast notification system
        window.showToast = function(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const colors = {
                success: 'bg-emerald-500',
                error: 'bg-red-500',
                warning: 'bg-amber-500',
                info: 'bg-blue-500'
            };
            
            const icons = {
                success: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
                error: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
                warning: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
                info: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
            };
            
            toast.className = `${colors[type]} text-white px-4 py-3 rounded-lg shadow-xl flex items-center space-x-3 transform transition-all duration-300 translate-x-full`;
            toast.innerHTML = `
                ${icons[type]}
                <span class="font-medium">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        };
        
        // Detect if user prefers reduced motion
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion) {
            document.documentElement.style.setProperty('--animation-duration', '0.01ms');
        }
    </script>
    
    @yield('scripts')
    @stack('scripts')
</body>
</html>