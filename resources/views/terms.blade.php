{{-- resources/views/terms.blade.php --}}
@extends('layouts.app')

@section('title', 'Términos y Condiciones - Rifasys')

@section('content')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 overflow-hidden">
    {{-- Background Effects --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" xmlns="http://www.w3.org/2000/svg"%3E%3Cdefs%3E%3Cpattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse"%3E%3Cpath d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(59,130,246,0.1)" stroke-width="1"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width="100%25" height="100%25" fill="url(%23grid)"/%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Términos y Condiciones
            </h1>
            <p class="text-xl text-slate-300">
                Última actualización: {{ now()->format('d/m/Y') }}
            </p>
        </div>
    </div>
</section>

{{-- Terms Content --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                
                {{-- Table of Contents --}}
                <div class="bg-blue-50 rounded-xl p-6 mb-12">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-list mr-2 text-blue-600"></i>
                        Índice de Contenidos
                    </h2>
                    <ul class="space-y-2">
                        <li><a href="#intro" class="text-blue-600 hover:text-blue-700 hover:underline">1. Introducción</a></li>
                        <li><a href="#uso" class="text-blue-600 hover:text-blue-700 hover:underline">2. Uso del Servicio</a></li>
                        <li><a href="#cuentas" class="text-blue-600 hover:text-blue-700 hover:underline">3. Cuentas de Usuario</a></li>
                        <li><a href="#pagos" class="text-blue-600 hover:text-blue-700 hover:underline">4. Pagos y Facturación</a></li>
                        <li><a href="#rifas" class="text-blue-600 hover:text-blue-700 hover:underline">5. Gestión de Rifas</a></li>
                        <li><a href="#responsabilidades" class="text-blue-600 hover:text-blue-700 hover:underline">6. Responsabilidades</a></li>
                        <li><a href="#propiedad" class="text-blue-600 hover:text-blue-700 hover:underline">7. Propiedad Intelectual</a></li>
                        <li><a href="#limitacion" class="text-blue-600 hover:text-blue-700 hover:underline">8. Limitación de Responsabilidad</a></li>
                        <li><a href="#terminacion" class="text-blue-600 hover:text-blue-700 hover:underline">9. Terminación</a></li>
                        <li><a href="#contacto" class="text-blue-600 hover:text-blue-700 hover:underline">10. Contacto</a></li>
                    </ul>
                </div>
                
                {{-- Content Sections --}}
                <div class="prose prose-lg max-w-none text-gray-700">
                    
                    <h2 id="terminacion" class="text-2xl font-bold text-gray-900 mb-4 mt-12">9. Terminación</h2>
                    <p class="mb-6">
                        Podemos suspender o terminar su acceso al Servicio en cualquier momento si viola estos Términos. 
                        Usted puede cancelar su cuenta en cualquier momento desde su panel de control o contactándonos directamente.
                    </p>
                    
                    <h2 id="contacto" class="text-2xl font-bold text-gray-900 mb-4 mt-12">10. Contacto</h2>
                    <p class="mb-6">
                        Si tiene preguntas sobre estos Términos y Condiciones, puede contactarnos en:
                    </p>
                    <div class="bg-blue-50 rounded-lg p-6">
                        <p class="mb-2"><strong>Rifasys</strong></p>
                        <p class="mb-2">Email: legal@rifasys.com</p>
                        <p class="mb-2">Teléfono: +1 (234) 567-8900</p>
                        <p>Horario: Lunes a Viernes, 9:00 AM - 6:00 PM</p>
                    </div>
                </div>
                
                {{-- Agreement Section --}}
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white">
                        <h3 class="text-xl font-bold mb-3">Aceptación de Términos</h3>
                        <p class="mb-4">
                            Al utilizar Rifasys, usted reconoce que ha leído, entendido y acepta estar sujeto a estos Términos y Condiciones.
                        </p>
                        <p class="text-sm opacity-90">
                            Fecha de entrada en vigor: 1 de enero de 2024
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Quick Links --}}
<section class="py-12 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-3 gap-6">
                <a href="/privacidad" class="group bg-gray-50 rounded-xl p-6 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-shield-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Política de Privacidad</p>
                            <p class="text-sm text-gray-600">Cómo protegemos tus datos</p>
                        </div>
                    </div>
                </a>
                
                <a href="/contacto" class="group bg-gray-50 rounded-xl p-6 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-headset text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Soporte</p>
                            <p class="text-sm text-gray-600">¿Necesitas ayuda?</p>
                        </div>
                    </div>
                </a>
                
                <a href="/#precios" class="group bg-gray-50 rounded-xl p-6 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-tag text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Planes y Precios</p>
                            <p class="text-sm text-gray-600">Conoce nuestros planes</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

@endsectiond="intro" class="text-2xl font-bold text-gray-900 mb-4">1. Introducción</h2>
                    <p class="mb-6">
                        Bienvenido a Rifasys. Estos Términos y Condiciones ("Términos") rigen el uso de nuestro servicio de software 
                        para la gestión de rifas en línea y puntos de venta físicos ("Servicio"). Al acceder o utilizar Rifasys, 
                        usted acepta estar sujeto a estos Términos. Si no está de acuerdo con estos Términos, no debe utilizar nuestro Servicio.
                    </p>
                    
                    <h2 id="uso" class="text-2xl font-bold text-gray-900 mb-4 mt-12">2. Uso del Servicio</h2>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">2.1 Elegibilidad</h3>
                    <p class="mb-4">
                        Debe tener al menos 18 años de edad y la capacidad legal para celebrar contratos vinculantes para utilizar nuestro Servicio.
                    </p>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">2.2 Uso Aceptable</h3>
                    <p class="mb-4">Usted se compromete a utilizar Rifasys únicamente para:</p>
                    <ul class="list-disc list-inside mb-6 space-y-2">
                        <li>Gestionar rifas legales y autorizadas según las leyes de su jurisdicción</li>
                        <li>Actividades comerciales legítimas relacionadas con sorteos y rifas</li>
                        <li>Cumplir con todas las leyes y regulaciones aplicables</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">2.3 Restricciones</h3>
                    <p class="mb-4">Está prohibido:</p>
                    <ul class="list-disc list-inside mb-6 space-y-2">
                        <li>Usar el servicio para actividades ilegales o fraudulentas</li>
                        <li>Realizar rifas sin las autorizaciones legales correspondientes</li>
                        <li>Modificar, copiar o distribuir el software sin autorización</li>
                        <li>Intentar acceder a áreas restringidas del sistema</li>
                        <li>Usar el servicio para enviar spam o contenido no solicitado</li>
                    </ul>
                    
                    <h2 id="cuentas" class="text-2xl font-bold text-gray-900 mb-4 mt-12">3. Cuentas de Usuario</h2>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">3.1 Registro</h3>
                    <p class="mb-4">
                        Para acceder a ciertas funciones del Servicio, debe registrarse y crear una cuenta. 
                        Debe proporcionar información precisa, completa y actualizada durante el registro.
                    </p>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">3.2 Seguridad de la Cuenta</h3>
                    <p class="mb-6">
                        Usted es responsable de mantener la confidencialidad de sus credenciales de acceso y 
                        de todas las actividades que ocurran bajo su cuenta.
                    </p>
                    
                    <h2 id="pagos" class="text-2xl font-bold text-gray-900 mb-4 mt-12">4. Pagos y Facturación</h2>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">4.1 Planes y Precios</h3>
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <p class="mb-4"><strong>Rifasys Plus:</strong> $150 USD por rifa (pago único)</p>
                        <p><strong>Rifasys Premium:</strong> Precio personalizado según requerimientos</p>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">4.2 Política de Pagos</h3>
                    <p class="mb-6">
                        Los pagos son procesados de manera segura a través de nuestros proveedores de pago autorizados. 
                        Todos los precios están sujetos a cambios con 30 días de aviso previo.
                    </p>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">4.3 Reembolsos</h3>
                    <p class="mb-6">
                        Ofrecemos una garantía de satisfacción de 30 días. Si no está satisfecho con el servicio, 
                        puede solicitar un reembolso completo dentro de los primeros 30 días.
                    </p>
                    
                    <h2 id="rifas" class="text-2xl font-bold text-gray-900 mb-4 mt-12">5. Gestión de Rifas</h2>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">5.1 Responsabilidad Legal</h3>
                    <p class="mb-4">
                        El usuario es el único responsable de cumplir con todas las leyes y regulaciones locales, 
                        estatales y federales relacionadas con la organización de rifas y sorteos.
                    </p>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">5.2 Transparencia</h3>
                    <p class="mb-6">
                        Rifasys proporciona herramientas para realizar sorteos transparentes y auditables, 
                        pero no garantiza ni es responsable de la legitimidad de las rifas organizadas por los usuarios.
                    </p>
                    
                    <h2 id="responsabilidades" class="text-2xl font-bold text-gray-900 mb-4 mt-12">6. Responsabilidades</h2>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">6.1 Nuestras Responsabilidades</h3>
                    <ul class="list-disc list-inside mb-6 space-y-2">
                        <li>Proporcionar el software y servicios según lo descrito</li>
                        <li>Mantener la seguridad y disponibilidad del sistema</li>
                        <li>Ofrecer soporte técnico según el plan contratado</li>
                        <li>Proteger la información personal según nuestra Política de Privacidad</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">6.2 Sus Responsabilidades</h3>
                    <ul class="list-disc list-inside mb-6 space-y-2">
                        <li>Cumplir con todas las leyes aplicables</li>
                        <li>Obtener los permisos necesarios para realizar rifas</li>
                        <li>Entregar los premios prometidos a los ganadores</li>
                        <li>Mantener registros precisos de las transacciones</li>
                    </ul>
                    
                    <h2 id="propiedad" class="text-2xl font-bold text-gray-900 mb-4 mt-12">7. Propiedad Intelectual</h2>
                    <p class="mb-6">
                        Todo el contenido, diseño, software y tecnología de Rifasys está protegido por derechos de autor 
                        y otros derechos de propiedad intelectual. No puede copiar, modificar, distribuir o crear trabajos 
                        derivados sin nuestro consentimiento expreso por escrito.
                    </p>
                    
                    <h2 id="limitacion" class="text-2xl font-bold text-gray-900 mb-4 mt-12">8. Limitación de Responsabilidad</h2>
                    <p class="mb-6">
                        EN LA MÁXIMA MEDIDA PERMITIDA POR LA LEY, RIFASYS NO SERÁ RESPONSABLE POR DAÑOS INDIRECTOS, 
                        INCIDENTALES, ESPECIALES, CONSECUENTES O PUNITIVOS, INCLUIDA LA PÉRDIDA DE BENEFICIOS, DATOS, 
                        USO U OTRAS PÉRDIDAS INTANGIBLES.
                    </p>
                    
                    <h2 id="terminacion" class="text-2xl font-bold text-gray-900 mb-4 mt-12">9. Terminación</h2>
                    <p class="mb-6">
                        Podemos suspender o terminar su acceso al Servicio en cualquier momento si viola estos Términos. 
                        Usted puede cancelar su cuenta en cualquier momento desde su panel de control o contactándonos directamente.
                    </p>
                    
                    <h2 id="contacto" class="text-2xl font-bold text-gray-900 mb-4 mt-12">10. Contacto</h2>
                    <p class="mb-6">
                        Si tiene preguntas sobre estos Términos y Condiciones, puede contactarnos en:
                    </p>
                    <div class="bg-blue-50 rounded-lg p-6">
                        <p class="mb-2"><strong>Rifasys</strong></p>
                        <p class="mb-2">Email: legal@rifasys.com</p>
                        <p class="mb-2">Teléfono: +1 (234) 567-8900</p>
                        <p>Horario: Lunes a Viernes, 9:00 AM - 6:00 PM</p>
                    </div>