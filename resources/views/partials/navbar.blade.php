{{-- resources/views/partials/navbar.blade.php --}}
<nav id="navbar" class="fixed w-full z-50 transition-all duration-300 nav-sticky">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            {{-- Logo --}}
            <a href="#inicio" class="flex items-center gap-3 shrink-0" aria-label="Rifasys - Inicio">
                <img
                    src="{{ asset('images/rifasys.png') }}"
                    alt="Rifasys"
                    class="h-9 w-auto select-none"
                    loading="lazy"
                >
                

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="#inicio" class="text-gray-700 hover:text-blue-600 transition">Inicio</a>
                <a href="#caracteristicas" class="text-gray-700 hover:text-blue-600 transition">Características</a>
                <a href="#como-funciona" class="text-gray-700 hover:text-blue-600 transition">Cómo Funciona</a>
                <a href="#precios" class="text-gray-700 hover:text-blue-600 transition">Precios</a>
                <a href="#testimonios" class="text-gray-700 hover:text-blue-600 transition">Testimonios</a>
                <button class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition pulse-button">
                    Comenzar Ahora
                </button>
            </div>

            {{-- Mobile Menu Button --}}
            <button class="md:hidden text-gray-700" onclick="toggleMobileMenu()" aria-label="Abrir menú">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<div id="mobileMenu" class="fixed top-0 right-0 w-64 h-full bg-white shadow-xl transform translate-x-full transition-transform duration-300 z-50">
    <div class="p-6">
        <button class="mb-8 text-gray-700" onclick="closeMobileMenu()" aria-label="Cerrar menú">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <div class="flex flex-col space-y-4">
            <a href="#inicio" class="text-gray-700 hover:text-blue-600 transition" onclick="closeMobileMenu()">Inicio</a>
            <a href="#caracteristicas" class="text-gray-700 hover:text-blue-600 transition" onclick="closeMobileMenu()">Características</a>
            <a href="#como-funciona" class="text-gray-700 hover:text-blue-600 transition" onclick="closeMobileMenu()">Cómo Funciona</a>
            <a href="#precios" class="text-gray-700 hover:text-blue-600 transition" onclick="closeMobileMenu()">Precios</a>
            <a href="#testimonios" class="text-gray-700 hover:text-blue-600 transition" onclick="closeMobileMenu()">Testimonios</a>
            <button class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition">
                Comenzar Ahora
            </button>
        </div>
    </div>
</div>
