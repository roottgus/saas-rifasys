{{-- resources/views/contact.blade.php --}}
@extends('layouts.app')

@section('title', 'Contacto - Rifasys | Solicita tu Demo')

@section('content')

{{-- Hero Section --}}
<section class="relative pt-32 pb-20 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 overflow-hidden">
    {{-- Background Effects --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" xmlns="http://www.w3.org/2000/svg"%3E%3Cdefs%3E%3Cpattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse"%3E%3Cpath d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(59,130,246,0.1)" stroke-width="1"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width="100%25" height="100%25" fill="url(%23grid)"/%3E%3C/svg%3E')]"></div>
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full backdrop-blur-sm mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-sm font-medium text-blue-200">Respuesta en menos de 24 horas</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                Hablemos de tu <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">Proyecto</span>
            </h1>
            <p class="text-xl text-slate-300">
                Estamos aquí para ayudarte a transformar tu negocio de rifas
            </p>
        </div>
    </div>
</section>

{{-- Contact Section --}}
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            
            {{-- Contact Form --}}
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Envíanos un mensaje</h2>
                
                <form id="contact-form" class="space-y-6">
                    @csrf
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre completo *
                            </label>
                            <input type="text" 
                                   name="name" 
                                   required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Juan Pérez">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input type="email" 
                                   name="email" 
                                   required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="correo@ejemplo.com">
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="tel" 
                                   name="phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="+1 234 567 8900">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Empresa
                            </label>
                            <input type="text" 
                                   name="company" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Nombre de tu empresa">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ¿Qué plan te interesa?
                        </label>
                        <select name="plan" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Selecciona un plan</option>
                            <option value="plus">Rifasys Plus - Sistema Online ($150/rifa)</option>
                            <option value="premium">Rifasys Premium - Sistema Completo (Consultar)</option>
                            <option value="no-sure">Aún no estoy seguro</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mensaje *
                        </label>
                        <textarea name="message" 
                                  rows="5" 
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Cuéntanos sobre tu proyecto de rifas..."></textarea>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="privacy-check" 
                               name="privacy" 
                               required
                               class="mr-3 rounded text-blue-600 focus:ring-blue-500">
                        <label for="privacy-check" class="text-sm text-gray-600">
                            Acepto la <a href="/privacidad" class="text-blue-600 hover:underline">política de privacidad</a> *
                        </label>
                    </div>
                    
                    <button type="submit" 
                            class="w-full py-4 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-blue-500/25 transform hover:-translate-y-1 transition-all duration-200">
                        <span class="flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            Enviar Mensaje
                        </span>
                    </button>
                </form>
                
                {{-- Success Message (hidden by default) --}}
                <div id="success-message" class="hidden mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-xl"></i>
                        <div>
                            <p class="font-semibold">¡Mensaje enviado exitosamente!</p>
                            <p class="text-sm">Te contactaremos en las próximas 24 horas.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Contact Information --}}
            <div class="space-y-8">
                {{-- Quick Contact --}}
                <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-6">Información de Contacto</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold mb-1">Email</p>
                                <a href="mailto:ventas@rifasys.com" class="text-blue-100 hover:text-white transition-colors">
                                    ventas@rifasys.com
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold mb-1">WhatsApp</p>
                                <a href="https://wa.me/1234567890" class="text-blue-100 hover:text-white transition-colors">
                                    +1 (234) 567-8900
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold mb-1">Horario de Atención</p>
                                <p class="text-blue-100">Lunes a Viernes: 9:00 AM - 6:00 PM</p>
                                <p class="text-blue-100">Sábados: 10:00 AM - 2:00 PM</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-headset text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold mb-1">Soporte 24/7</p>
                                <p class="text-blue-100">Para clientes activos</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Social Links --}}
                    <div class="mt-8 pt-6 border-t border-white/20">
                        <p class="font-semibold mb-4">Síguenos</p>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                {{-- FAQ Card --}}
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Preguntas Frecuentes</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="font-semibold text-gray-700 mb-1">¿Cuánto tiempo toma la implementación?</p>
                            <p class="text-gray-600 text-sm">Rifasys Plus está listo en 24-48 horas. Premium requiere 5-7 días.</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700 mb-1">¿Ofrecen capacitación?</p>
                            <p class="text-gray-600 text-sm">Sí, incluimos capacitación completa y documentación.</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700 mb-1">¿Puedo solicitar una demo?</p>
                            <p class="text-gray-600 text-sm">¡Por supuesto! Agendamos demos personalizadas por videollamada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            ¿Prefieres una llamada rápida?
        </h2>
        <p class="text-xl mb-8 text-blue-100">
            Agenda una videollamada de 15 minutos y resolvemos todas tus dudas
        </p>
        <a href="https://calendly.com/rifasys" 
           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-600 rounded-full font-semibold hover:bg-blue-50 transform hover:scale-105 transition-all duration-200">
            <i class="fas fa-calendar-check"></i>
            Agendar Llamada
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Form submission handler
    document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Here you would normally send the form data to your backend
        // For now, just show success message
        
        const form = this;
        const button = form.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        button.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            // Hide form and show success
            form.style.display = 'none';
            document.getElementById('success-message').classList.remove('hidden');
            
            // Reset form
            form.reset();
            button.innerHTML = originalText;
            button.disabled = false;
            
            // Scroll to success message
            document.getElementById('success-message').scrollIntoView({ behavior: 'smooth' });
        }, 1500);
    });
</script>
@endpush

@endsection