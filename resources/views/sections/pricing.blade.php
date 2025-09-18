{{-- resources/views/sections/pricing.blade.php --}}
<section id="precios" class="py-20 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 relative overflow-hidden">
    {{-- Animated Background --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" xmlns="http://www.w3.org/2000/svg"%3E%3Cdefs%3E%3Cpattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse"%3E%3Cpath d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(59,130,246,0.1)" stroke-width="1"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width="100%25" height="100%25" fill="url(%23grid)"/%3E%3C/svg%3E')]"></div>
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse-slow animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full backdrop-blur-sm mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-sm font-medium text-blue-200">Soluciones para cada tipo de negocio</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Elige tu <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">Plan Ideal</span>
            </h2>
            <p class="text-xl text-slate-300 max-w-3xl mx-auto">
                Desde ventas online hasta puntos físicos completos, tenemos la solución perfecta
            </p>
        </div>
        
        {{-- Pricing Cards Container --}}
        <div class="grid lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
            
            {{-- RIFASYS PLUS - Online --}}
            <div class="pricing-card group relative" data-plan="plus">
                {{-- Glow Effect --}}
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-3xl blur-xl opacity-25 group-hover:opacity-40 transition duration-500"></div>
                
                {{-- Card Content --}}
                <div class="relative bg-slate-900/90 backdrop-blur-xl rounded-3xl border border-slate-700 overflow-hidden h-full">
                    {{-- Header Gradient --}}
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-cyan-500"></div>
                    
                    {{-- Plan Badge --}}
                    <div class="absolute top-6 right-6">
                        <span class="px-3 py-1 bg-blue-500/20 border border-blue-500/30 rounded-full text-xs font-semibold text-blue-300">
                            ONLINE
                        </span>
                    </div>
                    
                    <div class="p-8">
                        {{-- Plan Header --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-globe text-2xl text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">Rifasys Plus</h3>
                                    <p class="text-slate-400">Sistema de Ventas Online</p>
                                </div>
                            </div>
                            
                            {{-- Price --}}
                            <div class="flex items-baseline gap-2 mb-2">
                                <span class="text-5xl font-bold text-white">$150</span>
                                <span class="text-slate-400">USD / por rifa</span>
                            </div>
                            <p class="text-sm text-slate-500">Pago único por cada rifa creada</p>
                        </div>
                        
                        {{-- Features List --}}
                        <div class="space-y-4 mb-8">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Hasta 10,000 números</span>
                                    <p class="text-sm text-slate-400">Capacidad para rifas grandes</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Dominio personalizado</span>
                                    <p class="text-sm text-slate-400">Ej: turifa.com incluido</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Configuración completa</span>
                                    <p class="text-sm text-slate-400">Te ayudamos con todo el setup</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Pagos integrados</span>
                                    <p class="text-sm text-slate-400">Stripe, PayPal, transferencias</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Panel de administración</span>
                                    <p class="text-sm text-slate-400">Dashboard completo en tiempo real</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Verificador de Tickets</span>
                                    <p class="text-sm text-slate-400">Verificacion con codigos QR</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Notificaciones via Email</span>
                                    <p class="text-sm text-slate-400">Envio de Emails en todo el proceso</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">SSL & Seguridad</span>
                                    <p class="text-sm text-slate-400">Certificado SSL incluido</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- CTA Button --}}
                        <a href="https://wa.me/584220076738?text=Hola%2C%20quiero%20contratar%20el%20plan%20Plus%20de%20Rifasys.%20%C2%BFPueden%20orientarme%20sobre%20el%20proceso%3F"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Comenzar con Plus vía WhatsApp"
   class="w-full py-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-2xl font-semibold hover:shadow-lg hover:shadow-blue-500/25 transform hover:-translate-y-1 transition-all duration-200 group flex items-center justify-center gap-2 text-center">
    <i class="fas fa-rocket"></i>
    Comenzar con Plus
    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
</a>

                        
                        {{-- Support Info --}}
                        <p class="text-center text-sm text-slate-500 mt-4">
                            <i class="fas fa-headset mr-1"></i>
                            Soporte 24/7 incluido
                        </p>
                    </div>
                </div>
            </div>
            
            {{-- RIFASYS PREMIUM - Físico --}}
            <div class="pricing-card group relative" data-plan="premium">
                {{-- Popular Badge --}}
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-20">
                    <span class="px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full text-sm font-bold text-slate-900 shadow-lg animate-pulse">
                        RECOMENDADO
                    </span>
                </div>
                
                {{-- Glow Effect --}}
                <div class="absolute -inset-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-3xl blur-xl opacity-25 group-hover:opacity-40 transition duration-500"></div>
                
                {{-- Card Content --}}
                <div class="relative bg-slate-900/90 backdrop-blur-xl rounded-3xl border border-slate-700 overflow-hidden h-full">
                    {{-- Header Gradient --}}
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                    
                    {{-- Plan Badge --}}
                    <div class="absolute top-6 right-6">
                        <span class="px-3 py-1 bg-purple-500/20 border border-purple-500/30 rounded-full text-xs font-semibold text-purple-300">
                            FÍSICO + ONLINE
                        </span>
                    </div>
                    
                    <div class="p-8">
                        {{-- Plan Header --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-store text-2xl text-purple-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">Rifasys Premium</h3>
                                    <p class="text-slate-400">Sistema Completo + Puntos Físicos</p>
                                </div>
                            </div>
                            
                            {{-- Price --}}
                            <div class="flex items-baseline gap-2 mb-2">
                                <span class="text-5xl font-bold text-white">Consultar</span>
                            </div>
                            <p class="text-sm text-slate-500">Precio personalizado según necesidades</p>
                        </div>
                        
                        {{-- Features List --}}
                        <div class="space-y-4 mb-8">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Todo de Rifasys Plus +</span>
                                    <p class="text-sm text-slate-400">Todas las características online</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-star text-yellow-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Impresora térmica incluida</span>
                                    <p class="text-sm text-slate-400">Hardware profesional para tickets</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-star text-yellow-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Rifas especiales</span>
                                    <p class="text-sm text-slate-400">50/50, progresivas, combo rifas</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-star text-yellow-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Sistema de apartados</span>
                                    <p class="text-sm text-slate-400">Reserva y abonos por ticket</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-star text-yellow-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Módulo contable completo</span>
                                    <p class="text-sm text-slate-400">Facturación, reportes, balance</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-star text-yellow-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Creacion Premios Especiales</span>
                                    <p class="text-sm text-slate-400">Premios especiales con panel administrativo</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-star text-yellow-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Módulo de Descuentos</span>
                                    <p class="text-sm text-slate-400">Genera descuentos automáticos</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-star text-yellow-400 mt-1"></i>
                                <div>
                                   <span class="text-white font-medium">Soporte x 6 meses continuos</span>
<p class="text-sm text-slate-400">Incluye actualizaciones del sistema.</p>

                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i class="fas fa-star text-yellow-400 mt-1"></i>
                                <div>
                                    <span class="text-white font-medium">Capacitación presencial</span>
                                    <p class="text-sm text-slate-400">Training completo a tu equipo</p>
                                </div>
                            </div>
                        </div>
                        
                       <a href="https://wa.me/584220076738?text=Hola%2C%20me%20gustar%C3%ADa%20solicitar%20una%20demo%20premium%20de%20Rifasys%20para%20conocer%20todas%20las%20funciones.%20%C2%BFPueden%20agendarme%20una%20demostraci%C3%B3n%3F"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Solicitar demo premium vía WhatsApp"
   class="w-full py-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-2xl font-semibold hover:shadow-lg hover:shadow-purple-500/25 transform hover:-translate-y-1 transition-all duration-200 group flex items-center justify-center gap-2 text-center">
    <i class="fas fa-phone"></i>
    Solicitar Demo Premium
    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
</a>

                        
                        {{-- Support Info --}}
                        <p class="text-center text-sm text-slate-500 mt-4">
                            <i class="fas fa-user-tie mr-1"></i>
                            Account Manager dedicado
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Interactive Comparison Toggle --}}
        <div class="mt-16 text-center">
            <button id="comparison-toggle" class="inline-flex items-center gap-3 px-6 py-3 bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-full text-white hover:bg-slate-800 transition-all duration-200 group">
                <i class="fas fa-balance-scale"></i>
                <span>Comparar planes detalladamente</span>
                <i class="fas fa-chevron-down group-hover:translate-y-1 transition-transform" id="comparison-arrow"></i>
            </button>
        </div>
        
        {{-- Detailed Comparison (Hidden by default) --}}
        <div id="comparison-section" class="hidden mt-12 animate-fadeIn">
            <div class="max-w-6xl mx-auto">
                <div class="bg-slate-900/50 backdrop-blur-xl rounded-2xl border border-slate-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-700 bg-slate-800/50">
                                    <th class="text-left p-6 text-white font-semibold">Características</th>
                                    <th class="p-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <i class="fas fa-globe text-blue-400"></i>
                                            <span class="text-blue-400 font-semibold">Rifasys Plus</span>
                                        </div>
                                    </th>
                                    <th class="p-6 text-center bg-purple-500/10">
                                        <div class="flex items-center justify-center gap-2">
                                            <i class="fas fa-store text-purple-400"></i>
                                            <span class="text-purple-400 font-semibold">Rifasys Premium</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-300">
                                <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                                    <td class="p-6 font-medium">Precio</td>
                                    <td class="p-6 text-center">$150 USD por rifa</td>
                                    <td class="p-6 text-center bg-purple-500/5">Precio a consultar</td>
                                </tr>
                                <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                                    <td class="p-6 font-medium">Capacidad de números</td>
                                    <td class="p-6 text-center">10,000</td>
                                    <td class="p-6 text-center bg-purple-500/5 font-semibold">Ilimitado</td>
                                </tr>
                                <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                                    <td class="p-6 font-medium">Ventas online</td>
                                    <td class="p-6 text-center"><i class="fas fa-check text-green-400"></i></td>
                                    <td class="p-6 text-center bg-purple-500/5"><i class="fas fa-check text-green-400"></i></td>
                                </tr>
                                <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                                    <td class="p-6 font-medium">Punto de venta físico</td>
                                    <td class="p-6 text-center"><i class="fas fa-times text-red-400"></i></td>
                                    <td class="p-6 text-center bg-purple-500/5"><i class="fas fa-check text-green-400"></i></td>
                                </tr>
                                <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                                    <td class="p-6 font-medium">Impresora térmica</td>
                                    <td class="p-6 text-center"><i class="fas fa-times text-red-400"></i></td>
                                    <td class="p-6 text-center bg-purple-500/5"><i class="fas fa-check text-green-400"></i></td>
                                </tr>
                                <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                                    <td class="p-6 font-medium">Sistema contable</td>
                                    <td class="p-6 text-center">Básico</td>
                                    <td class="p-6 text-center bg-purple-500/5 font-semibold">Completo</td>
                                </tr>
                                <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                                    <td class="p-6 font-medium">App móvil vendedores</td>
                                    <td class="p-6 text-center"><i class="fas fa-times text-red-400"></i></td>
                                    <td class="p-6 text-center bg-purple-500/5"><i class="fas fa-check text-green-400"></i></td>
                                </tr>
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="p-6 font-medium">Soporte</td>
                                    <td class="p-6 text-center">24/7 Online</td>
                                    <td class="p-6 text-center bg-purple-500/5 font-semibold">Dedicado + Presencial</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- FAQ Section --}}
        <div class="mt-20 max-w-3xl mx-auto">
            <h3 class="text-2xl font-bold text-white text-center mb-8">Preguntas Frecuentes</h3>
            <div class="space-y-4">
                <div class="faq-item bg-slate-800/30 backdrop-blur-sm border border-slate-700 rounded-xl overflow-hidden">
                    <button class="w-full p-6 text-left flex justify-between items-center text-white hover:bg-slate-800/50 transition-colors faq-toggle">
                        <span class="font-medium">¿Puedo migrar de Plus a Premium después?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-6 text-slate-400">
                        <p>¡Por supuesto! Puedes comenzar con Rifasys Plus y cuando tu negocio crezca, migrar a Premium. Migramos todos tus datos y te damos crédito por lo pagado.</p>
                    </div>
                </div>
                
                <div class="faq-item bg-slate-800/30 backdrop-blur-sm border border-slate-700 rounded-xl overflow-hidden">
    <button class="w-full p-6 text-left flex justify-between items-center text-white hover:bg-slate-800/50 transition-colors faq-toggle">
        <span class="font-medium">¿Qué incluye el precio de $150 en Plus?</span>
        <i class="fas fa-chevron-down transition-transform"></i>
    </button>
    <div class="faq-content hidden px-6 pb-6 text-slate-400">
        <ul class="list-disc ml-5 space-y-2 text-base">
            <li>
                <b>El precio es por cada rifa publicada</b>, válido hasta la finalización de la misma. No existen pagos mensuales ni cargos ocultos.
            </li>
            <li>
                <b>No es posible crear una nueva rifa si tienes una rifa activa</b>. Puedes iniciar una nueva solo cuando finalices tu rifa actual.
            </li>
            <li>
                Sistema de <b>venta online de boletos</b> (hasta 10,000 tickets por rifa) con página personalizada y panel de control CMS para gestionar tu sorteo.
            </li>
            <li>
                <b>Dominio personalizado</b>,configuración técnica completa incluida.
            </li>
            <li>
                <b>Integración de métodos de pago</b> y soporte técnico durante todo el proceso de la rifa.
            </li>
        </ul>
    </div>
</div>

                
                <div class="faq-item bg-slate-800/30 backdrop-blur-sm border border-slate-700 rounded-xl overflow-hidden">
                    <button class="w-full p-6 text-left flex justify-between items-center text-white hover:bg-slate-800/50 transition-colors faq-toggle">
                        <span class="font-medium">¿Necesito conocimientos técnicos?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-6 text-slate-400">
                        <p>No, nosotros nos encargamos de toda la configuración técnica. Te entregamos el sistema listo para usar y te capacitamos en su manejo.</p>
                    </div>
                </div>
                <div class="faq-item bg-slate-800/30 backdrop-blur-sm border border-slate-700 rounded-xl overflow-hidden">
    <button class="w-full p-6 text-left flex justify-between items-center text-white hover:bg-slate-800/50 transition-colors faq-toggle">
        <span class="font-medium">¿Cómo puedo contratar el servicio?</span>
        <i class="fas fa-chevron-down transition-transform"></i>
    </button>
    <div class="faq-content hidden px-6 pb-6 text-slate-400">
        <ul class="list-disc ml-5 space-y-2 text-base">
            <li>
                <b>Comunícate con nuestro equipo de atención al cliente</b>. Te guiaremos paso a paso para iniciar el proceso.
            </li>
            <li>
                Realizarás un <b>contrato inteligente digital</b>, el cual quedará integrado y disponible dentro de tu panel de control.
            </li>
            <li>
                Efectúa la cancelación del plan o sistema deseado y disfruta de tu servicio profesional con todas las funciones habilitadas.
            </li>
        </ul>
    </div>
</div>

            </div>
        </div>
    </div>
</section>

<style>
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.2; transform: scale(1); }
        50% { opacity: 0.3; transform: scale(1.05); }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-pulse-slow {
        animation: pulse-slow 4s ease-in-out infinite;
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .pricing-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .pricing-card:hover {
        transform: translateY(-8px);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Comparison Toggle
        const comparisonToggle = document.getElementById('comparison-toggle');
        const comparisonSection = document.getElementById('comparison-section');
        const comparisonArrow = document.getElementById('comparison-arrow');
        
        if (comparisonToggle) {
            comparisonToggle.addEventListener('click', function() {
                comparisonSection.classList.toggle('hidden');
                comparisonArrow.classList.toggle('rotate-180');
                
                if (!comparisonSection.classList.contains('hidden')) {
                    comparisonSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        }
        
        // FAQ Toggles
        const faqToggles = document.querySelectorAll('.faq-toggle');
        faqToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('i');
                
                content.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
                
                // Close other FAQs
                faqToggles.forEach(otherToggle => {
                    if (otherToggle !== toggle) {
                        otherToggle.nextElementSibling.classList.add('hidden');
                        otherToggle.querySelector('i').classList.remove('rotate-180');
                    }
                });
            });
        });
        
        // Animate cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.pricing-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            observer.observe(card);
        });
    });
</script>