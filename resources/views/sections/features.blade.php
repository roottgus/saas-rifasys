{{-- resources/views/sections/features.blade.php --}}
<section id="caracteristicas" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Características <span class="text-gradient">Poderosas</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Todo lo que necesitas para gestionar tu negocio de rifas de manera profesional
            </p>
        </div>
        
        {{-- Features Grid --}}
        <div class="grid md:grid-cols-3 gap-8">
            {{-- Dashboard Intuitivo --}}
            <div class="bg-white p-8 rounded-2xl shadow-lg card-hover group transition-all duration-300 hover:shadow-xl">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-line text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-2xl font-semibold mb-4 text-gray-900">Dashboard Intuitivo</h3>
                <p class="text-gray-600 mb-6">
                    Controla todas tus rifas, ventas y participantes desde un panel centralizado con estadísticas en tiempo real.
                </p>
                
            </div>

            {{-- Pagos Seguros --}}
<div class="bg-white p-8 rounded-2xl shadow-lg card-hover group transition-all duration-300 hover:shadow-xl">
    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
        <i class="fas fa-credit-card text-2xl text-green-600"></i>
    </div>
    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Pagos Seguros</h3>
    <p class="text-gray-600 mb-6">
        Los pagos realizados por los participantes llegan directamente a la <strong>cuenta bancaria del titular de la rifa</strong>.<br>
        <span class="block mt-2 text-sm text-gray-500">
            Por seguridad, todos los pagos deben ser <strong>verificados manualmente</strong> por el organizador antes de aprobar la compra del boleto.
        </span>
    </p>
    
</div>


            {{-- Sorteos Transparentes --}}
<div class="bg-white p-8 rounded-2xl shadow-lg card-hover group transition-all duration-300 hover:shadow-xl">
    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
        <span class="relative inline-block w-8 h-8">
            <i class="fas fa-shield-alt text-green-500 text-2xl absolute left-0 top-0"></i>
            <i class="fas fa-check text-white text-sm absolute left-2 top-3"></i>
        </span>
    </div>
    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Sorteos Transparentes</h3>
    <p class="text-gray-600 mb-6">
        Los ganadores se determinan con base en el resultado oficial de la lotería seleccionada para cada rifa. Máxima transparencia y confianza para todos los participantes.
    </p>
    
</div>


            {{-- 100% Responsive --}}
            <div class="bg-white p-8 rounded-2xl shadow-lg card-hover group transition-all duration-300 hover:shadow-xl">
                <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-mobile-alt text-2xl text-yellow-600"></i>
                </div>
                <h3 class="text-2xl font-semibold mb-4 text-gray-900">100% Responsive</h3>
                <p class="text-gray-600 mb-6">
                    Plataforma optimizada para todos los dispositivos. Tus clientes pueden participar desde cualquier lugar.
                </p>
                
            </div>

            {{-- Notificaciones Automáticas --}}
            <div class="bg-white p-8 rounded-2xl shadow-lg card-hover group transition-all duration-300 hover:shadow-xl">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-bell text-2xl text-red-600"></i>
                </div>
                <h3 class="text-2xl font-semibold mb-4 text-gray-900">Notificaciones Automáticas</h3>
                <p class="text-gray-600 mb-6">
                    Mantén informados a tus participantes con emails y SMS automáticos sobre sorteos y ganadores.
                </p>
                
            </div>

            {{-- Máxima Seguridad / Responsabilidad Legal --}}
<div class="bg-white p-8 rounded-2xl shadow-lg card-hover group transition-all duration-300 hover:shadow-xl">
    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
        <i class="fas fa-shield-alt text-2xl text-indigo-600"></i>
    </div>
    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Responsabilidad Legal</h3>
    <p class="text-gray-600 mb-6">
        Es obligatorio que cada organizador cuente con la autorización correspondiente por parte de la <strong>CONALOT</strong> o ente regulador local para realizar rifas y sorteos.
        <br>
        <span class="block mt-2 text-xs text-red-500 font-medium">
            Rifasys es solo un sistema tecnológico y <strong>no se responsabiliza por el uso indebido de la plataforma</strong>, ni por multas o sanciones impuestas por las autoridades.
        </span>
    </p>
    <a href="https://pagina.conalot.gob.ve/" target="_blank" rel="noopener noreferrer"
       class="text-blue-600 font-semibold hover:text-blue-700 inline-flex items-center group/link">
        Conocer más 
        <i class="fas fa-arrow-right ml-2 group-hover/link:translate-x-1 transition-transform"></i>
    </a>
</div>


        </div>
    </div>
</section>

<style>
    /* Gradient text effect */
    .text-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Card hover effect */
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-8px);
    }
    
    /* Animation for cards on scroll */
    .card-hover.animate-in {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>