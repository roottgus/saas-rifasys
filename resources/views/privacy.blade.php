{{-- resources/views/privacy.blade.php --}}
@extends('layouts.app')

@section('title', 'Política de Privacidad - Rifasys')

@section('content')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 overflow-hidden">
    {{-- Background Effects --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" xmlns="http://www.w3.org/2000/svg"%3E%3Cdefs%3E%3Cpattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse"%3E%3Cpath d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(59,130,246,0.1)" stroke-width="1"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width="100%25" height="100%25" fill="url(%23grid)"/%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/10 border border-green-500/20 rounded-full backdrop-blur-sm mb-6">
                <i class="fas fa-shield-check text-green-400"></i>
                <span class="text-sm font-medium text-green-300">100% Seguro y Confidencial</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Política de Privacidad
            </h1>
            <p class="text-xl text-slate-300">
                Tu privacidad es nuestra prioridad
            </p>
            <p class="text-sm text-slate-400 mt-4">
                Última actualización: {{ now()->format('d/m/Y') }}
            </p>
        </div>
    </div>
</section>

{{-- Privacy Content --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            
            {{-- Quick Summary --}}
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white mb-12">
                <h2 class="text-2xl font-bold mb-4">
                    <i class="fas fa-info-circle mr-2"></i>
                    Resumen Rápido
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-400 mt-1"></i>
                        <div>
                            <p class="font-semibold">No vendemos tus datos</p>
                            <p class="text-sm opacity-90">Nunca compartiremos tu información personal con terceros</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-400 mt-1"></i>
                        <div>
                            <p class="font-semibold">Encriptación total</p>
                            <p class="text-sm opacity-90">Usamos SSL y encriptación de grado bancario</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-400 mt-1"></i>
                        <div>
                            <p class="font-semibold">Control total</p>
                            <p class="text-sm opacity-90">Puedes modificar o eliminar tus datos cuando quieras</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-400 mt-1"></i>
                        <div>
                            <p class="font-semibold">Cumplimiento legal</p>
                            <p class="text-sm opacity-90">Cumplimos con GDPR y regulaciones locales</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                
                {{-- Table of Contents --}}
                <div class="bg-blue-50 rounded-xl p-6 mb-12">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-list mr-2 text-blue-600"></i>
                        Contenido
                    </h2>
                    <ul class="space-y-2">
                        <li><a href="#info-recopilamos" class="text-blue-600 hover:text-blue-700 hover:underline">1. Información que Recopilamos</a></li>
                        <li><a href="#uso-info" class="text-blue-600 hover:text-blue-700 hover:underline">2. Cómo Usamos tu Información</a></li>
                        <li><a href="#compartir" class="text-blue-600 hover:text-blue-700 hover:underline">3. Con Quién Compartimos</a></li>
                        <li><a href="#seguridad" class="text-blue-600 hover:text-blue-700 hover:underline">4. Seguridad de Datos</a></li>
                        <li><a href="#cookies" class="text-blue-600 hover:text-blue-700 hover:underline">5. Cookies y Tecnologías</a></li>
                        <li><a href="#derechos" class="text-blue-600 hover:text-blue-700 hover:underline">6. Tus Derechos</a></li>
                        <li><a href="#menores" class="text-blue-600 hover:text-blue-700 hover:underline">7. Privacidad de Menores</a></li>
                        <li><a href="#cambios" class="text-blue-600 hover:text-blue-700 hover:underline">8. Cambios en la Política</a></li>
                    </ul>
                </div>
                
                {{-- Content Sections --}}
                <div class="prose prose-lg max-w-none text-gray-700">
                    
                    <h2 id="info-recopilamos" class="text-2xl font-bold text-gray-900 mb-4">
                        1. Información que Recopilamos
                    </h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">1.1 Información que nos proporcionas</h3>
                    <ul class="list-disc list-inside mb-6 space-y-2">
                        <li><strong>Datos de cuenta:</strong> Nombre, email, teléfono, empresa</li>
                        <li><strong>Datos de pago:</strong> Información de facturación (procesada por proveedores seguros)</li>
                        <li><strong>Datos de rifas:</strong> Información sobre las rifas que creas y gestionas</li>
                        <li><strong>Comunicaciones:</strong> Mensajes que envías a nuestro soporte</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">1.2 Información recopilada automáticamente</h3>
                    <ul class="list-disc list-inside mb-6 space-y-2">
                        <li><strong>Datos de uso:</strong> Cómo interactúas con nuestro servicio</li>
                        <li><strong>Datos del dispositivo:</strong> IP, navegador, sistema operativo</li>
                        <li><strong>Cookies:</strong> Para mejorar tu experiencia y recordar preferencias</li>
                        <li><strong>Análisis:</strong> Métricas agregadas para mejorar el servicio</li>
                    </ul>
                    
                    <h2 id="uso-info" class="text-2xl font-bold text-gray-900 mb-4 mt-12">
                        2. Cómo Usamos tu Información
                    </h2>
                    
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 mb-6">
                        <p class="font-semibold text-green-900 mb-2">Usamos tu información para:</p>
                        <ul class="list-disc list-inside space-y-1 text-green-800">
                            <li>Proporcionar y mantener nuestro servicio</li>
                            <li>Procesar pagos y transacciones</li>
                            <li>Enviar notificaciones importantes sobre tu cuenta</li>
                            <li>Brindar soporte técnico y atención al cliente</li>
                            <li>Mejorar y personalizar tu experiencia</li>
                            <li>Cumplir con obligaciones legales</li>
                        </ul>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">NO usamos tu información para:</h3>
                    <ul class="list-disc list-inside mb-6 space-y-2 text-red-700">
                        <li>Venderla a terceros</li>
                        <li>Compartirla con anunciantes</li>
                        <li>Enviar spam o correos no solicitados</li>
                        <li>Fines no relacionados con el servicio</li>
                    </ul>
                    
                    <h2 id="compartir" class="text-2xl font-bold text-gray-900 mb-4 mt-12">
                        3. Con Quién Compartimos tu Información
                    </h2>
                    
                    <p class="mb-4">Solo compartimos información en estas situaciones:</p>
                    
                    <div class="space-y-4 mb-6">
                        <div class="border-l-4 border-blue-500 pl-6">
                            <p class="font-semibold text-gray-800">Proveedores de servicios</p>
                            <p class="text-sm text-gray-600">Procesadores de pago, servicios de hosting, análisis</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-6">
                            <p class="font-semibold text-gray-800">Requisitos legales</p>
                            <p class="text-sm text-gray-600">Cuando la ley lo requiera o para proteger derechos</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-6">
                            <p class="font-semibold text-gray-800">Con tu consentimiento</p>
                            <p class="text-sm text-gray-600">Solo si nos autorizas explícitamente</p>
                        </div>
                    </div>
                    
                    <h2 id="seguridad" class="text-2xl font-bold text-gray-900 mb-4 mt-12">
                        4. Seguridad de Datos
                    </h2>
                    
                    <div class="bg-blue-50 rounded-xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">
                            <i class="fas fa-lock mr-2"></i>
                            Medidas de Seguridad Implementadas
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-check text-blue-600 mt-1"></i>
                                <span class="text-sm">Encriptación SSL/TLS de 256 bits</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-check text-blue-600 mt-1"></i>
                                <span class="text-sm">Firewalls y protección DDoS</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-check text-blue-600 mt-1"></i>
                                <span class="text-sm">Backups diarios automáticos</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-check text-blue-600 mt-1"></i>
                                <span class="text-sm">Autenticación de dos factores</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-check text-blue-600 mt-1"></i>
                                <span class="text-sm">Monitoreo 24/7 de seguridad</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-check text-blue-600 mt-1"></i>
                                <span class="text-sm">Cumplimiento PCI DSS</span>
                            </div>
                        </div>
                    </div>
                    
                    <h2 id="cookies" class="text-2xl font-bold text-gray-900 mb-4 mt-12">
                        5. Cookies y Tecnologías Similares
                    </h2>
                    
                    <p class="mb-4">Utilizamos cookies para:</p>
                    <ul class="list-disc list-inside mb-6 space-y-2">
                        <li>Mantener tu sesión activa</li>
                        <li>Recordar tus preferencias</li>
                        <li>Analizar el uso del servicio</li>
                        <li>Mejorar la seguridad</li>
                    </ul>
                    
                    <p class="mb-6">
                        Puedes configurar tu navegador para rechazar cookies, pero algunas funciones del servicio podrían no funcionar correctamente.
                    </p>
                    
                    <h2 id="derechos" class="text-2xl font-bold text-gray-900 mb-4 mt-12">
                        6. Tus Derechos
                    </h2>
                    
                    <div class="bg-purple-50 rounded-xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Tienes derecho a:</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-user-check text-purple-600 mt-1"></i>
                                <div>
                                    <p class="font-semibold">Acceder</p>
                                    <p class="text-sm text-gray-600">Solicitar una copia de tus datos personales</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-edit text-purple-600 mt-1"></i>
                                <div>
                                    <p class="font-semibold">Rectificar</p>
                                    <p class="text-sm text-gray-600">Corregir datos inexactos o incompletos</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-trash-alt text-purple-600 mt-1"></i>
                                <div>
                                    <p class="font-semibold">Eliminar</p>
                                    <p class="text-sm text-gray-600">Solicitar la eliminación de tus datos</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-download text-purple-600 mt-1"></i>
                                <div>
                                    <p class="font-semibold">Portabilidad</p>
                                    <p class="text-sm text-gray-600">Recibir tus datos en formato estructurado</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-hand-paper text-purple-600 mt-1"></i>
                                <div>
                                    <p class="font-semibold">Oposición</p>
                                    <p class="text-sm text-gray-600">Oponerte a ciertos procesamientos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p class="mb-6">
                        Para ejercer cualquiera de estos derechos, contacta a nuestro equipo de privacidad en 
                        <a href="mailto:privacy@rifasys.com" class="text-blue-600 hover:underline">privacy@rifasys.com</a>
                    </p>
                    
                    <h2 id="menores" class="text-2xl font-bold text-gray-900 mb-4 mt-12">
                        7. Privacidad de Menores
                    </h2>
                    
                    <p class="mb-6">
                        Nuestro servicio no está dirigido a menores de 18 años. No recopilamos intencionalmente 
                        información personal de menores. Si eres padre o tutor y crees que tu hijo nos ha proporcionado 
                        información personal, contáctanos inmediatamente.
                    </p>
                    
                    <h2 id="cambios" class="text-2xl font-bold text-gray-900 mb-4 mt-12">
                        8. Cambios en esta Política
                    </h2>
                    
                    <p class="mb-6">
                        Podemos actualizar esta Política de Privacidad ocasionalmente. Te notificaremos sobre cambios 
                        significativos por email o mediante un aviso prominente en nuestro servicio. La fecha de 
                        "Última actualización" al inicio indica la última revisión.
                    </p>
                </div>
                
                {{-- Contact Section --}}
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">¿Tienes preguntas sobre privacidad?</h2>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="font-semibold text-gray-900 mb-3">
                                <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                Email de Privacidad
                            </h3>
                            <p class="text-gray-600 mb-2">Para consultas sobre tus datos:</p>
                            <a href="mailto:privacy@rifasys.com" class="text-blue-600 hover:underline">
                                privacy@rifasys.com
                            </a>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="font-semibold text-gray-900 mb-3">
                                <i class="fas fa-shield-alt mr-2 text-blue-600"></i>
                                Oficial de Protección de Datos
                            </h3>
                            <p class="text-gray-600 mb-2">DPO disponible en:</p>
                            <a href="mailto:dpo@rifasys.com" class="text-blue-600 hover:underline">
                                dpo@rifasys.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Trust Badges --}}
<section class="py-12 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-wrap justify-center items-center gap-8">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-lock text-green-500 text-xl"></i>
                    <span class="font-semibold">SSL Encriptado</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-shield-check text-blue-500 text-xl"></i>
                    <span class="font-semibold">GDPR Compliant</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-user-shield text-purple-500 text-xl"></i>
                    <span class="font-semibold">Datos Protegidos</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-certificate text-orange-500 text-xl"></i>
                    <span class="font-semibold">PCI DSS</span>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection