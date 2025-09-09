{{-- resources/views/store/partials/terms-modal.blade.php --}}
<div
    x-data="{ open: false }"
    x-cloak
    x-on:open-terms-modal.window="open = true"
    x-on:keydown.escape.window="open = false"
>
    <!-- Trigger global para abrir modal desde cualquier lugar -->
    <template x-if="open">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
            <div
                class="bg-[#16171b] w-full max-w-2xl rounded-2xl shadow-2xl relative p-6 sm:p-8 border border-white/10"
                @click.away="open = false"
            >
                <button
                    class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-2xl"
                    @click="open = false"
                    title="Cerrar"
                >
                    <i class="fas fa-times"></i>
                </button>
                <h2 class="text-2xl sm:text-3xl font-extrabold mb-2 text-[var(--primary)] text-center drop-shadow">
                    Términos y Condiciones
                </h2>
                <div class="max-h-[60vh] overflow-y-auto text-base sm:text-[15px] leading-relaxed px-1 text-white">
                    <p class="mb-3">
                        Bienvenido/a a <strong>{{ config('app.name') }}</strong>. Al comprar y participar, aceptas nuestras políticas, el uso de tus datos y la normativa vigente. Revisa cuidadosamente las siguientes condiciones:
                    </p>
                    <ul class="list-disc pl-5 mb-3">
                        <li>Solo mayores de edad pueden participar.</li>
                        <li>Debes ingresar datos reales y actualizados.</li>
                        <li>El pago debe realizarse a las cuentas mostradas y cargar el comprobante en este sistema.</li>
                        <li>Debe transferir el monto exacto; no se realizan reembolsos por montos erróneos. Si existe una diferencia, el reembolso se realizará únicamente en forma de tickets.</li>
                        <li>Para órdenes superiores a 10 tickets, por favor comuníquese al número de soporte publicado.</li>
                        <li>La cantidad de números disponibles se detalla en la página de información específica de cada sorteo.</li>
                        <li>Los tickets se enviarán en un plazo máximo de 12 horas después de verificar su pago.</li>
                        <li>Nos reservamos el derecho de anular tickets con pagos dudosos o referencias no válidas.</li>
                        <li>La entrega de premios se realiza según las reglas de cada sorteo, debiendo mostrar tu cédula o documento.</li>
                        <li>Puedes consultar el estado de tu compra en la sección "Verificador".</li>
                        <li>Tu información será tratada con confidencialidad y solo se usará para la gestión de la rifa.</li>
                    </ul>
                    <p class="mb-1">Para dudas, escríbenos por WhatsApp o revisa el FAQ.</p>
                    <p class="text-xs text-gray-400">Última actualización: {{ date('d/m/Y') }}</p>
                </div>
                <div class="mt-6 flex justify-center">
                    <button
                        @click="open = false"
                        class="rounded-xl bg-[var(--primary)] hover:brightness-110 active:brightness-90 px-7 py-2 text-white font-bold shadow"
                    >Aceptar</button>
                </div>
            </div>
        </div>
    </template>
</div>
