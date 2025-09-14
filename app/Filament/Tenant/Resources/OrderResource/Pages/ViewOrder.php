<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\OrderResource\Pages;

use App\Actions\Orders\CancelOrder;
use App\Actions\Orders\MarkOrderPaid;
use App\Filament\Tenant\Resources\OrderResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    // --------------------- HEADER PRO -------------------------

    public function getHeading(): string|Htmlable
    {
        $order = $this->getRecord();
        $estado = $this->normalizeStatus($order->status);
        $statusConfig = $this->getStatusConfig($estado);

        // Inyectar estilos CSS corporativos
        $styles = $this->getCorporateStyles();

        $html = sprintf('
            <style>%s</style>
            <div class="corporate-header">
                <div class="header-main">
                    <h1 class="header-title">
                        <span class="title-icon">📋</span>
                        Orden <span class="order-number">#%s</span>
                    </h1>
                    <div class="header-meta">
                        <span class="meta-date">📅 %s</span>
                    </div>
                </div>
                <div class="header-status">
                    <span class="status-badge status-%s">
                        <span class="status-dot"></span>
                        %s
                    </span>
                </div>
            </div>',
            $styles,
            $order->code ?? 'N/A',
            $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'Sin fecha',
            $estado,
            $statusConfig['label']
        );

        return new HtmlString($html);
    }

    public function getTitle(): string
    {
        return sprintf('Orden %s - Panel Administrativo',
            $this->getRecord()->code ?? 'N/A'
        );
    }

    // --------------------- INFOLIST PRO -------------------------

    public function infolist(Infolist $infolist): Infolist
    {
        $order = $this->getRecord();
        $items = $order->items ?? [];
        $numeros = $items->pluck('numero')->toArray();

        return $infolist
            ->record($order)
            ->schema([
                Infolists\Components\Section::make('Información del Cliente')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('customer_name')
                                    ->label('Nombre')
                                    ->icon('heroicon-m-user')
                                    ->default('No especificado')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('customer_phone')
                                    ->label('WhatsApp')
                                    ->icon('heroicon-m-phone')
                                    ->default('No especificado')
                                    ->copyable()
                                    ->copyMessage('Número copiado'),
                                Infolists\Components\TextEntry::make('customer_email')
                                    ->label('Email')
                                    ->icon('heroicon-m-envelope')
                                    ->default('No especificado')
                                    ->copyable()
                                    ->copyMessage('Email copiado'),
                            ]),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Información del Pago')
                    ->icon('heroicon-o-credit-card')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('paymentAccount.etiqueta')
                                    ->label('Método de pago')
                                    ->icon('heroicon-m-banknotes')
                                    ->default('No especificado'),
                                Infolists\Components\TextEntry::make('paymentAccount.tipo')
                                    ->label('Tipo')
                                    ->icon('heroicon-m-tag')
                                    ->default('No especificado'),
                                Infolists\Components\TextEntry::make('paymentAccount.requiere_voucher')
                                    ->label('Requiere voucher')
                                    ->icon('heroicon-m-document-check')
                                    ->formatStateUsing(fn ($state) => $state ? 'Sí' : 'No')
                                    ->badge()
                                    ->color(fn ($state) => $state ? 'warning' : 'success'),
                            ]),
                        Infolists\Components\TextEntry::make('voucher_path')
                            ->label('Comprobante')
                            ->icon('heroicon-m-photo')
                            ->formatStateUsing(fn ($state) => $state ? 'Comprobante cargado' : 'Sin comprobante')
                            ->badge()
                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Información de la Rifa')
                    ->icon('heroicon-o-ticket')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('rifa.titulo')
                                    ->label('Título')
                                    ->icon('heroicon-m-trophy')
                                    ->default('No especificado')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('numeros_seleccionados')
                                    ->label('Números seleccionados')
                                    ->icon('heroicon-m-hashtag')
                                    ->state(fn($record) => $record->items?->pluck('numero')->implode(', ') ?: 'Sin números')
                                    ->badge()
                                    ->color('info'),

                            ]),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Resumen de la Orden')
                    ->icon('heroicon-o-calculator')
                    ->schema([
                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('items_count')
                                    ->label('Cantidad de ítems')
                                    ->icon('heroicon-m-shopping-cart')
                                    ->default(fn ($record) => $record->items?->count() ?? 0)
                                    ->badge()
                                    ->color('info'),
                                Infolists\Components\TextEntry::make('total_amount')
                                    ->label('Total')
                                    ->icon('heroicon-m-currency-dollar')
                                    ->money('USD')
                                    ->weight('bold')
                                    ->color('success'),
                                Infolists\Components\TextEntry::make('expires_at')
                                    ->label('Expira')
                                    ->icon('heroicon-m-clock')
                                    ->dateTime('d/m/Y H:i')
                                    ->color('warning'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Creada')
                                    ->icon('heroicon-m-calendar')
                                    ->dateTime('d/m/Y H:i'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    // --------------------- ACCIONES HEADER PRO -------------------------

    protected function getHeaderActions(): array
    {
        $order = $this->getRecord();
        $estado = $this->normalizeStatus($order->status);
        $puedeGestionar = in_array($estado, ['pending', 'submitted'], true);

        $actions = [];

        // Ver voucher
        if ($order->voucher_path && Storage::exists($order->voucher_path)) {
            $voucherUrl = Storage::url($order->voucher_path);
            $actions[] = Actions\Action::make('voucher')
                ->label('Ver Comprobante')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->url($voucherUrl, true)
                ->openUrlInNewTab();
        }

        // WhatsApp
        if ($order->customer_phone) {
            $num = preg_replace('/\D+/', '', (string) $order->customer_phone);
            if (!str_starts_with($num, '58')) { // cambia por tu país si quieres
                $num = '58' . $num;
            }
            $msg = "Hola, sobre tu orden {$order->code}";
            $whatsUrl = "https://wa.me/{$num}?text=" . urlencode($msg);

            $actions[] = Actions\Action::make('whatsapp')
                ->label('WhatsApp')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url($whatsUrl, true)
                ->openUrlInNewTab()
                ->extraAttributes(['class' => 'btn-whatsapp']); // CLASE ÚNICA
        }

        // Confirmar Pago
        if ($puedeGestionar) {
            $actions[] = Actions\Action::make('markPaid')
                ->label('Confirmar')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Confirmar Pago de Orden')
                ->modalDescription('¿Está seguro de marcar esta orden como pagada? Esta acción confirmará los números reservados.')
                ->modalSubmitActionLabel('Sí, Confirmar Pago')
                ->extraAttributes(['class' => 'btn-confirmar']) // CLASE ÚNICA
                ->action(function () use ($order) {
                    try {
                        app(MarkOrderPaid::class)->handle($order);
                        Notification::make()
                            ->title('✅ Pago Confirmado')
                            ->body("La orden #{$order->code} ha sido marcada como pagada exitosamente.")
                            ->success()
                            ->duration(5000)
                            ->send();
                        $this->refreshFormData(['status']);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error al Confirmar Pago')
                            ->body('No se pudo procesar el pago. Por favor, intente nuevamente.')
                            ->danger()
                            ->send();
                    }
                });

            // Cancelar orden
            $actions[] = Actions\Action::make('cancel')
                ->label('Cancelar')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Cancelar Orden')
                ->modalDescription('¿Está seguro de cancelar esta orden? Los números reservados quedarán disponibles nuevamente.')
                ->modalSubmitActionLabel('Sí, Cancelar Orden')
                ->extraAttributes(['class' => 'btn-cancelar']) // CLASE ÚNICA
                ->action(function () use ($order) {
                    try {
                        app(CancelOrder::class)->handle($order);
                        Notification::make()
                            ->title('Orden Cancelada')
                            ->body("La orden #{$order->code} ha sido cancelada. Los números están disponibles nuevamente.")
                            ->warning()
                            ->send();
                        $this->refreshFormData(['status']);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error al Cancelar')
                            ->body('No se pudo cancelar la orden. Por favor, intente nuevamente.')
                            ->danger()
                            ->send();
                    }
                });
        }

        return $actions;
    }
    // --------------------- HELPERS PRO -------------------------

    private function normalizeStatus($status): string
    {
        if ($status instanceof \BackedEnum) {
            return $status->value;
        }
        return (string) $status;
    }

    private function getStatusConfig(string $status): array
    {
        $configs = [
            'pending' => ['label' => 'Pendiente'],
            'submitted' => ['label' => 'Por Revisar'],
            'paid' => ['label' => 'Pagada'],
            'cancelled' => ['label' => 'Cancelada'],
            'expired' => ['label' => 'Expirada'],
        ];

        return $configs[$status] ?? ['label' => ucfirst($status)];
    }

    protected function getCorporateStyles(): string
    {
        return '
            /* ========================================
               DISEÑO CORPORATIVO PROFESIONAL
               ======================================== */
            
            /* Variables de color corporativo */
            :root {
                --corp-primary: #00367C;
                --corp-secondary: #00367C;
                --corp-accent: #00367C;
                --corp-success: #27ae60;
                --corp-warning: #f39c12;
                --corp-danger: #e74c3c;
                --corp-info: #00367C;
                --corp-light: #00367C;
                --corp-dark: #ffffff;
                --corp-border: #bdc3c7;
                --corp-bg: #f8f9fa;
                --corp-white: #ffffff;
            }

            /* Fondo general más profesional */
            .fi-page {
                background: var(--corp-bg) !important;
            }

            .fi-header {
    background: transparent !important;
}

/* Botones personalizados de acciones Filament */

.fi-btn.btn-whatsapp,
.fi-ac-action.btn-whatsapp {
    background: #128c7e  !important;
    color: #fff !important;
    border-radius: 8px !important;
    font-weight: bold !important;
    border: none !important;
}
.fi-btn.btn-whatsapp:hover,
.fi-ac-action.btn-whatsapp:hover {
    background: #25d366  !important;
}

.fi-btn.btn-confirmar,
.fi-ac-action.btn-confirmar {
    background: #1d4ed8 !important; /* Azul Confirmar */
    color: #fff !important;
    border-radius: 8px !important;
    font-weight: bold !important;
    border: none !important;
}
.fi-btn.btn-confirmar:hover,
.fi-ac-action.btn-confirmar:hover {
    background: #2563eb  !important;
}

.fi-btn.btn-cancelar,
.fi-ac-action.btn-cancelar {
    background: #c53030  !important; /* Rojo Cancelar */
    color: #fff !important;
    border-radius: 8px !important;
    font-weight: bold !important;
    border: none !important;
}
.fi-btn.btn-cancelar:hover,
.fi-ac-action.btn-cancelar:hover {
    background: #e53e3e  !important;
}


            /* Header Corporativo */
            .corporate-header {
                background: linear-gradient(135deg, var(--corp-white) 0%, #f8f9fa 100%);
                border: 1px solid var(--corp-border);
                border-radius: 10px;
                padding: 1.5rem 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .header-main {
                flex: 1;

            }

            .header-title {
                font-size: 1.75rem;
                font-weight: 600;
                color: var(--corp-light);
                margin: 0;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .title-icon {
                font-size: 1.5rem;
            }

            .order-number {
                color: var(--corp-success);
                font-weight: 700;
            }

            .header-meta {
                margin-top: 0.5rem;
                color: #6c757d;
                font-size: 0.9rem;
            }

            .meta-date {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
            }

            /* Badge de Estado Mejorado */
            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.625rem 1.25rem;
                border-radius: 25px;
                font-weight: 600;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.025em;
                transition: all 0.2s ease;
            }

            .status-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                display: inline-block;
                animation: pulse 2s infinite;
            }

            .status-pending {
                background: rgba(243, 156, 18, 0.1);
                color: var(--corp-warning);
                border: 1.5px solid var(--corp-warning);
            }

            .status-pending .status-dot {
                background: var(--corp-warning);
            }

            .status-submitted {
                background: rgba(22, 160, 133, 0.1);
                color: var(--corp-info);
                border: 1.5px solid var(--corp-info);
            }

            .status-submitted .status-dot {
                background: var(--corp-info);
            }

            .status-paid {
                background: rgba(39, 174, 96, 0.1);
                color: var(--corp-success);
                border: 1.5px solid var(--corp-success);
            }

            .status-paid .status-dot {
                background: var(--corp-success);
            }

            .status-cancelled,
            .status-expired {
                background: rgba(231, 76, 60, 0.1);
                color: var(--corp-danger);
                border: 1.5px solid var(--corp-danger);
            }

            .status-cancelled .status-dot,
            .status-expired .status-dot {
                background: var(--corp-danger);
            }

            @keyframes pulse {
                0% {
                    box-shadow: 0 0 0 0 currentColor;
                }
                70% {
                    box-shadow: 0 0 0 6px rgba(0, 0, 0, 0);
                }
                100% {
                    box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
                }
            }

            /* Secciones de Información Corporativas */
            .fi-section,
            .fi-infolist-section {
                background: var(--corp-white) !important;
                border: 1px solid #dee2e6 !important;
                border-radius: 8px !important;
                padding: 1.5rem !important;
                margin-bottom: 1.5rem !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.04) !important;
                transition: all 0.3s ease;
            }

            .fi-section:hover,
            .fi-infolist-section:hover {
                box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
            }

            /* Títulos de Sección */
            .fi-section-heading,
            .fi-infolist-section-heading {
                font-size: 1.125rem !important;
                font-weight: 600 !important;
                color: var(--corp-accent) !important;
                margin-bottom: 1rem !important;
                padding-bottom: 0.75rem !important;
                border-bottom: 2px solid var(--corp-accent) !important;
                display: flex !important;
                align-items: center !important;
                gap: 0.5rem !important;
            }

            /* Contenido de Secciones */
            .fi-infolist-component {
                background: transparent !important;
            }

            .fi-infolist-item {
                padding: 0.75rem 0 !important;
                border-bottom: 1px solid #f0f0f0 !important;
            }

            .fi-infolist-item:last-child {
                border-bottom: none !important;
            }

            .fi-infolist-item-label {
                color: #6c757d !important;
                font-weight: 500 !important;
                font-size: 0.875rem !important;
                text-transform: uppercase !important;
                letter-spacing: 0.025em !important;
            }

            .fi-infolist-item-value {
                color: var(--corp-dark) !important;
                font-weight: 600 !important;
                font-size: 1rem !important;
                margin-top: 0.25rem !important;
            }

            /* Tablas Corporativas */
            .fi-ta-table {
                background: var(--corp-white) !important;
                border: 1px solid #dee2e6 !important;
                border-radius: 8px !important;
                overflow: hidden !important;
            }

            .fi-ta-table thead {
                background: #f8f9fa !important;
                border-bottom: 2px solid var(--corp-accent) !important;
            }

            .fi-ta-table th {
                color: var(--corp-dark) !important;
                font-weight: 600 !important;
                text-transform: uppercase !important;
                font-size: 0.8rem !important;
                letter-spacing: 0.05em !important;
                padding: 1rem !important;
            }

            .fi-ta-table tbody tr {
                border-bottom: 1px solid #f0f0f0 !important;
                transition: background 0.2s ease;
            }

            .fi-ta-table tbody tr:hover {
                background: #f8f9fa !important;
            }

            .fi-ta-table td {
                padding: 1rem !important;
                color: var(--corp-secondary) !important;
            }

            /* Botones Corporativos */
            .fi-btn {
                font-weight: 600 !important;
                border-radius: 6px !important;
                padding: 0.625rem 1.25rem !important;
                transition: all 0.2s ease !important;
                text-transform: uppercase !important;
                font-size: 0.8rem !important;
                letter-spacing: 0.025em !important;
            }

            .fi-btn:hover {
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
            }

            .fi-btn-primary {
                background: var(--corp-accent) !important;
                border: 1px solid var(--corp-accent) !important;
            }

            .fi-btn-primary:hover {
                background: #2980b9 !important;
                border-color: #2980b9 !important;
            }

            .fi-btn-success {
                background: var(--corp-success) !important;
                border: 1px solid var(--corp-success) !important;
            }

            .fi-btn-danger {
                background: var(--corp-danger) !important;
                border: 1px solid var(--corp-danger) !important;
            }


            /* Action Buttons Group */
            .fi-ac-action {
                background: var(--corp-accent) !important;
                border: 1px solid var(--corp-border) !important;
                color: var(--corp-dark) !important;
                transition: all 0.2s ease !important;
            }

            .fi-ac-action:hover {
                background: var(--corp-accent) !important;
                border-color: var(--corp-accent) !important;
                color: white !important;
            }

            /* Cards de Información */
            .fi-card {
                background: var(--corp-white) !important;
                border: 1px solid #dee2e6 !important;
                border-radius: 8px !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.04) !important;
                padding: 1.5rem !important;
                margin-bottom: 1.5rem !important;
            }

            .fi-card-heading {
                color: var(--corp-dark) !important;
                font-weight: 600 !important;
                font-size: 1.125rem !important;
                margin-bottom: 1rem !important;
                padding-bottom: 0.75rem !important;
                border-bottom: 2px solid var(--corp-accent) !important;
            }

            /* Grid de Información */
            .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
                margin-top: 1rem;
            }

            .info-item {
                background: #f8f9fa;
                padding: 1rem;
                border-radius: 6px;
                border-left: 3px solid var(--corp-accent);
            }

            .info-label {
                color: #6c757d;
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 0.25rem;
            }

            .info-value {
                color: var(--corp-dark);
                font-size: 1.125rem;
                font-weight: 600;
            }

            /* Modales Corporativos */
            .fi-modal-window {
                background: var(--corp-white) !important;
                border-radius: 8px !important;
                box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
            }

            .fi-modal-heading {
                color: var(--corp-primary) !important;
                font-weight: 600 !important;
            }

            /* Notificaciones */
            .fi-notification {
                border-radius: 6px !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .corporate-header {
                    flex-direction: column;
                    gap: 1rem;
                    text-align: center;
                }

                .header-status {
                    width: 100%;
                    display: flex;
                    justify-content: center;
                }

                .info-grid {
                    grid-template-columns: 1fr;
                }
            }
        ';
    }
}
