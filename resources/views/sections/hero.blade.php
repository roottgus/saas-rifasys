{{-- resources/views/sections/hero.blade.php --}}
<section id="inicio" class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    {{-- Animated Background Effects --}}
    <div class="absolute inset-0">
        {{-- Grid Pattern --}}
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" xmlns="http://www.w3.org/2000/svg"%3E%3Cdefs%3E%3Cpattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse"%3E%3Cpath d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width="100%25" height="100%25" fill="url(%23grid)"/%3E%3C/svg%3E')] opacity-20"></div>
        
        {{-- Gradient Orbs --}}
        <div class="absolute top-0 -left-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
        <div class="absolute top-0 -right-40 w-80 h-80 bg-yellow-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-20 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-4000"></div>
        
        {{-- Floating Particles --}}
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-blue-400 rounded-full animate-float-slow opacity-30"></div>
            <div class="absolute top-3/4 left-1/3 w-1 h-1 bg-purple-400 rounded-full animate-float-medium opacity-40"></div>
            <div class="absolute top-1/2 left-3/4 w-3 h-3 bg-yellow-400 rounded-full animate-float-fast opacity-20"></div>
            <div class="absolute top-1/3 right-1/4 w-2 h-2 bg-cyan-400 rounded-full animate-float-medium opacity-30"></div>
            <div class="absolute bottom-1/4 right-1/3 w-1 h-1 bg-pink-400 rounded-full animate-float-slow opacity-40"></div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Left Content --}}
            <div class="text-white space-y-8">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500/10 to-purple-500/10 border border-blue-500/20 rounded-full backdrop-blur-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-sm font-medium text-blue-200">+30 rifas activas este mes</span>
                </div>

                {{-- Main Heading --}}
                <div class="space-y-4">
                    <h1 class="text-5xl lg:text-7xl font-bold leading-tight">
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-200 via-blue-100 to-white">
                            La Plataforma
                        </span>
                        <span class="block mt-2">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-yellow-300 to-orange-400 drop-shadow-[0_0_30px_rgba(250,204,21,0.3)]">
                                #1 de Rifas
                            </span>
                        </span>
                        <span class="block text-blue-100 mt-2">
                            Digitales
                        </span>
                    </h1>
                    
                    <p class="text-xl text-blue-100/80 leading-relaxed max-w-xl">
                        Automatiza, gestiona y escala tu negocio de rifas con tecnología de punta. 
                        Sistema completo con <span class="text-yellow-400 font-semibold">pagos integrados</span>, 
                        <span class="text-cyan-400 font-semibold">sorteos transparentes</span> y 
                        <span class="text-green-400 font-semibold">análisis en tiempo real</span>.
                    </p>
                </div>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap gap-4">
                    <button class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl font-semibold text-white shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 transform hover:-translate-y-1 transition-all duration-200">
                        <span class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-300 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200"></span>
                        <span class="relative flex items-center gap-2">
                            <i class="fas fa-rocket"></i>
                            Crea tu Rifa - Hoy Mismo
                        </span>
                    </button>
                    
                    <a href="https://rifasys.com/t/rifasys"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Abrir demo en vivo de Rifasys en una nueva pestaña"
   class="group inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold
          text-white bg-white/5 border border-white/20 backdrop-blur-sm
          transition-all duration-200 hover:-translate-y-1 hover:bg-white/10 hover:border-white/30
          focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-white/60 focus-visible:ring-offset-transparent">
  <span class="relative h-5 w-5">
    <span class="absolute inset-0 rounded-full bg-red-500 animate-ping" aria-hidden="true"></span>
    <i class="fas fa-play-circle relative text-lg" aria-hidden="true"></i>
  </span>
  <span>Ver Demo en Vivo</span>
</a>

                </div>

                {{-- Trust Indicators --}}
                <div class="flex flex-wrap items-center gap-8 pt-8 border-t border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-500/20 rounded-lg">
                            <i class="fas fa-shield-alt text-green-400 text-xl"></i>

                        </div>
                        <div>
                            <p class="text-xs text-blue-200/60 uppercase tracking-wider">Seguridad</p>
                            <p class="text-sm font-semibold text-white">SSL + PCI DSS</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-yellow-500/20 rounded-lg">
                            <i class="fas fa-trophy text-yellow-400 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-blue-200/60 uppercase tracking-wider">Premios</p>
                            <p class="text-sm font-semibold text-white">Garantizados 100%</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-500/20 rounded-lg">
                            <i class="fas fa-users text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-blue-200/60 uppercase tracking-wider">Usuarios</p>
                            <p class="text-sm font-semibold text-white">99+ Activos</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Content - Interactive Dashboard Preview --}}
            <div class="relative lg:pl-8">
                {{-- Main Dashboard Card --}}
                <div class="relative">
                    {{-- Glow Effect --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur-3xl opacity-20"></div>
                    
                    {{-- Dashboard --}}
                    <div class="relative bg-gradient-to-br from-slate-900/90 to-slate-800/90 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl overflow-hidden">
                        {{-- Dashboard Header --}}
                        <div class="px-6 py-4 border-b border-white/10 bg-gradient-to-r from-blue-500/10 to-purple-500/10">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                </div>
                                <span class="text-xs text-white/50 font-mono">dashboard.rifasys.com</span>
                            </div>
                        </div>
                        
                        {{-- Dashboard Content --}}
                        <div class="p-6 space-y-4">
                            {{-- Stats Row --}}
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/10 rounded-lg p-3 border border-blue-500/20">
                                    <p class="text-3xl font-bold text-white">
                                        <span class="counter" data-target="1247">0</span>
                                    </p>
                                    <p class="text-xs text-blue-200/60 mt-1">Rifas Vendidas Hoy</p>
                                </div>
                                <div class="bg-gradient-to-br from-green-500/20 to-green-600/10 rounded-lg p-3 border border-green-500/20">
                                    <p class="text-3xl font-bold text-white">
                                        $<span class="counter" data-target="8943">0</span>
                                    </p>
                                    <p class="text-xs text-green-200/60 mt-1">Ganancias del Día</p>
                                </div>
                                <div class="bg-gradient-to-br from-purple-500/20 to-purple-600/10 rounded-lg p-3 border border-purple-500/20">
                                    <p class="text-3xl font-bold text-white">
                                        <span class="counter" data-target="98">0</span>%
                                    </p>
                                    <p class="text-xs text-purple-200/60 mt-1">Tasa Conversión</p>
                                </div>
                            </div>
                            
                            {{-- Live Activity Feed --}}
                            <div class="space-y-2">
                                <p class="text-xs text-white/50 uppercase tracking-wider">Actividad en Vivo</p>
                                <div class="space-y-2 activity-feed">
                                    <div class="flex items-center gap-3 p-2 bg-white/5 rounded-lg animate-slide-in">
                                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                        <p class="text-sm text-white/80">
                                            <span class="font-semibold">María G.</span> compró 3 boletos - 
                                            <span class="text-yellow-400">Rifa iPhone 15 Pro</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3 p-2 bg-white/5 rounded-lg animate-slide-in animation-delay-1000">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                                        <p class="text-sm text-white/80">
                                            <span class="font-semibold">Carlos M.</span> ganó - 
                                            <span class="text-green-400">Premio $500</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3 p-2 bg-white/5 rounded-lg animate-slide-in animation-delay-2000">
                                        <span class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></span>
                                        <p class="text-sm text-white/80">
                                            <span class="font-semibold">Ana P.</span> creó nueva rifa - 
                                            <span class="text-purple-400">PS5 + Juegos</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Chart Preview --}}
                            <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                                <div class="flex items-end gap-2 h-24">
                                    <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t" style="height: 60%;"></div>
                                    <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t" style="height: 80%;"></div>
                                    <div class="flex-1 bg-gradient-to-t from-yellow-500 to-yellow-400 rounded-t animate-pulse" style="height: 100%;"></div>
                                    <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t" style="height: 70%;"></div>
                                    <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t" style="height: 85%;"></div>
                                    <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t" style="height: 65%;"></div>
                                    <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t" style="height: 90%;"></div>
                                </div>
                                <p class="text-xs text-white/40 text-center mt-2">Ventas últimos 7 días</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Floating Elements --}}
                <div class="absolute -top-8 -right-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl p-4 shadow-xl animate-float text-center">
  <i class="fas fa-crown text-3xl text-white inline-block"></i>
  <p class="text-xs text-white font-semibold mt-1">Premium</p>
</div>

                
                <div class="absolute -bottom-8 -left-8 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl px-4 py-3 shadow-xl animate-float animation-delay-2000">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-white"></i>
                        <p class="text-sm text-white font-semibold">100% Seguro</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/50 animate-bounce">
        <i class="fas fa-chevron-down text-2xl"></i>
    </div>
</section>

{{-- Additional Styles --}}
<style>
    @keyframes blob {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    
    @keyframes float-slow {
        0%, 100% { transform: translateY(0px) translateX(0px); }
        50% { transform: translateY(-30px) translateX(10px); }
    }
    
    @keyframes float-medium {
        0%, 100% { transform: translateY(0px) translateX(0px); }
        50% { transform: translateY(-20px) translateX(-10px); }
    }
    
    @keyframes float-fast {
        0%, 100% { transform: translateY(0px) translateX(0px); }
        50% { transform: translateY(-15px) translateX(5px); }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    
    @keyframes slide-in {
        from { 
            opacity: 0;
            transform: translateX(-20px);
        }
        to { 
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-blob {
        animation: blob 10s infinite;
    }
    
    .animate-float-slow {
        animation: float-slow 8s ease-in-out infinite;
    }
    
    .animate-float-medium {
        animation: float-medium 6s ease-in-out infinite;
    }
    
    .animate-float-fast {
        animation: float-fast 4s ease-in-out infinite;
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    .animate-slide-in {
        animation: slide-in 0.5s ease-out forwards;
    }
    
    .animation-delay-1000 {
        animation-delay: 1s;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>

{{-- Counter Animation Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter');
        const speed = 200;
        
        const animateCounter = (counter) => {
            const target = +counter.getAttribute('data-target');
            const increment = target / speed;
            
            const updateCount = () => {
                const count = +counter.innerText.replace(/[^0-9]/g, '');
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 10);
                } else {
                    counter.innerText = target.toLocaleString();
                }
            };
            
            updateCount();
        };
        
        // Intersection Observer para animar cuando sea visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    animateCounter(counter);
                    observer.unobserve(counter);
                }
            });
        });
        
        counters.forEach(counter => {
            observer.observe(counter);
        });
    });
</script>