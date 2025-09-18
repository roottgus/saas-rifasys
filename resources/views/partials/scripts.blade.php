{{-- resources/views/partials/scripts.blade.php --}}
<script>
(function() {
    'use strict';
    
    // Prevenir múltiples inicializaciones
    if (window.RifasysApp) return;
    
    window.RifasysApp = {
        initialized: false,
        observers: [],
        
        init() {
            if (this.initialized) return;
            this.initialized = true;
            
            this.initSmoothScroll();
            this.initNavbar();
            this.initMobileMenu();
            this.initAnimations();
            this.initParallax();
            this.initNumberCounters();
        },
        
        // Smooth scrolling para navegación
        initSmoothScroll() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const target = document.querySelector(targetId);
                    
                    if (target) {
                        const offset = 80; // Altura del navbar
                        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                        
                        // Cerrar menú móvil si está abierto
                        const mobileMenu = document.getElementById('mobileMenu');
                        if (mobileMenu && !mobileMenu.classList.contains('translate-x-full')) {
                            mobileMenu.classList.add('translate-x-full');
                        }
                    }
                });
            });
        },
        
        // Navbar effects
        initNavbar() {
            const navbar = document.getElementById('navbar');
            if (!navbar) return;
            
            let lastScroll = 0;
            
            const handleScroll = () => {
                const currentScroll = window.pageYOffset;
                
                // Add shadow on scroll
                if (currentScroll > 100) {
                    navbar.classList.add('shadow-lg', 'bg-white/95');
                    navbar.classList.remove('bg-transparent');
                } else {
                    navbar.classList.remove('shadow-lg', 'bg-white/95');
                    navbar.classList.add('bg-transparent');
                }
                
                // Hide/show navbar on scroll (opcional)
                if (currentScroll > lastScroll && currentScroll > 500) {
                    navbar.style.transform = 'translateY(-100%)';
                } else {
                    navbar.style.transform = 'translateY(0)';
                }
                
                lastScroll = currentScroll;
            };
            
            // Throttle scroll event
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        handleScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        },
        
        // Mobile menu
        initMobileMenu() {
            window.toggleMobileMenu = () => {
                const menu = document.getElementById('mobileMenu');
                if (menu) {
                    menu.classList.toggle('translate-x-full');
                    document.body.classList.toggle('overflow-hidden');
                }
            };
            
            window.closeMobileMenu = () => {
                const menu = document.getElementById('mobileMenu');
                if (menu) {
                    menu.classList.add('translate-x-full');
                    document.body.classList.remove('overflow-hidden');
                }
            };
            
            // Cerrar menú al hacer click fuera
            document.addEventListener('click', (e) => {
                const menu = document.getElementById('mobileMenu');
                const menuButton = e.target.closest('.menu-button');
                
                if (menu && !menu.contains(e.target) && !menuButton && !menu.classList.contains('translate-x-full')) {
                    closeMobileMenu();
                }
            });
        },
        
        // Animaciones con Intersection Observer
        initAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            // Observer para cards y elementos animados
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        
                        // Animar cards
                        if (element.classList.contains('card-hover') || element.classList.contains('animate-on-scroll')) {
                            element.classList.add('animate-in');
                        }
                        
                        // Animar elementos con data-animate
                        if (element.dataset.animate) {
                            element.classList.add(element.dataset.animate);
                        }
                        
                        // No volver a observar
                        if (!element.dataset.keepObserving) {
                            animationObserver.unobserve(element);
                        }
                    }
                });
            }, observerOptions);
            
            // Observar elementos
            document.querySelectorAll('.card-hover, .animate-on-scroll, [data-animate]').forEach(element => {
                // Preparar elementos para animación
                if (element.classList.contains('card-hover')) {
                    element.style.opacity = '0';
                    element.style.transform = 'translateY(20px)';
                    element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                }
                
                animationObserver.observe(element);
            });
            
            this.observers.push(animationObserver);
        },
        
        // Parallax effects
        initParallax() {
            const parallaxElements = document.querySelectorAll('.parallax, .floating');
            
            if (parallaxElements.length === 0) return;
            
            // Check for reduced motion preference
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReducedMotion) return;
            
            let ticking = false;
            
            const updateParallax = () => {
                const scrolled = window.pageYOffset;
                
                parallaxElements.forEach(element => {
                    const speed = element.dataset.speed || 0.5;
                    const yPos = -(scrolled * speed);
                    
                    // Limitar el efecto para evitar que elementos salgan del viewport
                    if (Math.abs(yPos) < 500) {
                        element.style.transform = `translateY(${yPos}px)`;
                    }
                });
                
                ticking = false;
            };
            
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(updateParallax);
                    ticking = true;
                }
            });
        },
        
        // Contadores numéricos mejorados
        initNumberCounters() {
            const animateValue = (element, start, end, duration, suffix = '') => {
                if (!element || element.dataset.animated === 'true') return;
                
                element.dataset.animated = 'true';
                const isFloat = !Number.isInteger(end);
                const startTime = performance.now();
                
                const animate = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // Easing function
                    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                    
                    const current = start + (end - start) * easeOutQuart;
                    
                    if (isFloat) {
                        element.textContent = current.toFixed(1) + suffix;
                    } else {
                        element.textContent = Math.floor(current).toLocaleString() + suffix;
                    }
                    
                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        element.textContent = (isFloat ? end.toFixed(1) : end.toLocaleString()) + suffix;
                    }
                };
                
                requestAnimationFrame(animate);
            };
            
            // Observer para contadores
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counters = entry.target.querySelectorAll('.counter, .stat-number, [data-count]');
                        
                        counters.forEach(counter => {
                            // Obtener valor del atributo correcto
                            const value = parseFloat(
                                counter.dataset.count || 
                                counter.dataset.target || 
                                counter.dataset.value || 
                                '0'
                            );
                            
                            const suffix = counter.dataset.suffix || '';
                            
                            if (!isNaN(value) && counter.dataset.animated !== 'true') {
                                animateValue(counter, 0, value, 2000, suffix);
                            }
                        });
                        
                        counterObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            });
            
            // Buscar secciones con contadores
            const sections = document.querySelectorAll('#inicio, .stats-section, [data-has-counters]');
            sections.forEach(section => {
                if (section.querySelector('.counter, .stat-number, [data-count]')) {
                    counterObserver.observe(section);
                }
            });
            
            this.observers.push(counterObserver);
        },
        
        // Cleanup function
        destroy() {
            this.observers.forEach(observer => observer.disconnect());
            this.observers = [];
            this.initialized = false;
        }
    };
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => RifasysApp.init());
    } else {
        RifasysApp.init();
    }
    
    // Soporte para navegación dinámica (Turbo, Livewire, etc.)
    document.addEventListener('turbo:load', () => RifasysApp.init());
    document.addEventListener('livewire:load', () => RifasysApp.init());
    document.addEventListener('alpine:init', () => RifasysApp.init());
    
    // Reinicializar en cambios de página con PJAX/AJAX
    window.addEventListener('popstate', () => RifasysApp.init());
})();
</script>