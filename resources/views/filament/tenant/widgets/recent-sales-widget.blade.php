{{-- resources/views/filament/tenant/widgets/recent-sales-widget.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Ventas Recientes</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full recent-sales-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th class="text-right">Monto</th>
                        <th class="text-center">Estado</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->getRecentOrders() as $order)
                        @php
                            $status = $this->getOrderStatus($order->status);
                            $statusValue = $order->status instanceof \BackedEnum ? $order->status->value : $order->status;
                        @endphp
                        <tr>
                            <td class="order-code">{{ $order->code }}</td>
                            <td>{{ $order->customer_name ?? 'Desconocido' }}</td>
                            <td>{{ Str::limit($order->customer_email ?? 'roottgus@gmail.com', 22) }}</td>
                            <td class="text-right font-bold text-blue-700 dark:text-blue-400">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="text-center">
                                <span class="status-badge" data-status="{{ $statusValue }}">
                                    <x-dynamic-component :component="$status['icon'] ?? 'heroicon-o-bolt'" class="w-4 h-4 mr-1" />
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            <td class="text-gray-500 dark:text-gray-400">
                                {{ $order->created_at->diffForHumans() }}
                            </td>
                            <td class="text-center space-x-1">
                                @php
                                    $canApprove = in_array($statusValue, ['pending', 'submitted']);
                                @endphp
                                @if($canApprove)
                                    <button
                                        wire:click="openApprovalModal({{ $order->id }})"
                                        class="actions-btn approve-btn"
                                        title="Revisa los Pagos y Aprueba"
                                    >
                                        <x-heroicon-o-check class="w-4 h-4 mr-1 animate-pulse" />
                                        Revisar
                                    </button>
                                @endif
                                <a
                                    href="{{ route('filament.tenant.resources.orders.view', ['tenant' => \Filament\Facades\Filament::getTenant(), 'record' => $order]) }}"
                                    class="actions-btn"
                                    title="Ver Detalle"
                                >
                                    <x-heroicon-o-eye class="w-4 h-4 mr-1" />
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
    <tr>
        <td colspan="7" class="empty-state">
            <x-heroicon-o-check-circle class="w-8 h-8 mb-2 inline text-green-400" />
            <div class="font-bold">¡No hay pagos pendientes por autorizar!</div>
            <div class="text-xs">Todas las ventas ya fueron procesadas.</div>
        </td>
    </tr>
@endforelse

                </tbody>
            </table>
        </div>
    </x-filament::section>
<!-- Modal de Aprobación Rápida PRO -->
@if($showModal && $selectedOrder)
    <div 
        x-data="{ show: @entangle('showModal') }"
        x-show="show"
        class="fixed inset-0 z-50 flex items-center justify-center modal-blur-bg"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="backdrop-filter: blur(4px);"
    >
        <div 
            class="recent-sales-modal"
            x-show="show"
            x-transition:enter="transition transform duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-8"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition transform duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-8"
            @click.away="$wire.closeModal()"
            style="max-width: 760px;"
        >

            <!-- Header -->
            <div class="recent-sales-modal-header flex items-center justify-between">
                <div>
                    <div class="font-bold text-lg">
                        Aprobar Pago <span class="font-mono text-base opacity-80">#{{ $selectedOrder->code }}</span>
                    </div>
                    <div class="text-xs opacity-90 mt-1">Revisa los detalles del pago antes de aprobarlo</div>
                </div>
                <button 
                    @click="$wire.closeModal()" 
                    class="recent-sales-modal-close"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Body en 2 columnas -->
            <div class="recent-sales-modal-body rs-modal-flex">
                <!-- Columna izquierda -->
                <div class="rs-modal-left">
                    <!-- Info Cliente -->
                    <div class="recent-sales-modal-block">
                        <div class="recent-sales-modal-label">
                            <x-heroicon-o-user class="w-4 h-4" /> Información del Cliente
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Nombre:</span>
                                <span class="ml-1 font-medium">{{ $selectedOrder->customer_name ?? 'No especificado' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Email:</span>
                                <span class="ml-1 font-medium">{{ $selectedOrder->customer_email ?? 'roottgus@gmail.com' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">WhatsApp:</span>
                                @if($selectedOrder->customer_phone)
                                    <a href="https://wa.me/{{ $this->formatPhoneNumber($selectedOrder->customer_phone) }}" 
                                    class="ml-1 font-medium text-green-600 hover:underline" target="_blank">
                                    {{ $selectedOrder->customer_phone }}
                                    <x-heroicon-o-phone class="inline w-4 h-4 ml-1" />
                                    </a>
                                @else
                                    <span class="ml-1 text-gray-400">No registrado</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Fecha:</span>
                                <span class="ml-1 font-medium">{{ $selectedOrder->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Info Pago -->
                    <div class="recent-sales-modal-block">
                        <div class="recent-sales-modal-label">
                            <x-heroicon-o-currency-dollar class="w-4 h-4" /> Detalles del Pago
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Monto:</span>
                                <span class="ml-1 font-bold text-lg text-green-700 dark:text-green-400">
                                    ${{ number_format($selectedOrder->total_amount, 2) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Método:</span>
                                <span class="ml-1 font-medium">
                                    {{ $selectedOrder->paymentAccount->etiqueta ?? 'No especificado' }}
                                </span>
                                @if($selectedOrder->paymentAccount?->tipo)
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-700">
                                        {{ $selectedOrder->paymentAccount->tipo }}
                                    </span>
                                @endif
                            </div>
                            @if($selectedOrder->paymentAccount?->requiere_voucher)
                            <div class="col-span-2">
                                <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1" />
                                    Método requiere comprobante
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Info Rifa -->
                    <div class="recent-sales-modal-block">
                        <div class="recent-sales-modal-label">
                            <x-heroicon-o-ticket class="w-4 h-4" /> {{ $selectedOrder->rifa->titulo }}
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">
                            Números comprados ({{ $selectedOrder->items->count() }}):
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($selectedOrder->items->sortBy('numero') as $item)
                                <div class="rounded bg-white dark:bg-gray-800 border border-purple-200 dark:border-purple-700 px-3 py-2 text-center font-bold text-purple-700 dark:text-purple-300 text-sm" style="min-width:54px;">
                                    {{ str_pad($item->numero, 4, '0', STR_PAD_LEFT) }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notas -->
                    @if($selectedOrder->notes)
                        <div class="recent-sales-modal-block">
                            <div class="recent-sales-modal-label">
                                <x-heroicon-o-document-text class="w-4 h-4" /> Notas Adicionales
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $selectedOrder->notes }}
                            </div>
                        </div>
                    @endif

                    <!-- Si NO hay comprobante, mostrar aquí el bloque de 'no comprobante' -->
                    @if(!$selectedOrder->voucher_path)
                        <div class="recent-sales-modal-block">
                            <div class="flex items-center text-yellow-700 dark:text-yellow-300 gap-2 text-sm">
                                <x-heroicon-o-exclamation-circle class="w-5 h-5" />
                                No se ha cargado comprobante de pago
                                @if($selectedOrder->paymentAccount?->requiere_voucher)
                                    <strong>(Requerido)</strong>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Columna derecha: Comprobante SOLO si existe -->
                @if($selectedOrder->voucher_path)
                    @php
                        $voucherUrl = $this->getVoucherUrl($selectedOrder->voucher_path);
                        $isImage = $this->isImageVoucher($selectedOrder->voucher_path);
                    @endphp
                    <div class="rs-modal-right">
                        <div class="recent-sales-modal-block w-full">
                            <div class="recent-sales-modal-label text-green-700 dark:text-green-300">
                                <x-heroicon-o-document-check class="w-4 h-4" /> Comprobante de Pago
                            </div>
                            @if($isImage)
                                <div class="relative w-full">
                                    <img 
                                        src="{{ $voucherUrl }}" 
                                        alt="Comprobante de pago"
                                        class="rs-voucher-img"
                                        onclick="window.open('{{ $voucherUrl }}', '_blank')"
                                    >
                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-center w-full">
                                        <x-heroicon-o-eye class="inline w-4 h-4 mr-1" />
                                        Click en la imagen para ver en grande
                                    </div>
                                </div>
                            @else
                                <a href="{{ $voucherUrl }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors mt-2">
                                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-2" />
                                    Abrir Comprobante
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

            <!-- Footer -->
            <div class="recent-sales-modal-footer">
                
                <button
    wire:click="rejectPayment"
    type="button"
    class="reject-btn"
>
    <x-heroicon-o-x-circle class="w-4 h-4" />
    Rechazar Pago
</button>

                <button
                    wire:click="approvePayment"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60 cursor-not-allowed"
                    type="button"
                    class="approve-btn"
                >
                    <x-heroicon-o-check class="w-4 h-4" />
                    <span wire:loading.remove>Aprobar Pago</span>
                    <span wire:loading>Procesando...</span>
                </button>
            </div>
        </div>
    </div>
@endif

</x-filament-widgets::widget>
