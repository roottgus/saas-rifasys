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
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-8 px-2">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border" style="border-color: {{ $primary }}30;">

        {{-- Logo dinámico (sin fondo extra) --}}
        <div class="flex justify-center pt-8 pb-1">
            @if($brand && $brand->logo_path)
                <img src="{{ Storage::url($brand->logo_path) }}"
                     alt="{{ $brand->nombre ?? 'Logo' }}"
                     class="h-24 object-contain"
                     style="max-width:140px;">
            @else
                <span class="text-2xl font-bold" style="color:{{ $primary }}">Rifasys</span>
            @endif
        </div>

        {{-- Título principal y subtítulo --}}
        <div class="text-center px-8">
            <h1 class="text-2xl font-bold text-gray-900 mt-2 mb-1"
                style="color: {{ $primary }}">{{ __('¡Pago Enviado!') }}</h1>
            <p class="text-gray-600 text-sm mb-5">Tu pago está siendo verificado</p>
        </div>

        {{-- Main Card Grid --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 px-6 sm:px-10 pb-5">

            {{-- Lado Izquierdo: Detalles de la compra --}}
            <div class="flex-1 w-full">
                <div class="bg-gray-50 rounded-xl px-5 py-4 mb-2 border" style="border-color: {{ $primary }}33;">
                    <h2 class="text-base font-semibold mb-4" style="color: {{ $primary }}">
                        Detalles de tu compra
                    </h2>
                    <dl class="grid grid-cols-1 gap-y-2">
                        <div class="flex justify-between items-center gap-2">
                            <dt class="text-gray-600">Código de orden:</dt>
                            <dd class="font-mono font-bold text-gray-900 text-right">{{ $order->code }}</dd>
                        </div>
                        <div class="flex justify-between items-center gap-2">
                            <dt class="text-gray-600">Total pagado:</dt>
                            <dd class="font-semibold text-right" style="color:{{ $primary }}">${{ number_format($order->total_amount, 2) }}</dd>
                        </div>
                        <div class="flex justify-between items-center gap-2">
                            <dt class="text-gray-600">Método de pago:</dt>
                            <dd class="text-right">
                                @if($order->paymentAccount)
    <span class="font-semibold">
        {{ $order->paymentAccount->etiqueta ?? ucfirst($order->paymentAccount->tipo ?? '') }}
    </span>
    @if($order->paymentAccount->banco)
        <span class="text-xs text-gray-500 ml-1">({{ $order->paymentAccount->banco }})</span>
    @endif
@else
    <span class="font-semibold text-gray-400">No especificado</span>
@endif

                            </dd>
                        </div>
                        @if($order->referencia)
                        <div class="flex justify-between items-center gap-2">
                            <dt class="text-gray-600">Referencia:</dt>
                            <dd class="font-mono text-gray-800 text-right">{{ $order->referencia }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Lado Derecho: QR --}}
            @if(isset($qrCode) && $qrCode)
            <div class="flex flex-col items-center mx-auto">
                <div class="bg-white border rounded-xl p-2 shadow-sm"
                     style="border-color: {{ $primary }}40;">
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR de la orden" class="w-36 h-36">
                </div>
                <span class="block text-xs" style="color:{{ $primary }}">Escanéalo para verificar tu orden</span>
            </div>
            @endif
        </div>

        {{-- Tus números en badges tipo ticket --}}
        <div class="px-8 pt-1 pb-4">
            <div class="rounded-xl p-4 mb-2 flex flex-col items-center"
                 style="background:{{ $primary }}12; border:1.5px solid {{ $primary }}35;">
                <h2 class="text-base font-semibold mb-3" style="color: {{ $primary }}">Tus números</h2>
                <div class="flex flex-wrap justify-center gap-3">
                    @foreach($items as $item)
                        <span class="rounded-full px-5 py-2 text-base font-bold shadow-sm border ticket-badge"
                              style="background: #fff; color: {{ $primary }}; border-color: {{ $primary }}; min-width:64px;">
                            #{{ str_pad($item->numero, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Información de verificación --}}
        <div class="px-8 pb-3">
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 flex items-start gap-3 mb-2">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" style="color:{{ $primary }}">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <div class="font-semibold mb-0.5" style="color: {{ $primary }};">Tu pago está en proceso de verificación</div>
                    <div class="text-xs text-yellow-800">
                        Nuestro equipo verificará tu comprobante de pago en las próximas horas.<br>
                        Recibirás un correo de confirmación una vez aprobado.
                    </div>
                </div>
            </div>
        </div>

        {{-- Información de contacto --}}
        <div class="text-center text-xs text-gray-600 px-8 mb-2">
            <p>Enviamos los detalles a: <strong class="text-gray-900">{{ $order->customer_email }}</strong></p>
            <p class="mt-1">¿Tienes preguntas? <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer_phone ?? '') }}" target="_blank" style="color: {{ $primary }}; font-weight: 600;" class="hover:underline">Contáctanos por WhatsApp</a></p>
        </div>

        {{-- Botones de acción --}}
        <div class="px-8 pb-7 flex flex-col items-center gap-3">
            <a href="{{ route('store.verify', ['tenant' => $tenant, 'code' => $order->code]) }}" class="w-full">
                <button class="w-full rounded-xl px-6 py-3 text-white font-semibold shadow transition"
                        style="background:{{ $primary }}; box-shadow:0 2px 8px 0 {{ $primary }}25;">
                    Verificar estado
                </button>
            </a>
            <a href="{{ route('store.home', $tenant) }}" class="w-full">
                <button class="w-full rounded-xl px-6 py-3 font-semibold shadow transition"
                        style="background:#F7FAFC; color:{{ $primary }}; border:1.5px solid {{ $primary }}40;">
                    Volver a la tienda
                </button>
            </a>
        </div>

        {{-- Pie: Estado como badge + soporte --}}
        <div class="border-t border-gray-100 px-6 pt-4 pb-5 text-center text-xs text-gray-400">
            <span class="inline-flex items-center gap-2">
                <span class="px-3 py-1 rounded-full border text-xs font-bold uppercase tracking-wide {{ $statusColor }}">
                    {{ $statusNice }}
                </span>
            </span>
            <br>
            Este mensaje fue enviado por Rifasys.<br>
            ¿Necesitas ayuda? Responde a este correo o contáctanos por WhatsApp.
        </div>
    </div>
</div>

{{-- Opcional: badge estilo ticket personalizado --}}
<style>
.ticket-badge {
    box-shadow: 0 1px 8px 0 rgba(44,44,84,0.04);
    border-style: dashed;
    letter-spacing: 1.5px;
    font-variant-numeric: tabular-nums;
}
</style>
@endsection
