{{-- resources/views/sections/stats.blade.php --}}
<section class="relative py-32 overflow-hidden bg-slate-950">
    {{-- Background Effects --}}
    <div class="absolute inset-0">
        {{-- Gradient Mesh --}}
        <div class="absolute inset-0 bg-gradient-to-br from-blue-950/50 via-slate-950 to-purple-950/50"></div>
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="100" height="100" xmlns="http://www.w3.org/2000/svg"%3E%3Cdefs%3E%3Cpattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"%3E%3Cpath d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(59,130,246,0.08)" stroke-width="1"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width="100%25" height="100%25" fill="url(%23grid)"/%3E%3C/svg%3E')]"></div>
        
        {{-- Animated Gradient Orbs --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl animate-pulse-slow animation-delay-2000"></div>
        
        {{-- Light Beams --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-1/3 w-px h-full bg-gradient-to-b from-transparent via-blue-500/20 to-transparent animate-beam"></div>
            <div class="absolute top-0 right-1/3 w-px h-full bg-gradient-to-b from-transparent via-purple-500/20 to-transparent animate-beam animation-delay-1000"></div>
        </div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full backdrop-blur-sm mb-6">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-sm font-medium text-blue-200">Métricas en Tiempo Real</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Resultados que <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">Hablan por Sí Mismos</span>
            </h2>
            <p class="text-xl text-slate-400 max-w-2xl mx-auto">
                Números que respaldan nuestra posición como líderes en el mercado
            </p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid md:grid-cols-4 gap-6 max-w-6xl mx-auto">
            @php
                $stats = [
                    [
                        'value' => '99.9',
                        'suffix' => '%',
                        'label' => 'Uptime Garantizado',
                        'sublabel' => 'SLA Enterprise',
                        'icon' => 'fa-server',
                        'color' => 'blue',
                        'trend' => '+0.3%',
                        'trending' => 'up'
                    ],
                    [
                        'value' => '24',
                        'suffix' => '/7',
                        'label' => 'Soporte Premium',
                        'sublabel' => 'Respuesta <10min',
                        'icon' => 'fa-headset',
                        'color' => 'purple',
                        'trend' => '100%',
                        'trending' => 'stable'
                    ],
                    [
                        'value' => '0',
                        'suffix' => '%',
                        'label' => 'Comisión Inicial',
                        'sublabel' => 'Primeros 30 días',
                        'icon' => 'fa-hand-holding-dollar',
                        'color' => 'green',
                        'trend' => 'Gratis',
                        'trending' => 'free'
                    ],
                    [
                        'value' => '5',
                        'suffix' => 'min',
                        'label' => 'Setup Instantáneo',
                        'sublabel' => 'Listo para vender',
                        'icon' => 'fa-rocket',
                        'color' => 'yellow',
                        'trend' => '-85%',
                        'trending' => 'down'
                    ]
                ];
            @endphp

            @foreach ($stats as $i => $stat)
                <div class="stat-card group relative" style="--delay: {{ $i * 100 }}ms">
                    {{-- Card Background with Gradient Border --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $stat['color'] }}-500/20 to-{{ $stat['color'] }}-600/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    {{-- Card Content --}}
                    <div class="relative bg-slate-900/50 backdrop-blur-xl rounded-2xl border border-slate-800 group-hover:border-{{ $stat['color'] }}-500/30 transition-all duration-500 p-8 h-full">
                        {{-- Icon --}}
                        <div class="mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-{{ $stat['color'] }}-500/20 to-{{ $stat['color'] }}-600/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-400 text-xl"></i>
                            </div>
                        </div>
                        
                        {{-- Number --}}
                        <div class="mb-4">
                            <div class="flex items-baseline gap-1">
                                <span class="stat-number text-5xl font-bold text-white" 
                                      data-value="{{ $stat['value'] }}"
                                      data-suffix="{{ $stat['suffix'] }}">0</span>
                                <span class="text-3xl font-bold text-{{ $stat['color'] }}-400">{{ $stat['suffix'] }}</span>
                            </div>
                            
                            {{-- Trend Indicator --}}
                            @if($stat['trending'] === 'up')
                                <div class="inline-flex items-center gap-1 mt-2 px-2 py-1 bg-green-500/10 rounded-full">
                                    <i class="fas fa-arrow-up text-green-400 text-xs"></i>
                                    <span class="text-xs text-green-400 font-medium">{{ $stat['trend'] }}</span>
                                </div>
                            @elseif($stat['trending'] === 'down')
                                <div class="inline-flex items-center gap-1 mt-2 px-2 py-1 bg-blue-500/10 rounded-full">
                                    <i class="fas fa-arrow-down text-blue-400 text-xs"></i>
                                    <span class="text-xs text-blue-400 font-medium">{{ $stat['trend'] }} tiempo</span>
                                </div>
                            @elseif($stat['trending'] === 'free')
                                <div class="inline-flex items-center gap-1 mt-2 px-2 py-1 bg-green-500/10 rounded-full">
                                    <i class="fas fa-gift text-green-400 text-xs"></i>
                                    <span class="text-xs text-green-400 font-medium">{{ $stat['trend'] }}</span>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1 mt-2 px-2 py-1 bg-purple-500/10 rounded-full">
                                    <i class="fas fa-check text-purple-400 text-xs"></i>
                                    <span class="text-xs text-purple-400 font-medium">{{ $stat['trend'] }}</span>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Labels --}}
                        <div>
                            <p class="text-lg font-semibold text-white mb-1">{{ $stat['label'] }}</p>
                            <p class="text-sm text-slate-400">{!! $stat['sublabel'] !!}</p>
                        </div>
                        
                        {{-- Decorative Line --}}
                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-{{ $stat['color'] }}-500/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Bottom CTA --}}
        <div class="text-center mt-16">
            <p class="text-slate-400 mb-6">Únete a más de <span class="text-white font-semibold">10,000 empresas</span> que confían en nosotros</p>
            <div class="flex flex-wrap justify-center gap-4">
                <button class="group px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl font-semibold text-white shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 transform hover:-translate-y-1 transition-all duration-200">
                    Ver Casos de Éxito
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </button>
                <button class="px-6 py-3 bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl font-semibold text-white hover:bg-slate-800 hover:border-slate-600 transform hover:-translate-y-1 transition-all duration-200">
                    Documentación API
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Animations */
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(1.1); }
        }
        
        @keyframes beam {
            0%, 100% { transform: translateY(-100%); }
            50% { transform: translateY(100%); }
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 8s ease-in-out infinite;
        }
        
        .animate-beam {
            animation: beam 8s ease-in-out infinite;
        }
        
        .animation-delay-1000 {
            animation-delay: 1s;
        }
        
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        
        /* Card entrance animation */
        .stat-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
            animation-delay: var(--delay);
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Number counting animation will be triggered by JS */
        .stat-number {
            font-variant-numeric: tabular-nums;
            letter-spacing: -0.02em;
        }
        
        @media (prefers-reduced-motion: reduce) {
            .animate-pulse-slow,
            .animate-beam,
            .stat-card {
                animation: none;
                opacity: 1;
                transform: none;
            }
        }
    </style>

    <script>
        (function() {
            if (window.__statsProInitDone) return;
            window.__statsProInitDone = true;
            
            const animateValue = (element, start, end, duration, suffix = '') => {
                if (!element) return;
                
                const isFloat = !Number.isInteger(end);
                const startTime = performance.now();
                
                const animate = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // Easing function (ease-out-expo)
                    const easeOutExpo = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                    
                    const current = start + (end - start) * easeOutExpo;
                    
                    if (isFloat) {
                        element.textContent = current.toFixed(1);
                    } else {
                        element.textContent = Math.floor(current);
                    }
                    
                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        element.textContent = isFloat ? end.toFixed(1) : end;
                    }
                };
                
                requestAnimationFrame(animate);
            };
            
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const numbers = entry.target.querySelectorAll('.stat-number');
                        numbers.forEach(num => {
                            const value = parseFloat(num.dataset.value);
                            if (!isNaN(value)) {
                                animateValue(num, 0, value, 2000);
                            }
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            // Observe the stats section
            const statsSection = document.querySelector('.stat-card')?.closest('section');
            if (statsSection) {
                observer.observe(statsSection);
            }
            
            // Support for dynamic navigation
            const init = () => {
                const section = document.querySelector('.stat-card')?.closest('section');
                if (section && !section.dataset.observed) {
                    section.dataset.observed = 'true';
                    observer.observe(section);
                }
            };
            
            document.addEventListener('DOMContentLoaded', init);
            document.addEventListener('turbo:load', init);
            document.addEventListener('livewire:load', init);
        })();
    </script>
</section>