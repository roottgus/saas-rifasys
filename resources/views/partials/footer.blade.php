{{-- resources/views/partials/footer.blade.php --}}
<footer class="bg-gray-900 text-white pt-16 pb-4">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            {{-- Company Info --}}
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="bg-blue-600 text-white p-2 rounded-lg">
                        <i class="fas fa-crown text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold">Rifasys</span>
                </div>
                <p class="text-gray-400 mb-4">
                    La plataforma líder para gestionar rifas online de manera profesional y segura.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.instagram.com/publienredca/" target="_blank" rel="noopener" class="bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-500 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
            {{-- Product Links --}}
            <div>
                <h4 class="font-semibold mb-4">Producto</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Características</a></li>
                    <li><a href="#" class="hover:text-white transition">Precios</a></li>
                    <li><a href="#" class="hover:text-white transition">Integraciones</a></li>
                    <li><a href="#" class="hover:text-white transition">API</a></li>
                    <li><a href="#" class="hover:text-white transition">Roadmap</a></li>
                </ul>
            </div>
            {{-- Resources Links --}}
            <div>
                <h4 class="font-semibold mb-4">Recursos</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Centro de Ayuda</a></li>
                    <li><a href="#" class="hover:text-white transition">Documentación</a></li>
                    <li><a href="#" class="hover:text-white transition">Tutoriales</a></li>
                    <li><a href="#" class="hover:text-white transition">Blog</a></li>
                    <li><a href="#" class="hover:text-white transition">Webinars</a></li>
                </ul>
            </div>
            {{-- Company Links --}}
            <div>
                <h4 class="font-semibold mb-4">Empresa</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Nosotros</a></li>
                    <li><a href="#" class="hover:text-white transition">Contacto</a></li>
                    <li><a href="#" class="hover:text-white transition">Términos y Condiciones</a></li>
                    <li><a href="#" class="hover:text-white transition">Privacidad</a></li>
                    <li><a href="#" class="hover:text-white transition">Afiliados</a></li>
                </ul>
            </div>
        </div>
    </div>
    {{-- Credito final, fuera del container, SIEMPRE abajo --}}
    <div class="mt-10 pt-6 border-t border-gray-800 w-full text-center text-gray-400 text-xs md:text-sm flex flex-col items-center gap-1">
        <span>
            © {{ date('Y') }} Rifasys. Todos los derechos reservados.
        </span>
        <span>
            Página y sistema desarrollado por 
            <a href="https://publienred.com" target="_blank" rel="noopener" class="text-blue-400 hover:text-yellow-400 font-semibold underline transition">publienred.com</a>
            &nbsp;|&nbsp;
            Instagram: 
            <a href="https://www.instagram.com/publienredca/" target="_blank" rel="noopener" class="text-pink-300 hover:text-yellow-400 underline transition">@publienredca</a>
            &nbsp;|&nbsp;
            <i class="fas fa-lock mr-1"></i> SSL Seguro
            &nbsp;|&nbsp;
            <i class="fas fa-shield-alt mr-1"></i> GDPR Compliant
        </span>
    </div>
</footer>
