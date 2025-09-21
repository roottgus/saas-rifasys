{{-- Professional Raffle Checkout Component --}}
@php
    $cantidad = $minSel;
    $cantidadMax = $maxSel;
    $reserveUrl = $reserveUrl ?? null;
@endphp

<div 
    x-data="raffleCheckoutPro({ 
        min: {{ $minSel }}, 
        max: {{ $maxSel }}, 
        price: {{ $price }}, 
        available: {{ $available }}, 
        numsCount: {{ count($nums) }},
        quickSelections: {{ Js::from($quickSelections ?? []) }}
    })"

    class="w-full max-w-5xl mx-auto
       bg-gradient-to-br from-blue-100 via-blue-50 to-blue-200
       rounded-2xl shadow-xl p-4 md:p-8 my-8 border border-blue-100/70"
>
    {{-- HEADER SECTION --}}
    <div class="text-center mb-4">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1 flex items-center justify-center gap-3">
            <i class="fa-solid fa-ticket text-blue-600"></i>
            COMPRA TUS TICKETS
        </h2>
        <p class="text-gray-600 text-xs md:text-sm">Selecciona la cantidad de tickets que deseas comprar</p>
    </div>

    {{-- QUICK SELECTION SECTION --}}
    <div class="flex flex-col items-center gap-3 mb-4" x-show="!manual" x-transition>
        {{-- Quantity Selector --}}
        <div class="flex flex-col items-center gap-2">
            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">CANTIDAD DE TICKETS</label>
            <div class="flex items-center gap-0 bg-gray-100 rounded-full p-1">
                <button 
                    type="button" 
                    class="w-10 h-10 flex items-center justify-center bg-white text-gray-700 font-semibold transition-all duration-200 hover:bg-blue-500 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed rounded-l-full shadow-sm"
                    @click="decrement"
                    :disabled="cantidad <= min"
                >
                    <i class="fa-solid fa-minus"></i>
                </button>
                <input 
                    type="text" 
                    readonly 
                    id="cantidad" 
                    x-model="cantidad"
                    class="w-16 h-10 text-center text-xl font-bold text-gray-800 bg-white border-0 focus:outline-none" 
                />
                <button 
                    type="button" 
                    class="w-10 h-10 flex items-center justify-center bg-white text-gray-700 font-semibold transition-all duration-200 hover:bg-blue-500 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed rounded-r-full shadow-sm"
                    @click="increment"
                    :disabled="cantidad >= max"
                >
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>

        {{-- Info Badge --}}
        <div class="flex items-center gap-2 text-xs text-gray-600 bg-green-200 px-3 py-1 rounded-full">
            <i class="fa-solid fa-info-circle text-blue-500"></i>
            Mín: <strong x-text="min"></strong> | Máx: <strong x-text="max"></strong>
        </div>

        {{-- Quick Selections desde configuración --}}
<div class="flex flex-wrap justify-center gap-3 mt-4">
    <template x-for="btn in quickButtons" :key="btn.cantidad">
        <button
            type="button"
            @click="setQuickSelection(btn.cantidad, btn.descuento)"
            class="relative flex flex-col items-center justify-end
                w-12 h-16 md:w-12 md:h-12 sm:w-10 sm:h-10
                bg-gradient-to-b from-blue-50 via-white to-blue-100
                rounded-t-2xl rounded-b-lg shadow-lg border transition-all duration-150
                outline-none select-none group
                hover:scale-105 focus:ring-2 focus:ring-blue-200
                active:scale-95"
            :class="cantidad === btn.cantidad 
                ? 'border-blue-600 bg-gradient-to-b from-blue-100 to-blue-300 shadow-xl scale-105 z-10'
                : 'border-gray-200'"
        >
            {{-- ICONO EN CÍRCULO --}}
            <span class="absolute -top-5 flex items-center justify-center w-8 h-8 rounded-full shadow-md 
                        bg-white border-2 border-blue-100 group-hover:border-blue-400 z-20 opacity-80">
                <i class="fa-solid fa-bolt text-[rgb(255,215,0)] text-xl opacity-50"></i>
            </span>
            {{-- SIGNO + CON EFECTO PULSE --}}
            <span class="absolute top-1 right-2 text-blue-400 text-lg font-bold z-30 select-none pulse-plus">+</span>
            
            {{-- NÚMERO --}}
            <span class="mt-8 mb-1 text-lg md:text-xl font-extrabold text-blue-900 drop-shadow-sm z-10" x-text="btn.cantidad"></span>
            {{-- DESCUENTO --}}
            <template x-if="btn.descuento > 0">
                <span class="absolute top-2 right-2 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold shadow z-30">
                    -<span x-text="btn.descuento"></span>%
                </span>
            </template>
        </button>
    </template>
</div>



    </div>

    {{-- RANDOM SELECTION MESSAGE --}}
    <div 
        x-show="aleatorio && !manual" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        class="w-full p-3 rounded-lg flex items-center gap-2 text-xs font-medium bg-green-50 text-green-800 border border-green-200 mb-2"
    >
        <i class="fa-solid fa-shuffle animate-pulse"></i>
        <span>Los tickets serán seleccionados aleatoriamente al procesar el pago</span>
    </div>

    {{-- MANUAL SELECTION TOGGLE --}}
    <div class="w-full flex flex-col items-center gap-2">
        <button 
            type="button" 
            id="btnToggleManual"
            @click="toggleManual"
            class="flex items-center gap-2 font-semibold text-xs transition-colors duration-200"
            :class="manual ? 'text-red-600 hover:text-red-700' : 'text-blue-600 hover:text-blue-700'"
        >
            <i :class="manual ? 'fa-solid fa-times-circle' : 'fa-regular fa-hand-pointer'"></i>
            <span x-text="manual ? 'Cerrar selección manual' : 'Seleccionar números específicos'"></span>
        </button>

        {{-- Manual Numbers Grid --}}
        <div id="manualNumbersGrid" x-show="manual" x-cloak x-transition class="w-full mt-2">
            @include('store.partials.rifa-numbers-embedded', [
                'nums' => $nums,
                'maxSel' => $maxSel,
                'reserveUrl' => $reserveUrl,
                'alpineBind' => "manualSelectedNums, max, toggleManualNum"
            ])
        </div>
    </div>

    {{-- ORDER SUMMARY --}}
    <div class="rounded-xl border border-red-300 bg-white px-3 py-2 md:px-4 md:py-3 mb-2 shadow-sm mt-4">
        <div class="flex items-center justify-between text-center gap-4">
            <div>
                <div class="text-xs text-gray-500">Tickets</div>
                <div class="font-bold text-lg md:text-xl text-gray-800" x-text="manual ? manualSelectedNums.length : cantidad"></div>
            </div>
            <div>
                <div class="text-xs text-gray-500">Precio x ticket</div>
                <div class="font-semibold text-lg text-gray-700">$<span x-text="price.toFixed(2)"></span></div>
            </div>
            <div class="border-l border-blue-50 pl-4 min-w-[90px] text-right">
                <div class="text-xs text-gray-400 uppercase">Total</div>
                <span class="text-blue-700 font-black text-xl md:text-2xl leading-none">
                    $<span x-text="calculateTotal()"></span>
                </span>
                <span class="text-xs text-gray-400">USD</span>
            </div>
        </div>
    </div>

    {{-- Savings Badge (optional) --}}
    <div x-show="cantidad >= 10" class="mt-1 text-center">
        <span class="inline-block bg-yellow-50 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
            <i class="fa-solid fa-tag mr-1"></i>
            ¡Ahorra comprando más tickets!
        </span>
    </div>

    {{-- PAYMENT BUTTON --}}
    <div class="mt-2 flex justify-center">
        <button 
            type="button" 
            id="btnProceed"
            @click="proceedToPayment"
            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800
                   text-white font-semibold px-5 py-2.5 md:px-7 md:py-3 rounded-lg
                   shadow transition-all duration-200 hover:scale-[1.01]
                   disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="!isValidSelection()"
        >
            <span class="flex items-center gap-2 text-sm md:text-base">
                <i class="fa-solid fa-lock text-base md:text-lg"></i>
                <span>Procesar pago</span>
                <i class="fa-solid fa-arrow-right animate-pulse"></i>
            </span>
        </button>
    </div>

    {{-- Security Badges --}}
    <div class="flex flex-col md:flex-row items-center justify-center gap-2 md:gap-4 text-gray-600 mt-2">
        <div class="flex items-center gap-2 text-xs font-medium leading-tight py-1">
            <i class="fa-solid fa-shield-check text-green-600 text-base"></i>
            <span class="whitespace-nowrap">Pago 100% Seguro y Encriptado</span>
        </div>
        <div class="hidden md:block h-5 w-px bg-gray-200 mx-2"></div>
        <div class="flex items-center gap-2 text-xl md:text-lg">
            <i class="fab fa-cc-visa text-blue-700 opacity-80"></i>
            <i class="fab fa-cc-mastercard text-orange-500 opacity-80"></i>
            <i class="fab fa-cc-amex text-blue-500 opacity-80"></i>
            <i class="fab fa-cc-paypal text-blue-600 opacity-80"></i>
        </div>
    </div>

    {{-- Trust Indicators --}}
    <div class="flex items-center gap-4 text-xs text-gray-500 mt-2 justify-center">
        <div class="flex items-center gap-1">
            <i class="fa-solid fa-users"></i>
            <span>+1,000 compradores</span>
        </div>
        <div class="flex items-center gap-1">
            <i class="fa-solid fa-star text-yellow-500"></i>
            <span>4.9/5 valoración</span>
        </div>
        <div class="flex items-center gap-1">
            <i class="fa-solid fa-clock"></i>
            <span>Entrega inmediata</span>
        </div>
    </div>
</div>

{{-- SCRIPT CONSOLIDADO - TODO EN UNO --}}

<script>
// ===================================================================
// COMPONENTE ALPINE.JS PARA EL CHECKOUT (CON SELECCIONES RÁPIDAS PERSONALIZADAS)
// ===================================================================

function raffleCheckoutPro({ min, max, price, available, numsCount, quickSelections = [] }) {
    return {
        // Propiedades de datos
        min: min,
        max: max,
        price: price,
        cantidad: min,
        manual: false,
        aleatorio: true,
        manualSelectedNums: [],
        quickButtons: [],
        quickSelections: quickSelections,
        descuento: 0,
        
        // Inicialización
        init() {
            // Establecer cantidad mínima
            this.cantidad = this.min;
            this.aleatorio = true;
            
            // Configurar botones rápidos
            if (this.quickSelections && Array.isArray(this.quickSelections) && this.quickSelections.length > 0) {
                // Usar las selecciones configuradas en el admin
                this.quickButtons = this.quickSelections.map(sel => ({
                    cantidad: parseInt(sel.cantidad || 0),
                    etiqueta: sel.etiqueta || `${sel.cantidad} Tickets`,
                    descuento: parseFloat(sel.descuento || 0)
                })).filter(btn => btn.cantidad > 0); // Filtrar solo válidos
            } 
            
            // Si no hay configuración o está vacía, usar valores por defecto
            if (this.quickButtons.length === 0) {
                const defaultButtons = [5, 10, 20, 50, 100];
                this.quickButtons = defaultButtons
                    .filter(n => n >= this.min && n <= this.max)
                    .slice(0, 4)
                    .map(n => ({
                        cantidad: n,
                        etiqueta: `${n} Tickets`,
                        descuento: 0
                    }));
            }
            
            // Configurar navegación por teclado
            this.setupKeyboardNav();
            
            // Scroll suave al elemento
            this.$el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },
        
        // Método para establecer cantidad manualmente
        setCantidad(val) {
            const newVal = Math.max(this.min, Math.min(this.max, val));
            this.cantidad = newVal;
            this.aleatorio = true;
            this.manual = false;
            this.clearManualSelection();
            this.descuento = 0; // Reset descuento
            this.animateButton();
        },
        
        // Método para selección rápida con descuento
        setQuickSelection(cant, desc = 0) {
            this.cantidad = Math.max(this.min, Math.min(this.max, parseInt(cant)));
            this.descuento = parseFloat(desc) || 0;
            this.aleatorio = true;
            this.manual = false;
            this.clearManualSelection();
            this.animateButton();
            
            
        },
        
        // Incrementar cantidad
        increment() {
            if (this.cantidad < this.max) {
                this.cantidad = this.cantidad + 1;
                this.aleatorio = true;
                this.descuento = 0; // Reset descuento al cambiar manualmente
                this.playSound('increment');
            } else {
                this.shake();
            }
        },
        
        // Decrementar cantidad
        decrement() {
            if (this.cantidad > this.min) {
                this.cantidad = this.cantidad - 1;
                this.aleatorio = true;
                this.descuento = 0; // Reset descuento al cambiar manualmente
                this.playSound('decrement');
            } else {
                this.shake();
            }
        },
        
        // Toggle selección manual
        toggleManual() {
            this.manual = !this.manual;
            if (this.manual) {
                this.aleatorio = false;
                this.cantidad = 0;
                this.descuento = 0;
                setTimeout(() => {
                    const grid = document.getElementById('manualNumbersGrid');
                    if (grid) {
                        grid.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'nearest' 
                        });
                    }
                }, 100);
            } else {
                this.clearManualSelection();
                this.cantidad = this.min;
                this.aleatorio = true;
            }
        },
        
        // Toggle número manual específico
        toggleManualNum(label) {
            const idx = this.manualSelectedNums.indexOf(label);
            if (idx === -1) {
                if (this.manualSelectedNums.length < this.max) {
                    this.manualSelectedNums.push(label);
                    this.playSound('select');
                } else {
                    this.shake();
                    this.showNotification('warning', `Máximo ${this.max} tickets permitidos`);
                }
            } else {
                this.manualSelectedNums.splice(idx, 1);
                this.playSound('deselect');
            }
        },
        
        // Limpiar selección manual
        clearManualSelection() {
            this.manualSelectedNums = [];
        },
        
        // Calcular total con descuento
        calculateTotal() {
            const qty = this.manual ? this.manualSelectedNums.length : this.cantidad;
            let total = qty * this.price;
            
            // Aplicar descuento si existe
            if (this.descuento && this.descuento > 0) {
                const discountAmount = total * (this.descuento / 100);
                total = total - discountAmount;
            }
            
            return total.toFixed(2);
        },
        
        // Calcular precio original (sin descuento)
        calculateOriginalTotal() {
            const qty = this.manual ? this.manualSelectedNums.length : this.cantidad;
            return (qty * this.price).toFixed(2);
        },
        
        // Verificar si hay descuento activo
        hasDiscount() {
            return this.descuento && this.descuento > 0;
        },
        
        // Calcular monto de descuento
        calculateDiscountAmount() {
            if (!this.hasDiscount()) return '0.00';
            const qty = this.manual ? this.manualSelectedNums.length : this.cantidad;
            const total = qty * this.price;
            const discountAmount = total * (this.descuento / 100);
            return discountAmount.toFixed(2);
        },
        
        // Validar selección
        isValidSelection() {
            if (this.manual) {
                return this.manualSelectedNums.length >= this.min && this.manualSelectedNums.length > 0;
            }
            return this.cantidad >= this.min && this.cantidad > 0;
        },
        
        // Procesar pago
        async proceedToPayment() {
            if (!this.isValidSelection()) {
                this.shake();
                this.showNotification('error', `Selecciona al menos ${this.min} tickets`);
                return;
            }
            
            const btn = document.getElementById('btnProceed');
            if (!btn) return;
            
            const originalBtnContent = btn.innerHTML;
            btn.disabled = true;
            btn.classList.add('opacity-75', 'pointer-events-none');
            
            const paymentData = {
                tipo: this.manual ? 'manual' : 'aleatorio',
                numeros: this.manual ? this.manualSelectedNums : [],
                cantidad: this.manual ? this.manualSelectedNums.length : this.cantidad,
                total: this.calculateTotal(),
                descuento: this.descuento || 0,
                timestamp: new Date().toISOString()
            };
            
            // Guardar datos para uso posterior
            window.currentPaymentData = paymentData;
            
            try {
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Preparando tu pago...';
                
                // Usar el sistema de reservación existente o crear uno nuevo
                const reservationSystem = window.raffleReservation || new RaffleReservationSystem();
                await reservationSystem.processDirectPayment(paymentData, btn);
                
            } catch (error) {
                console.error('Error en el proceso de pago:', error);
                
                // Restaurar botón
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'pointer-events-none');
                btn.innerHTML = originalBtnContent;
                
                // Mostrar error al usuario
                alert('Hubo un error al procesar tu solicitud. Por favor, intenta nuevamente.');
            }
        },
        
        // Configurar navegación por teclado
        setupKeyboardNav() {
            const handleKeydown = (e) => {
                // No interferir con campos de entrada
                if (e.target.matches('input, textarea, select')) return;
                
                switch(e.key) {
                    case 'ArrowUp':
                    case '+':
                        e.preventDefault();
                        this.increment();
                        break;
                    case 'ArrowDown':
                    case '-':
                        e.preventDefault();
                        this.decrement();
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (this.isValidSelection()) {
                            this.proceedToPayment();
                        }
                        break;
                    case 'm':
                    case 'M':
                        e.preventDefault();
                        this.toggleManual();
                        break;
                }
            };
            
            // Remover listener existente si existe
            document.removeEventListener('keydown', handleKeydown);
            // Agregar nuevo listener
            document.addEventListener('keydown', handleKeydown);
        },
        
        // Métodos de UI Helper
        animateButton() {
            const input = document.getElementById('cantidad');
            if (input) {
                input.classList.add('scale-110');
                setTimeout(() => {
                    input.classList.remove('scale-110');
                }, 200);
            }
        },
        
        shake() {
            this.$el.classList.add('animate-pulse');
            setTimeout(() => {
                this.$el.classList.remove('animate-pulse');
            }, 500);
        },
        
        playSound(type) {
            // Opcional: agregar efectos de sonido
            // const audio = new Audio(`/sounds/${type}.mp3`);
            // audio.play().catch(() => {});
        },
        
        showNotification(type, message) {
            console.log(`[${type.toUpperCase()}] ${message}`);
            
            // Disparar evento personalizado para sistema de notificaciones
            window.dispatchEvent(new CustomEvent('show-notification', {
                detail: { type, message }
            }));
        }
    };
}

// ===================================================================
// SISTEMA DE PAGO Y VALIDACIÓN CON BACKEND
// ===================================================================
document.addEventListener('DOMContentLoaded', function() {
    
    // Clase para manejar la reserva de números
    class RaffleReservationSystem {
        constructor() {
            // Obtener URLs de las rutas Laravel
            const baseUrl = window.location.origin;
            const pathParts = window.location.pathname.split('/');
            
            // Construir URLs basándose en la estructura actual
            this.reserveUrl = document.querySelector('[data-reserve-url]')?.dataset.reserveUrl || 
                             `${baseUrl}/t/${pathParts[2]}/rifas/${pathParts[4]}/get-available-numbers`;
            
            this.paymentUrl = document.querySelector('[data-payment-url]')?.dataset.paymentUrl || 
                             `${baseUrl}/t/${pathParts[2]}/r/${pathParts[4]}/reservar`;
            
            this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            
            // Guardar instancia global para reutilizar
            window.raffleReservation = this;
            
        }
        
        // Obtener números disponibles del backend
        async getAvailableNumbersFromBackend(cantidad) {
            try {
                const response = await fetch(this.reserveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        cantidad: cantidad,
                        tipo: 'aleatorio'
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Error al obtener números disponibles');
                }
                
                const data = await response.json();
                
                if (data.success && data.numbers) {
                    console.log(`✓ Números obtenidos: ${data.numbers.length}`);
                    return data.numbers;
                } else {
                    throw new Error(data.message || 'No hay suficientes números disponibles');
                }
            } catch (error) {
                console.error('Error al verificar disponibilidad:', error);
                throw error;
            }
        }
        
        // Generar números aleatorios localmente (fallback)
        generateRandomNumbersLocally(count) {
            const availableNumbers = this.getAvailableNumbersFromDOM();
            const selected = [];
            const available = [...availableNumbers];
            const maxCount = Math.min(count, available.length);
            
            for (let i = 0; i < maxCount; i++) {
                const randomIndex = Math.floor(Math.random() * available.length);
                selected.push(available[randomIndex]);
                available.splice(randomIndex, 1);
            }
            
            return selected;
        }
        
        // Obtener números disponibles del DOM
        getAvailableNumbersFromDOM() {
            const numberElements = document.querySelectorAll('[data-num]:not(.reserved):not(.sold)');
            const availableNums = [];
            
            numberElements.forEach(el => {
                const num = el.getAttribute('data-num');
                if (num) {
                    availableNums.push(num);
                }
            });
            
            // Si no hay elementos en el DOM, generar secuencia por defecto
            if (availableNums.length === 0) {
                const totalNums = 1000; // Valor por defecto
                for (let i = 0; i < totalNums; i++) {
                    availableNums.push(String(i).padStart(4, '0'));
                }
            }
            
            return availableNums;
        }
        
        // Proceso de pago directo sin modal
        async processDirectPayment(paymentData, btnElement) {
            const originalContent = btnElement.innerHTML;
            
            try {
                // Validaciones
                if (!paymentData.cantidad || paymentData.cantidad === 0) {
                    throw new Error('Debes seleccionar al menos un ticket.');
                }
                
                if (paymentData.tipo === 'manual' && (!paymentData.numeros || paymentData.numeros.length === 0)) {
                    throw new Error('Debes seleccionar los números específicos.');
                }
                
                // Obtener números si es aleatorio
                let numbersToSend = paymentData.numeros;
                
                if (paymentData.tipo === 'aleatorio') {
                    // Actualizar mensaje del botón
                    btnElement.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Seleccionando tus números...';
                    
                    try {
                        // Intentar obtener del backend
                        numbersToSend = await this.getAvailableNumbersFromBackend(paymentData.cantidad);
                        
                        // Mostrar números seleccionados brevemente
                        const preview = numbersToSend.slice(0, 3).join(', ');
                        const moreText = numbersToSend.length > 3 ? '...' : '';
                        btnElement.innerHTML = `<i class="fa fa-check text-green-500 mr-2"></i> Números: ${preview}${moreText}`;
                        
                        // Esperar un momento para que el usuario vea los números
                        await new Promise(resolve => setTimeout(resolve, 1000));
                        
                    } catch (error) {
                        console.warn('Usando generación local:', error);
                        numbersToSend = this.generateRandomNumbersLocally(paymentData.cantidad);
                        
                        if (!numbersToSend || numbersToSend.length === 0) {
                            throw new Error('No hay números disponibles. Intenta con menos cantidad.');
                        }
                    }
                }
                
                // Actualizar mensaje para redirección
                btnElement.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Redirigiendo al pago seguro...';
                
                // Preparar payload
                const payload = {
                    numbers: numbersToSend,
                    cantidad: paymentData.cantidad,
                    total: paymentData.total,
                    descuento: paymentData.descuento || 0,
                    flow: 'pay',
                    pay_now: 1,
                    tipo: paymentData.tipo,
                    validated: paymentData.tipo === 'aleatorio',
                    nombre: '',
                    whatsapp: '',
                    email: ''
                };
                
                console.log('📤 Enviando datos de pago:', {
                    tipo: payload.tipo,
                    cantidad: payload.cantidad,
                    total: payload.total,
                    descuento: payload.descuento,
                    numeros: payload.numbers.slice(0, 5) // Solo mostrar primeros 5 para debug
                });
                
                // Enviar al servidor
                const response = await fetch(this.paymentUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                
                const data = await response.json();
                
                if (data?.ok && data?.redirect) {
                    // Éxito - mostrar confirmación y redirigir
                    btnElement.innerHTML = '<i class="fa fa-check-circle text-green-500 mr-2"></i> ¡Listo! Redirigiendo...';
                    
                    // Limpiar datos almacenados
                    window.currentPaymentData = null;
                    
                    // Pequeña pausa para que vean el mensaje de éxito
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 500);
                    
                } else {
                    throw new Error(data?.message || 'No fue posible procesar el pago.');
                }
                
            } catch (error) {
                console.error('❌ Error:', error);
                
                // Restaurar botón
                btnElement.disabled = false;
                btnElement.classList.remove('opacity-75', 'pointer-events-none');
                btnElement.innerHTML = originalContent;
                
                // Mostrar error
                alert(error.message || 'Error al procesar el pago. Por favor, intenta nuevamente.');
                
                throw error; // Re-lanzar para que lo capture el componente Alpine
            }
        }
    }
    
    // Inicializar el sistema de reservas
    new RaffleReservationSystem();
});
</script>

{{-- Estilos opcionales --}}
<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

.payment-btn:hover .fa-arrow-right {
    animation: float 1s ease-in-out infinite;
}

@keyframes pulsePlus {
  0%, 100% { transform: scale(1); opacity: 0.7; }
  50%      { transform: scale(1.25); opacity: 1; }
}
.pulse-plus {
  animation: pulsePlus 1.1s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>