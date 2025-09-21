@extends('layouts.store')

@section('content')
@php
    $brand   = $tenant->brandSettings()->first();
    $primary = $tenant->primary_color;

    // Obtenemos el valor de status, sea Enum o string
    $statusValue = is_object($order->status) ? $order->status->value : $order->status;

    // Estados amigables y colores para badge
    $statusLabels = [
        'pending'   => 'Pendiente de pago',
        'submitted' => 'Pago en revisión',
        'paid'      => 'Pagado',
        'cancelled' => 'Cancelado',
        'expired'   => 'Expirado',
    ];
    $statusNice = $statusLabels[$statusValue] ?? ucfirst($statusValue);

    $statusColors = [
        'pending'   => 'bg-blue-100 text-blue-700 border-blue-300',
        'submitted' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'paid'      => 'bg-green-100 text-green-800 border-green-300',
        'cancelled' => 'bg-red-100 text-red-700 border-red-300',
        'expired'   => 'bg-red-100 text-red-700 border-red-300',
    ];
    $statusColor = $statusColors[$statusValue] ?? 'bg-gray-100 text-gray-700 border-gray-300';
@endphp

{{-- Full Screen Success Animation Overlay --}}
<div id="successOverlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999; background: #000; display: flex; align-items: center; justify-content: center;">
    {{-- Success Animation Container --}}
    <div style="position: relative; width: 300px; height: 300px; display: flex; align-items: center; justify-content: center;">
        {{-- Ripple Effects (ahora como contenedor principal) --}}
        <div class="ripple-container">
            <div class="ripple ripple-1"></div>
            <div class="ripple ripple-2"></div>
            <div class="ripple ripple-3"></div>
            
            {{-- Main Success Icon (ahora dentro del ripple container) --}}
            <div class="success-icon-wrapper">
                <div class="success-icon-circle">
                    <svg class="checkmark-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark-circle-bg" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
            </div>
        </div>
        
        {{-- Success Text (fuera del ripple container) --}}
        <div class="success-text-container" style="position: absolute; bottom: -100px; width: 100%; text-align: center;">
            <h2 class="success-title">¡Pago Exitoso!</h2>
            <p class="success-subtitle">Tu transacción ha sido procesada correctamente</p>
        </div>
        
        {{-- Loading Dots (debajo del texto) --}}
        <div class="loading-dots" style="position: absolute; bottom: -160px;">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</div>

{{-- Animated Background --}}
<div class="fixed inset-0 bg-gradient-to-br from-gray-50 via-blue-50/20 to-gray-50 -z-10" id="pageBackground">
    <div class="absolute inset-0 overflow-hidden">
        <div class="floating-circle absolute top-20 left-10 w-72 h-72 rounded-full opacity-5" style="background: {{ $primary }}"></div>
        <div class="floating-circle-reverse absolute bottom-20 right-10 w-96 h-96 rounded-full opacity-5" style="background: {{ $primary }}"></div>
        <div class="floating-circle absolute top-1/2 left-1/2 w-64 h-64 rounded-full opacity-3" style="background: {{ $primary }}"></div>
    </div>
</div>

<div class="min-h-screen flex items-center justify-center py-8 px-4 relative" id="mainContent">
    <div class="w-full max-w-2xl">
        
        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform hover:scale-[1.01] transition-transform duration-300">
            {{-- Header Gradient --}}
            <div class="relative h-20 overflow-hidden" style="background: linear-gradient(135deg, {{ $primary }} 0%, {{ $primary }}dd 100%);">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="pattern-dots absolute inset-0 opacity-10"></div>
                <div class="absolute top-2 left-1/2 transform -translate-x-1/2">
                    @if($brand && $brand->logo_path)
                        <div class="bg-white rounded-2xl p-2 shadow-xl">
                            <img src="{{ Storage::url($brand->logo_path) }}"
                                 alt="{{ $brand->nombre ?? 'Logo' }}"
                                 class="h-10 object-contain">
                        </div>
                    @else
                        <div class="bg-white rounded-2xl px-3 py-2 shadow-xl">
                            <span class="text-lg font-bold" style="color:{{ $primary }}">{{ $tenant->nombre ?? 'Rifasys' }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="px-4 pb-4">
                {{-- Success Message --}}
                <div class="text-center mt-5 mb-4">
                    <h1 class="text-2xl md:text-3xl font-bold mb-1 animate-fade-in" style="color: {{ $primary }}">
                        ¡Pago Recibido!
                    </h1>
                    <p class="text-gray-600 text-base">Transacción en proceso</p>
                    <div class="flex justify-center mt-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }} animate-pulse">
                            {{ $statusNice }}
                        </span>
                    </div>
                </div>

                {{-- Order Details Grid --}}
                <div class="grid md:grid-cols-2 gap-3 mb-5">
                    {{-- Left: Details Card --}}
                    <div class="order-1 md:order-1">
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-3 border border-gray-100 h-full">
                            <h3 class="text-xs font-semibold uppercase tracking-wider mb-2" style="color: {{ $primary }}">
                                <i class="fas fa-receipt mr-2"></i>Detalles
                            </h3>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                    <span class="text-gray-600 text-xs">Código:</span>
                                    <span class="font-mono font-bold" style="color: {{ $primary }}">{{ $order->code }}</span>
                                </div>
                                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                    <span class="text-gray-600 text-xs">Total:</span>
                                    <span class="text-lg font-bold" style="color: {{ $primary }}">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                @if($order->paymentAccount)
                                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                    <span class="text-gray-600 text-xs">Método:</span>
                                    <span class="font-semibold text-gray-800 text-xs">
                                        {{ $order->paymentAccount->etiqueta ?? 'Transferencia' }}
                                    </span>
                                </div>
                                @endif
                                @if($order->referencia)
                                <div class="flex justify-between items-center py-1">
                                    <span class="text-gray-600 text-xs">Referencia:</span>
                                    <span class="font-mono text-gray-800 text-xs">{{ $order->referencia }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Right: QR --}}
                    <div class="order-2 md:order-2 flex items-center justify-center">
                        @if(isset($qrCode) && $qrCode)
                        <div class="text-center">
                            <div class="bg-white rounded-2xl p-2 shadow-xl border-2"
                                 style="border-color: {{ $primary }}20;">
                                <img src="data:image/png;base64,{{ $qrCode }}"
                                     alt="Código QR"
                                     class="w-32 h-32 mx-auto">
                            </div>
                            <p class="mt-1 text-xs font-medium" style="color: {{ $primary }}">
                                <i class="fas fa-qrcode mr-1"></i> Escanea para verificar
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Tickets Section --}}
                <div class="mb-5">
                    <div class="rounded-2xl p-3 relative overflow-hidden"
                         style="background: linear-gradient(135deg, {{ $primary }}08 0%, {{ $primary }}15 100%);">
                        <h3 class="text-center text-base font-bold mb-3" style="color: {{ $primary }}">
                            <i class="fas fa-ticket-alt mr-2"></i>Tus Números
                        </h3>
                        <div class="flex flex-wrap justify-center gap-2">
                            @foreach($items as $item)
                            <div class="ticket-number group">
                                <div class="ticket-inner"
                                     style="padding: 8px 18px; font-size: 1rem; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 2px 10px 0 #16a34a33;">
                                    <span class="text-white font-extrabold tracking-widest drop-shadow-sm">
                                        #{{ str_pad($item->numero, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-xs text-gray-600">
                                <i class="fas fa-lock mr-1"></i> {{ count($items) }} {{ Str::plural('ticket', count($items)) }} asegurados
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Status Info Alert --}}
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center animate-pulse" 
                                     style="background: {{ $primary }}20;">
                                    <i class="fas fa-clock text-lg" style="color: {{ $primary }}"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 mb-1">Verificación en Proceso</h4>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Nuestro equipo está verificando tu pago. Este proceso generalmente toma entre 
                                    <strong>2 a 4 Horas</strong>. Te notificaremos por correo electrónico una vez confirmado.
                                </p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center text-xs bg-white px-3 py-1 rounded-full border border-gray-200">
                                        <i class="fas fa-envelope mr-1 text-blue-500"></i> Correo enviado
                                    </span>
                                    <span class="inline-flex items-center text-xs bg-white px-3 py-1 rounded-full border border-gray-200">
                                        <i class="fas fa-shield-alt mr-1 text-green-500"></i> Pago seguro
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Info --}}
                @if($order->customer_email || $order->customer_phone)
                <div class="text-center mb-6 py-4 border-t border-b border-gray-100">
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                        Confirmación enviada a:
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        @if($order->customer_email)
                        <span class="font-semibold text-gray-800">
                            <i class="fas fa-envelope mr-1" style="color: {{ $primary }}"></i>
                            {{ $order->customer_email }}
                        </span>
                        @endif
                        @if($order->customer_phone)
                        <span class="font-semibold text-gray-800">
                            <i class="fas fa-phone mr-1" style="color: {{ $primary }}"></i>
                            {{ $order->customer_phone }}
                        </span>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="grid md:grid-cols-2 gap-4">
                    <a href="{{ route('store.verify', ['tenant' => $tenant, 'code' => $order->code]) }}" 
                       class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:scale-[1.02]">
                        <button class="w-full px-6 py-4 text-white font-semibold shadow-lg transition-all duration-300 relative z-10"
                                style="background: linear-gradient(135deg, {{ $primary }} 0%, {{ $primary }}dd 100%);">
                            <span class="flex items-center justify-center gap-2">
                                <i class="fas fa-search"></i>
                                Verificar Estado
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </button>
                    </a>
                    
                    <a href="{{ route('store.home', $tenant) }}" 
                       class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:scale-[1.02]">
                        <button class="w-full px-6 py-4 font-semibold shadow-md transition-all duration-300 border-2 bg-white"
                                style="color: {{ $primary }}; border-color: {{ $primary }};">
                            <span class="flex items-center justify-center gap-2">
                                <i class="fas fa-home"></i>
                                Volver a la Tienda
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </button>
                    </a>
                </div>

                {{-- WhatsApp Support --}}
                @if($order->customer_phone)
                <div class="mt-6 text-center">
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer_phone) }}?text=Hola!%20Acabo%20de%20realizar%20un%20pago%20con%20el%20código%20{{ $order->code }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 text-white rounded-full font-semibold hover:bg-green-600 transition-colors shadow-lg hover:shadow-xl">
                        <i class="fab fa-whatsapp text-xl"></i>
                        Contactar por WhatsApp
                    </a>
                </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-100">
                <div class="flex flex-wrap items-center justify-between text-xs text-gray-500">
                    <span>© {{ date('Y') }} {{ $tenant->nombre ?? 'Rifasys' }} - Todos los derechos reservados</span>
                    <span class="flex items-center gap-4">
                        <span><i class="fas fa-lock mr-1"></i> Pago Seguro</span>
                        <span><i class="fas fa-shield-alt mr-1"></i> SSL Encriptado</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Trust Badges --}}
        <div class="mt-8 flex flex-wrap justify-center gap-6 text-gray-400">
            <div class="flex items-center gap-2">
                <i class="fab fa-cc-visa text-2xl"></i>
                <i class="fab fa-cc-mastercard text-2xl"></i>
                <i class="fab fa-cc-amex text-2xl"></i>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="flex items-center gap-1">
                    <i class="fas fa-users"></i> +10,000 clientes
                </span>
                <span class="flex items-center gap-1">
                    <i class="fas fa-star text-yellow-500"></i> 4.9/5
                </span>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT PARA LA ANIMACIÓN --}}
<script>
(function() {
    function initAnimation() {
        const overlay = document.getElementById('successOverlay');
        
        if (!overlay) {
            console.error('Overlay no encontrado');
            return;
        }
        
        // Agregar clase de animación después de 100ms
        setTimeout(function() {
            overlay.classList.add('animate-success');
        }, 100);
        
        // Ocultar overlay después de 3 segundos
        setTimeout(function() {
            overlay.style.transition = 'opacity 0.5s ease-out';
            overlay.style.opacity = '0';
            
            // Eliminar overlay del DOM después del fade out
            setTimeout(function() {
                overlay.style.display = 'none';
                overlay.remove();
            }, 500);
        }, 3000);
    }
    
    // Ejecutar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAnimation);
    } else {
        initAnimation();
    }
})();
</script>

{{-- CSS PARA LAS ANIMACIONES --}}
<style>
/* Success Animation Container */
.success-animation-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* Ripple Effects Container */
.ripple-container {
    position: relative;
    width: 300px;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ripple {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    border: 2px solid #22c55e;
    border-radius: 50%;
    opacity: 0;
}

.animate-success .ripple-1 {
    animation: ripple-effect 2s ease-out 0.5s;
}

.animate-success .ripple-2 {
    animation: ripple-effect 2s ease-out 0.8s;
}

.animate-success .ripple-3 {
    animation: ripple-effect 2s ease-out 1.1s;
}

@keyframes ripple-effect {
    0% {
        width: 120px;
        height: 120px;
        opacity: 0.8;
    }
    100% {
        width: 300px;
        height: 300px;
        opacity: 0;
    }
}

/* Success Icon - centrado en el ripple container */
.success-icon-wrapper {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
}

.success-icon-circle {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    border-radius: 50%;
    box-shadow: 0 10px 40px rgba(34, 197, 94, 0.4);
    transform: scale(0);
    opacity: 0;
}

.animate-success .success-icon-circle {
    animation: icon-appear 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.3s forwards;
}

@keyframes icon-appear {
    0% {
        transform: scale(0) rotate(-180deg);
        opacity: 0;
    }
    50% {
        transform: scale(1.2) rotate(10deg);
    }
    100% {
        transform: scale(1) rotate(0);
        opacity: 1;
    }
}

/* Checkmark SVG */
.checkmark-svg {
    width: 70px;
    height: 70px;
}

.checkmark-circle-bg {
    stroke: rgba(255, 255, 255, 0.3);
    stroke-width: 2;
}

.checkmark-check {
    stroke: #ffffff;
    stroke-width: 3;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
}

.animate-success .checkmark-check {
    animation: checkmark-stroke 0.8s cubic-bezier(0.65, 0, 0.45, 1) 0.9s forwards;
}

@keyframes checkmark-stroke {
    100% {
        stroke-dashoffset: 0;
    }
}

/* Success Text */
.success-text-container {
    margin-top: 30px;
    text-align: center;
    opacity: 0;
}

.animate-success .success-text-container {
    animation: text-appear 0.6s ease-out 1.5s forwards;
}

@keyframes text-appear {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.success-title {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 8px;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.success-subtitle {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
}

/* Loading Dots */
.loading-dots {
    margin-top: 40px;
    display: flex;
    gap: 8px;
    opacity: 0;
}

.animate-success .loading-dots {
    animation: dots-appear 0.4s ease-out 2s forwards;
}

@keyframes dots-appear {
    100% {
        opacity: 1;
    }
}

.dot {
    width: 8px;
    height: 8px;
    background-color: rgba(255, 255, 255, 0.6);
    border-radius: 50%;
}

.animate-success .dot:nth-child(1) {
    animation: dot-bounce 1.4s ease-in-out 2.2s infinite;
}

.animate-success .dot:nth-child(2) {
    animation: dot-bounce 1.4s ease-in-out 2.3s infinite;
}

.animate-success .dot:nth-child(3) {
    animation: dot-bounce 1.4s ease-in-out 2.4s infinite;
}

@keyframes dot-bounce {
    0%, 80%, 100% {
        transform: scale(1);
        opacity: 0.6;
    }
    40% {
        transform: scale(1.3);
        opacity: 1;
    }
}

/* Ticket Numbers */
.ticket-number {
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.ticket-number:hover {
    transform: translateY(-8px) scale(1.05);
}

.ticket-inner {
    padding: 12px 24px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
    border: 2px dashed #e5e7eb;
}

.ticket-inner::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
    transform: rotate(45deg);
    transition: all 0.5s;
    opacity: 0;
}

.ticket-number:hover .ticket-inner::before {
    animation: shine 0.5s ease-in-out;
}

@keyframes shine {
    0% {
        transform: translateX(-100%) translateY(-100%) rotate(45deg);
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        transform: translateX(100%) translateY(100%) rotate(45deg);
        opacity: 0;
    }
}

/* Floating Animation */
.floating-circle {
    animation: float 20s infinite ease-in-out;
}

.floating-circle-reverse {
    animation: float-reverse 25s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    25% { transform: translate(50px, -30px) scale(1.1); }
    50% { transform: translate(-30px, 50px) scale(0.9); }
    75% { transform: translate(30px, 30px) scale(1.05); }
}

@keyframes float-reverse {
    0%, 100% { transform: translate(0, 0) scale(1); }
    25% { transform: translate(-50px, 30px) scale(0.95); }
    50% { transform: translate(30px, -50px) scale(1.1); }
    75% { transform: translate(-30px, -30px) scale(1); }
}

/* Fade In Animation */
.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Pattern Dots Background */
.pattern-dots {
    background-image: radial-gradient(circle, rgba(255,255,255,0.3) 1px, transparent 1px);
    background-size: 15px 15px;
}
</style>

@endsection