<?php

namespace App\Filament\Tenant\Resources;

use App\Actions\Orders\CancelOrder;
use App\Actions\Orders\MarkOrderPaid;
use App\Filament\Tenant\Resources\OrderResource\Pages;
use App\Filament\Tenant\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Infolists\Components\Grid as InfoGrid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $tenantOwnershipRelationshipName = 'tenant';
    protected static ?string $tenantRelationshipName = 'orders';

    protected static ?string $navigationIcon  = 'heroicon-o-receipt-percent';
    protected static ?string $navigationLabel = 'Órdenes';
    protected static ?string $pluralLabel     = 'Órdenes';
    protected static ?string $modelLabel      = 'Orden';
    protected static ?int    $navigationSort  = 6;

    public static function table(Table $table): Table
    {
        return $table
            // Builder seguro y performante
            ->query(fn () => \App\Models\Order::query()
                ->with([
                    'rifa:id,titulo',
                    'paymentAccount:id,etiqueta,tipo,requiere_voucher',
                    'items:id,order_id,numero', // para tooltip y preview
                ])
                ->withCount('items') // items_count
                ->latest('id')
            )
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('rifa.titulo')
                    ->label('Rifa')
                    ->searchable()
                    ->wrap()
                    ->grow(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('WhatsApp')
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => $state ? preg_replace('/\D+/', '', (string) $state) : '—')
                    ->url(
                        fn ($record) => $record->customer_phone
                            ? 'https://wa.me/' . preg_replace('/\D+/', '', $record->customer_phone)
                            : null,
                        true
                    )
                    ->openUrlInNewTab(),

                // Preview de números + tooltip con la lista completa
                Tables\Columns\TextColumn::make('items_count')
                    ->label('Números')
                    ->formatStateUsing(function ($state, $record) {
                        $nums = collect($record->items)->pluck('numero')->sort()->values();
                        if ($nums->isEmpty()) return '—';
                        $preview = $nums->take(3)->implode(', ');
                        $rest = $nums->count() - 3;
                        return $rest > 0 ? "{$preview} +{$rest}" : $preview;
                    })
                    ->tooltip(fn ($record) => $record->items?->pluck('numero')->sort()->implode(', '))
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->prefix('$')
                    ->numeric(2)
                    ->alignRight(),

                // Método: mostramos etiqueta; el "tipo" lo verás en la vista
                Tables\Columns\TextColumn::make('paymentAccount.etiqueta')
                    ->label('Método de pago')
                    ->limit(24)
                    ->tooltip(fn ($record) => $record->paymentAccount?->etiqueta)
                    ->toggleable(),

                // Voucher con icono (clic abre)
                Tables\Columns\IconColumn::make('voucher_path')
                    ->label('Voucher')
                    ->tooltip(fn ($record) => $record->voucher_path ? 'Abrir voucher' : 'Sin voucher')
                    ->boolean()
                    ->state(fn ($record) => (bool) $record->voucher_path)
                    ->action(function ($record) {
                        if ($record->voucher_path) {
                            return redirect()->away(Storage::url($record->voucher_path));
                        }
                    })
                    ->alignCenter(),

                // Estado con badges en español
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'submitted',
                        'success' => 'paid',
                        'gray'    => 'cancelled',
                        'danger'  => 'expired',
                    ])
                    ->formatStateUsing(function ($state) {
                        $v = $state instanceof \BackedEnum ? $state->value : $state;
                        return match ($v) {
                            'pending'   => 'Pendiente',
                            'submitted' => 'Por revisar',
                            'paid'      => 'Pagada',
                            'cancelled' => 'Cancelada',
                            'expired'   => 'Expirada',
                            default     => ucfirst((string) $v),
                        };
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->since(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->attribute('status')
                    ->options([
                        'pending'   => 'Pendiente',
                        'submitted' => 'Por revisar',
                        'paid'      => 'Pagada',
                        'cancelled' => 'Cancelada',
                        'expired'   => 'Expirada',
                    ])
                    ->native(false),

                SelectFilter::make('rifa_id')
                    ->label('Rifa')
                    ->relationship('rifa', 'titulo')
                    ->native(false),

                SelectFilter::make('payment_account_id')
                    ->label('Método de pago')
                    ->relationship('paymentAccount', 'etiqueta')
                    ->native(false),

                Filter::make('rango_fechas')
                    ->label('Rango de fechas')
                    ->form([
                        Forms\Components\DatePicker::make('desde')->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(function (Builder $q, array $data) {
                        return $q
                            ->when($data['desde'] ?? null, fn ($qq) => $qq->whereDate('created_at', '>=', $data['desde']))
                            ->when($data['hasta'] ?? null, fn ($qq) => $qq->whereDate('created_at', '<=', $data['hasta']));
                    }),

                Tables\Filters\TernaryFilter::make('con_voucher')
                    ->label('Con voucher')
                    ->trueLabel('Con voucher')
                    ->falseLabel('Sin voucher')
                    ->placeholder('Todos')
                    ->queries(
                        true: fn ($q) => $q->whereNotNull('voucher_path'),
                        false: fn ($q) => $q->whereNull('voucher_path'),
                        blank: fn ($q) => $q
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->label('Ver'),

                    Tables\Actions\Action::make('voucher')
                        ->label('Abrir voucher')
                        ->icon('heroicon-o-photo')
                        ->visible(fn (Order $record) => (bool) $record->voucher_path)
                        ->url(fn (Order $record) => $record->voucher_path ? Storage::url($record->voucher_path) : null, true)
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('marcar_pagada')
                        ->label('Marcar pagada')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (Order $record) =>
                            in_array(($record->status instanceof \BackedEnum ? $record->status->value : $record->status), ['pending','submitted'], true)
                        )
                        ->action(function (Order $record) {
                            app(MarkOrderPaid::class)->handle($record);

                            Notification::make()
                                ->title('Orden marcada como pagada')
                                ->body("Se confirmaron los números y la orden {$record->code} pasó a “Pagada”.")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('cancelar')
                        ->label('Cancelar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Order $record) =>
                            in_array(($record->status instanceof \BackedEnum ? $record->status->value : $record->status), ['pending','submitted'], true)
                        )
                        ->action(function (Order $record) {
                            app(CancelOrder::class)->handle($record);

                            Notification::make()
                                ->title('Orden cancelada')
                                ->body("La orden {$record->code} fue cancelada y los números quedaron disponibles.")
                                ->success()
                                ->send();
                        }),
                ])->label('Acciones'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('marcar_pagadas_lote')
                    ->label('Marcar pagadas (lote)')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $svc = app(MarkOrderPaid::class);
                        $ok = 0; $fail = 0;

                        foreach ($records as $order) {
                            try {
                                $status = $order->status instanceof \BackedEnum ? $order->status->value : $order->status;
                                if (in_array($status, ['pending','submitted'], true)) {
                                    $svc->handle($order);
                                    $ok++;
                                }
                            } catch (\Throwable $e) {
                                $fail++;
                            }
                        }

                        Notification::make()
                            ->title('Proceso completado')
                            ->body("Órdenes OK: {$ok} | Fallidas: {$fail}")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\BulkAction::make('cancelar_lote')
                    ->label('Cancelar (lote)')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $svc = app(CancelOrder::class);
                        $ok = 0; $fail = 0;

                        foreach ($records as $order) {
                            try {
                                $status = $order->status instanceof \BackedEnum ? $order->status->value : $order->status;
                                if (in_array($status, ['pending','submitted'], true)) {
                                    $svc->handle($order);
                                    $ok++;
                                }
                            } catch (\Throwable $e) {
                                $fail++;
                            }
                        }

                        Notification::make()
                            ->title('Proceso completado')
                            ->body("Órdenes canceladas: {$ok} | Fallidas: {$fail}")
                            ->success()
                            ->send();
                    }),
            ]);
    }

    /** Vista “Ver” (infolist) con bloques profesionales */
    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
{
    return $infolist->schema([
        \Filament\Infolists\Components\Section::make('Datos de la orden')->schema([
            \Filament\Infolists\Components\Grid::make(12)->schema([
                \Filament\Infolists\Components\TextEntry::make('code')
                    ->label('Código')
                    ->copyable()
                    ->columnSpan(3),

                \Filament\Infolists\Components\TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'pending'   => 'warning',
                        'submitted' => 'info',
                        'paid'      => 'success',
                        'cancelled' => 'gray',
                        'expired'   => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(function ($state) {
                        $v = $state instanceof \BackedEnum ? $state->value : $state;
                        return match ($v) {
                            'pending'   => 'Pendiente',
                            'submitted' => 'Por revisar',
                            'paid'      => 'Pagada',
                            'cancelled' => 'Cancelada',
                            'expired'   => 'Expirada',
                            default     => ucfirst((string) $v),
                        };
                    })
                    ->columnSpan(3),

                \Filament\Infolists\Components\TextEntry::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => '$' . number_format((float) $state, 2))
                    ->extraAttributes(['class' => 'text-2xl font-semibold'])
                    ->columnSpan(3),

                \Filament\Infolists\Components\TextEntry::make('created_at')
                    ->label('Creada')
                    ->dateTime()
                    ->columnSpan(3),
            ]),

            \Filament\Infolists\Components\TextEntry::make('notes')
                ->label('Notas')
                ->placeholder('—')
                ->columnSpanFull(),
        ])->columns(12),

        \Filament\Infolists\Components\Section::make('Cliente')->schema([
            \Filament\Infolists\Components\TextEntry::make('customer_name')->label('Nombre')->placeholder('—'),
            \Filament\Infolists\Components\TextEntry::make('customer_phone')
                ->label('WhatsApp')
                ->placeholder('—')
                ->url(fn ($record) => $record->customer_phone
                    ? 'https://wa.me/' . preg_replace('/\D+/', '', $record->customer_phone)
                    : null, true)
                ->openUrlInNewTab(),
            \Filament\Infolists\Components\TextEntry::make('customer_email')->label('Email')->placeholder('—'),
        ])->columns(3),

        \Filament\Infolists\Components\Section::make('Pago')->schema([
            \Filament\Infolists\Components\Grid::make(12)->schema([
                \Filament\Infolists\Components\TextEntry::make('paymentAccount.etiqueta')
                    ->label('Método de pago')->placeholder('—')->columnSpan(4),

                \Filament\Infolists\Components\TextEntry::make('paymentAccount.tipo')
                    ->label('Tipo')->placeholder('—')->badge()
                    ->color(fn ($state) => match ((string) $state) {
                        'transferencia' => 'info',
                        'efectivo'      => 'success',
                        'zelle', 'paypal', 'binance' => 'warning',
                        default          => 'gray',
                    })
                    ->columnSpan(4),

                \Filament\Infolists\Components\TextEntry::make('paymentAccount.requiere_voucher')
                    ->label('Requiere voucher')
                    ->formatStateUsing(fn ($state) => $state ? 'Sí' : 'No')
                    ->badge()
                    ->color(fn ($state) => $state ? 'warning' : 'gray')
                    ->columnSpan(4),
            ]),

            // Miniatura si es imagen
            \Filament\Infolists\Components\ImageEntry::make('voucher_path')
                ->label('Voucher')
                ->visible(fn ($record) =>
                    $record->voucher_path && preg_match('/\.(jpe?g|png|webp)$/i', $record->voucher_path)
                )
                ->url(fn ($record) => \Illuminate\Support\Facades\Storage::url($record->voucher_path), true)
                ->columnSpanFull()
                ->extraImgAttributes(['class' => 'rounded-xl max-h-72']),

            // Enlace si es PDF u otro
            \Filament\Infolists\Components\TextEntry::make('voucher_link')
                ->label('Voucher')
                ->state(fn ($record) => $record->voucher_path ? 'Abrir comprobante' : '—')
                ->visible(fn ($record) =>
                    ! $record->voucher_path
                    || ! preg_match('/\.(jpe?g|png|webp)$/i', $record->voucher_path)
                )
                ->url(fn ($record) => $record->voucher_path ? \Illuminate\Support\Facades\Storage::url($record->voucher_path) : null, true)
                ->icon(fn ($record) => $record->voucher_path ? 'heroicon-o-arrow-top-right-on-square' : null)
                ->columnSpanFull(),
        ])->columns(12),

        \Filament\Infolists\Components\Section::make('Rifa')->schema([
            \Filament\Infolists\Components\TextEntry::make('rifa.titulo')->label('Título'),
            \Filament\Infolists\Components\TextEntry::make('items')
                ->label('Números')
                ->formatStateUsing(fn ($state, $record) => $record->items?->pluck('numero')->sort()->implode(', ') ?: '—')
                ->columnSpanFull(),
        ])->columns(2),
    ]);
}


    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view'  => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
