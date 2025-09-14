<?php

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\Widget;
use App\Models\Order;
use App\Actions\Orders\MarkOrderPaid;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use App\Models\RifaNumero;

class RecentSalesWidget extends Widget
{
    protected static string $view = 'filament.tenant.widgets.recent-sales-widget';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public $selectedOrder = null;
    public $showModal = false;

    protected $listeners = ['refreshWidget' => '$refresh'];

    /**
     * Devuelve las órdenes más recientes con relaciones precargadas.
     */
    public function getRecentOrders(): Collection
    {
        return Order::with(['rifa', 'paymentAccount', 'items'])
            ->whereIn('status', ['pending', 'submitted']) // SOLO pendientes o por revisar
            ->orderByDesc('id')
            ->take(10)
            ->get();
    }

    /**
     * Abre el modal de aprobación con validación de voucher si aplica.
     */
    public function openApprovalModal($orderId): void
    {
        $this->selectedOrder = Order::with(['rifa', 'paymentAccount', 'items'])->find($orderId);

        if (
            $this->selectedOrder &&
            $this->selectedOrder->paymentAccount?->requiere_voucher &&
            !$this->selectedOrder->voucher_path
        ) {
            Notification::make()
                ->title('Atención')
                ->body('Este método de pago requiere comprobante pero no se ha cargado ninguno.')
                ->warning()
                ->send();
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedOrder = null;
    }

    /**
     * Aprueba el pago usando el action MarkOrderPaid (asegúrate de que maneja validación).
     */
    public function approvePayment(): void
    {
        if (!$this->selectedOrder) return;

        try {
            app(MarkOrderPaid::class)->handle($this->selectedOrder);

            Notification::make()
                ->title('Orden marcada como pagada')
                ->body("La orden {$this->selectedOrder->code} fue confirmada como pagada.")
                ->success()
                ->send();

            $this->dispatch('rowApproved', $this->selectedOrder->id);

            $this->closeModal();
            $this->dispatch('refreshWidget');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Ocurrió un error al aprobar el pago: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Rechaza el pago (cancela la orden y libera los números).
     */
    public function rejectPayment(): void
    {
        if (!$this->selectedOrder) return;

        try {
            // Cambia el estado de la orden a cancelada
            $this->selectedOrder->status = 'cancelled';
            $this->selectedOrder->expires_at = null;
            $this->selectedOrder->save();

            // Libera los números asociados
            foreach ($this->selectedOrder->items as $item) {
                $numero = RifaNumero::where('tenant_id', $this->selectedOrder->tenant_id)
                    ->where('rifa_id', $item->rifa_id)
                    ->where('numero', $item->numero)
                    ->first();
                if ($numero && $numero->estado === 'reservado') {
                    $numero->estado = 'disponible';
                    $numero->reservado_hasta = null;
                    $numero->save();
                }
            }

            Notification::make()
                ->title('Orden rechazada')
                ->body("La orden {$this->selectedOrder->code} fue rechazada y cancelada. Los números han sido liberados.")
                ->danger()
                ->send();

            $this->dispatch('rowApproved', $this->selectedOrder->id);
            $this->closeModal();
            $this->dispatch('refreshWidget');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Ocurrió un error al rechazar el pago: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Devuelve info de status + icono adaptado para usar con <x-dynamic-component>
     */
    public function getOrderStatus($status): array
    {
        $statusValue = $status instanceof \BackedEnum ? $status->value : $status;

        $map = [
            'pending' => [
                'label' => 'Pendiente',
                'color' => 'warning',
                'bg' => 'bg-yellow-100 dark:bg-yellow-900',
                'text' => 'text-yellow-800 dark:text-yellow-200',
                'icon' => 'heroicon-o-clock',
            ],
            'submitted' => [
                'label' => 'Por revisar',
                'color' => 'info',
                'bg' => 'bg-blue-100 dark:bg-blue-900',
                'text' => 'text-blue-800 dark:text-blue-200',
                'icon' => 'heroicon-o-magnifying-glass',
            ],
            'paid' => [
                'label' => 'Pagada',
                'color' => 'success',
                'bg' => 'bg-green-100 dark:bg-green-900',
                'text' => 'text-green-800 dark:text-green-200',
                'icon' => 'heroicon-o-check-circle',
            ],
            'cancelled' => [
                'label' => 'Cancelada',
                'color' => 'gray',
                'bg' => 'bg-gray-100 dark:bg-gray-900',
                'text' => 'text-gray-800 dark:text-gray-200',
                'icon' => 'heroicon-o-x-circle',
            ],
            'expired' => [
                'label' => 'Expirada',
                'color' => 'danger',
                'bg' => 'bg-red-100 dark:bg-red-900',
                'text' => 'text-red-800 dark:text-red-200',
                'icon' => 'heroicon-o-exclamation-circle',
            ],
        ];

        return $map[$statusValue] ?? [
            'label' => ucfirst($statusValue),
            'color' => 'gray',
            'bg' => 'bg-gray-100 dark:bg-gray-900',
            'text' => 'text-gray-800 dark:text-gray-200',
            'icon' => 'heroicon-o-question-mark-circle',
        ];
    }

    /**
     * Limpia el teléfono a formato internacional para WhatsApp.
     */
    public function formatPhoneNumber($phone): string
    {
        return $phone ? preg_replace('/\D+/', '', (string) $phone) : '';
    }

    /**
     * Devuelve la URL pública del voucher, si existe.
     */
    public function getVoucherUrl($path): ?string
    {
        return $path ? Storage::url($path) : null;
    }

    /**
     * Determina si el voucher es imagen (jpg/png/webp/gif).
     */
    public function isImageVoucher($path): bool
    {
        return $path && preg_match('/\.(jpe?g|png|webp|gif)$/i', $path);
    }
}
