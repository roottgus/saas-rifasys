<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\PaymentAccountResource\Pages;
use App\Models\PaymentAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;

class PaymentAccountResource extends Resource
{
    protected static ?string $model = PaymentAccount::class;

    protected static ?string $tenantOwnershipRelationshipName = 'tenant';
    protected static ?string $tenantRelationshipName = 'paymentAccounts';

    protected static ?string $navigationIcon  = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Pagos & Cuentas';
    protected static ?string $pluralLabel     = 'Pagos & Cuentas';
    protected static ?int    $navigationSort  = 12;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Configuración General')
                ->description('Configura el tipo de pago y su identificación')
                ->schema([
                    Forms\Components\Select::make('tipo')
                        ->label('Tipo de pago')
                        ->options([
                            'transferencia' => 'Transferencia bancaria',
                            'zelle'         => 'Zelle',
                            'usdt'          => 'USDT',
                            'paypal'        => 'PayPal',
                            'stripe'        => 'Stripe (automático)',
                        ])
                        ->required()
                        ->native(false)
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            // Auto-configurar monedas según el tipo
                            if (in_array($state, ['zelle', 'paypal', 'usdt', 'stripe'])) {
                                $set('usd_enabled', true);
                                $set('bs_enabled', false);
                            } elseif ($state === 'transferencia') {
                                // Las transferencias pueden ser en cualquier moneda
                                $set('bs_enabled', true);
                            }
                        }),

                    Forms\Components\TextInput::make('etiqueta')
                        ->label('Etiqueta pública')
                        ->placeholder('Ej: Banco Provincial, Zelle Principal')
                        ->required()
                        ->maxLength(120),

                    Forms\Components\TextInput::make('orden')
                        ->label('Orden de visualización')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Menor número = Mayor prioridad'),

                    Forms\Components\Select::make('status')
                        ->label('Estado')
                        ->options([
                            'activo'   => 'Activo',
                            'inactivo' => 'Inactivo',
                        ])
                        ->default('activo')
                        ->required(),

                    FileUpload::make('logo')
                        ->label('Logo / Ícono')
                        ->image()
                        ->imagePreviewHeight('64')
                        ->directory('logos')
                        ->maxSize(512)
                        ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/jpeg'])
                        ->hint('Recomendado: PNG o SVG cuadrado, 64x64px mínimo')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            // Configuración de Monedas
            Forms\Components\Section::make('Configuración de Monedas')
                ->description('Define qué monedas acepta este método de pago')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\Toggle::make('usd_enabled')
                                ->label('Acepta USD ($)')
                                ->helperText('Pagos en dólares estadounidenses')
                                ->reactive()
                                ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                    // Si se desactiva USD y BS también está desactivado, activar BS
                                    if (!$state && !$get('bs_enabled')) {
                                        Notification::make()
                                            ->warning()
                                            ->title('Atención')
                                            ->body('Debe aceptar al menos una moneda. Se activará Bolívares.')
                                            ->send();
                                        $set('bs_enabled', true);
                                    }
                                }),

                            Forms\Components\Toggle::make('bs_enabled')
                                ->label('Acepta Bolívares (Bs.)')
                                ->helperText('Pagos en bolívares')
                                ->live()
                                ->reactive()
                                ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                    // Si se desactiva BS y USD también está desactivado, activar USD
                                    if (!$state && !$get('usd_enabled')) {
                                        Notification::make()
                                            ->warning()
                                            ->title('Atención')
                                            ->body('Debe aceptar al menos una moneda. Se activará USD.')
                                            ->send();
                                        $set('usd_enabled', true);
                                    }
                                    // Si se desactiva BS, limpiar la tasa
                                    if (!$state) {
                                        $set('tasa_bs', null);
                                    }
                                }),

                            Forms\Components\TextInput::make('tasa_bs')
                                ->label('Tasa Bs./USD')
                                ->placeholder('36.50')
                                ->helperText('Ej: 36.50 = 1 USD vale 36.50 Bs.')
                                ->numeric()
                                ->minValue(0.01)
                                ->maxValue(999999.99)
                                ->step(0.01)
                                ->prefix('Bs.')
                                ->suffix('/ USD')
                                ->visible(fn (Get $get) => (bool) $get('bs_enabled') === true)
                                ->required(fn (Get $get) => (bool) $get('bs_enabled') === true)
                                ->validationMessages([
                                    'required' => 'La tasa es obligatoria cuando se aceptan Bolívares',
                                    'numeric' => 'La tasa debe ser un número',
                                    'min' => 'La tasa debe ser mayor a 0',
                                ]),
                        ]),

                    // Indicador visual de monedas configuradas
                    Forms\Components\Placeholder::make('currency_summary')
                        ->label('Resumen de configuración')
                        ->content(function (Get $get) {
                            $currencies = [];
                            if ($get('usd_enabled')) {
                                $currencies[] = '✅ USD ($)';
                            }
                            if ($get('bs_enabled')) {
                                $tasa = $get('tasa_bs');
                                $tasaText = $tasa ? " - Tasa: Bs. {$tasa}" : " - ⚠️ Falta configurar tasa";
                                $currencies[] = '✅ Bolívares (Bs.)' . $tasaText;
                            }
                            
                            if (empty($currencies)) {
                                return '⚠️ No hay monedas configuradas';
                            }
                            
                            return implode(' | ', $currencies);
                        }),
                ])
                ->collapsible(),

            // Datos bancarios (Transferencia)
            Forms\Components\Section::make('Datos Bancarios')
                ->description('Información de la cuenta bancaria para transferencias')
                ->schema([
                    Forms\Components\TextInput::make('banco')
                        ->label('Nombre del banco')
                        ->placeholder('Banco Provincial')
                        ->maxLength(120)
                        ->required(fn (Get $get) => $get('tipo') === 'transferencia'),
                        
                    Forms\Components\TextInput::make('titular')
                        ->label('Titular de la cuenta')
                        ->placeholder('Juan Pérez')
                        ->maxLength(120)
                        ->required(fn (Get $get) => $get('tipo') === 'transferencia'),
                        
                    Forms\Components\TextInput::make('documento')
                        ->label('CI/RIF del titular')
                        ->placeholder('V-12345678')
                        ->maxLength(50),
                        
                    Forms\Components\TextInput::make('numero')
                        ->label('Número de cuenta')
                        ->placeholder('0102-1234-56-7890123456')
                        ->maxLength(120)
                        ->required(fn (Get $get) => $get('tipo') === 'transferencia'),
                        
                    Forms\Components\TextInput::make('iban')
                        ->label('Código IBAN (opcional)')
                        ->placeholder('VE12 3456 7890 1234 5678 9012')
                        ->maxLength(120),
                ])
                ->visible(fn (Get $get) => $get('tipo') === 'transferencia')
                ->columns(2)
                ->collapsible(),

            // Zelle / PayPal
            Forms\Components\Section::make('Configuración de Email')
                ->description('Email asociado a la cuenta de Zelle o PayPal')
                ->schema([
                    Forms\Components\TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email()
                        ->placeholder('usuario@ejemplo.com')
                        ->required(fn (Get $get) => in_array($get('tipo'), ['zelle','paypal'])),
                ])
                ->visible(fn (Get $get) => in_array($get('tipo'), ['zelle','paypal']))
                ->collapsible(),

            // USDT
            Forms\Components\Section::make('Configuración USDT')
                ->description('Dirección de wallet para recibir USDT')
                ->schema([
                    Forms\Components\TextInput::make('wallet')
                        ->label('Dirección de wallet')
                        ->placeholder('TRC20 o ERC20 address')
                        ->maxLength(190)
                        ->required(fn (Get $get) => $get('tipo') === 'usdt'),
                        
                    Forms\Components\Select::make('red')
                        ->label('Red blockchain')
                        ->options([
                            'TRC20' => 'TRC20 (Tron)',
                            'ERC20' => 'ERC20 (Ethereum)',
                            'BEP20' => 'BEP20 (BSC)',
                        ])
                        ->placeholder('Selecciona la red'),
                ])
                ->visible(fn (Get $get) => $get('tipo') === 'usdt')
                ->columns(2)
                ->collapsible(),

            // Configuración adicional
            Forms\Components\Section::make('Configuración Adicional')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Instrucciones para el cliente')
                        ->placeholder('Instrucciones adicionales que verá el cliente al pagar...')
                        ->rows(3)
                        ->maxLength(500),

                    Forms\Components\Toggle::make('requiere_voucher')
                        ->label('Requiere comprobante de pago')
                        ->helperText('El cliente deberá subir una imagen del comprobante')
                        ->default(true),

                    Forms\Components\Toggle::make('activo')
                        ->label('Método activo')
                        ->helperText('Desactiva temporalmente sin eliminar')
                        ->default(true),
                ])
                ->columns(3)
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('orden')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),
                    
                Tables\Columns\ImageColumn::make('logo')
                    ->label('')
                    ->circular()
                    ->width('40px')
                    ->height('40px'),
                    
                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->colors([
                        'primary' => 'transferencia',
                        'success' => 'zelle',
                        'warning' => 'usdt',
                        'info' => 'paypal',
                        'danger' => 'stripe',
                    ]),
                    
                Tables\Columns\TextColumn::make('etiqueta')
                    ->label('Etiqueta')
                    ->searchable()
                    ->wrap()
                    ->weight('bold'),

                // Monedas aceptadas con colores
                Tables\Columns\TextColumn::make('monedas_display')
                    ->label('Monedas')
                    ->getStateUsing(function ($record) {
                        $monedas = [];
                        if ($record->usd_enabled) {
                            $monedas[] = '<span class="text-green-600 font-bold">USD</span>';
                        }
                        if ($record->bs_enabled) {
                            $monedas[] = '<span class="text-blue-600 font-bold">Bs</span>';
                        }
                        return implode(' | ', $monedas);
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('tasa_bs')
                    ->label('Tasa Bs.')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->bs_enabled || !$state) {
                            return '—';
                        }
                        return 'Bs. ' . number_format((float) $state, 2, ',', '.');
                    })
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                Tables\Columns\ToggleColumn::make('activo')
                    ->label('Activo')
                    ->onColor('success')
                    ->offColor('danger'),
                    
                Tables\Columns\IconColumn::make('requiere_voucher')
                    ->boolean()
                    ->label('Voucher')
                    ->trueIcon('heroicon-o-camera')
                    ->falseIcon('heroicon-o-x-mark'),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'success' => 'activo',
                        'danger' => 'inactivo',
                    ]),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->since()
                    ->label('Actualizado')
                    ->color('gray'),
            ])
            ->defaultSort('orden', 'asc')
            ->reorderable('orden')
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo de pago')
                    ->options([
                        'transferencia' => 'Transferencia',
                        'zelle' => 'Zelle',
                        'usdt' => 'USDT',
                        'paypal' => 'PayPal',
                        'stripe' => 'Stripe',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Estado'),
                    
                Tables\Filters\TernaryFilter::make('bs_enabled')
                    ->label('Acepta Bolívares'),
            ])
            ->emptyStateHeading('Sin métodos de pago')
            ->emptyStateDescription('Agrega tus cuentas y métodos de pago para comenzar a recibir pagos.')
            ->emptyStateIcon('heroicon-o-credit-card')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Agregar primer método')
                    ->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->modalHeading('Editar método de pago'),
                    
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicar')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record) {
                        $newRecord = $record->replicate();
                        $newRecord->etiqueta = $record->etiqueta . ' (Copia)';
                        $newRecord->save();
                        
                        Notification::make()
                            ->success()
                            ->title('Método duplicado')
                            ->body('Se ha creado una copia del método de pago.')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPaymentAccounts::route('/'),
            'create' => Pages\CreatePaymentAccount::route('/create'),
            'edit'   => Pages\EditPaymentAccount::route('/{record}/edit'),
        ];
    }
}