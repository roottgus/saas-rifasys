<?php

namespace App\Filament\Tenant\Resources\OrderResource\Pages;

use App\Actions\Orders\CancelOrder;
use App\Actions\Orders\MarkOrderPaid;
use App\Filament\Tenant\Resources\OrderResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function getHeading(): string
    {
        return 'Orden ' . ($this->record->code ?? '');
    }

    public function getTitle(): string
    {
        return 'Orden';
    }

    protected function getHeaderActions(): array
    {
        $estado = $this->record->status instanceof \BackedEnum
            ? $this->record->status->value
            : (string) $this->record->status;

        $mapLabel = [
            'pending'   => 'Pendiente',
            'submitted' => 'Por revisar',
            'paid'      => 'Pagada',
            'cancelled' => 'Cancelada',
            'expired'   => 'Expirada',
        ];

        $mapColor = [
            'pending'   => 'warning',
            'submitted' => 'info',
            'paid'      => 'success',
            'cancelled' => 'gray',
            'expired'   => 'danger',
        ];

        $mapIcon = [
            'pending'   => 'heroicon-o-clock',
            'submitted' => 'heroicon-o-eye',
            'paid'      => 'heroicon-o-check-badge',
            'cancelled' => 'heroicon-o-x-circle',
            'expired'   => 'heroicon-o-exclamation-triangle',
        ];

        $puedeGestionar = in_array($estado, ['pending', 'submitted'], true);

        // WhatsApp (si hay número)
        $whatsUrl = null;
        if ($this->record->customer_phone) {
            $num = preg_replace('/\D+/', '', (string) $this->record->customer_phone);
            $msg = "Hola, sobre tu orden {$this->record->code}";
            $whatsUrl = "https://wa.me/{$num}?text=" . urlencode($msg);
        }

        // Voucher (si existe)
        $voucherUrl = $this->record->voucher_path
            ? Storage::url($this->record->voucher_path)
            : null;

        return [
            // Badge de estado (no clickeable) para dar contexto visual
            Actions\Action::make('estado_badge')
                ->label($mapLabel[$estado] ?? ucfirst($estado))
                ->icon($mapIcon[$estado] ?? 'heroicon-o-information-circle')
                ->color($mapColor[$estado] ?? 'gray')
                ->disabled()
                ->extraAttributes([
                    'class' => 'pointer-events-none opacity-100', // apariencia de badge, no botón
                ]),

            // Abrir voucher
            Actions\Action::make('voucher')
                ->label('Abrir voucher')
                ->icon('heroicon-o-photo')
                ->color('gray')
                ->visible(fn () => (bool) $voucherUrl)
                ->url(fn () => $voucherUrl, true)
                ->openUrlInNewTab(),

            // WhatsApp
            Actions\Action::make('whatsapp')
                ->label('WhatsApp')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->visible(fn () => (bool) $whatsUrl)
                ->url(fn () => $whatsUrl, true)
                ->openUrlInNewTab(),

            // Marcar pagada
            Actions\Action::make('markPaid')
                ->label('Marcar pagada')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $puedeGestionar)
                ->action(function () {
                    app(MarkOrderPaid::class)->handle($this->record);

                    Notification::make()
                        ->title('Orden marcada como pagada')
                        ->body("Se confirmaron los números y la orden {$this->record->code} pasó a «Pagada».")
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'expires_at']);
                }),

            // Cancelar orden
            Actions\Action::make('cancel')
                ->label('Cancelar orden')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => $puedeGestionar)
                ->action(function () {
                    app(CancelOrder::class)->handle($this->record);

                    Notification::make()
                        ->title('Orden cancelada')
                        ->body("La orden {$this->record->code} fue cancelada y los números quedaron disponibles.")
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'expires_at']);
                }),
        ];
    }
}
