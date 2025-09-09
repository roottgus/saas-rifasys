<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos generales')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre del cliente / marca')
                        ->required()
                        ->maxLength(64)
                        ->autofocus(),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug / Prefijo')
                        ->helperText('Identificador único para la URL (ej: rifasmonito). Solo minúsculas, sin espacios.')
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('domain')
                        ->label('Dominio personalizado')
                        ->placeholder('ej: rifasmonito.com')
                        ->helperText('Dominio propio para la marca. Déjalo vacío si no usará dominio propio.')
                        ->maxLength(128)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Ubicación y moneda')
                ->schema([
                    Forms\Components\Select::make('pais')
                        ->label('País')
                        ->options([
                            'VE' => '🇻🇪 Venezuela',
                            'CO' => '🇨🇴 Colombia',
                            'EC' => '🇪🇨 Ecuador',
                            'PA' => '🇵🇦 Panamá',
                            'CL' => '🇨🇱 Chile',
                            'MX' => '🇲🇽 México',
                            'US' => '🇺🇸 USA',
                        ])
                        ->required()
                        ->searchable(),

                    Forms\Components\Select::make('moneda_principal')
                        ->label('Moneda principal')
                        ->options([
                            'USD' => 'Dólares (USD)',
                            'VES' => 'Bolívares (VES)',
                            'COP' => 'Pesos Colombianos (COP)',
                            'EUR' => 'Euros (EUR)',
                        ])
                        ->required()
                        ->searchable()
                        ->reactive(),

                    Forms\Components\TextInput::make('tasa_bs')
                        ->label('Tasa Bs (1 USD en Bs)')
                        ->numeric()
                        ->step('0.01')
                        ->visible(fn ($get) => $get('moneda_principal') === 'VES')
                        ->helperText('Solo para rifas en VES. Ej: 36.00')
                        ->placeholder('Ej: 36.00'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Contacto y notificaciones')
                ->schema([
                    Forms\Components\TextInput::make('notify_email')
                        ->label('Email para notificaciones')
                        ->email()
                        ->maxLength(191)
                        ->placeholder('soporte@tudominio.com')
                        ->helperText('A este correo se enviarán las notificaciones internas (reservas, pagos enviados, etc.). Si se deja vacío, se usará el correo por defecto del sistema.'),
                ])
                ->columns(1),

            Forms\Components\Section::make('Branding')
                ->schema([
                    Forms\Components\Textarea::make('branding_json')
                        ->label('Ajustes de branding (JSON)')
                        ->rows(3)
                        ->placeholder('{"mode":"light","primary":"#2563EB"}')
                        ->helperText('No edites manualmente a menos que sepas JSON.')
                        ->rule('json'),
                ])
                ->collapsible()
                ->collapsed(),

            Forms\Components\Section::make('Estado')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Estado')
                        ->options([
                            'active'    => 'Activo',
                            'paused'    => 'Pausado',
                            'inactive'  => 'Inactivo',
                            'banned'    => 'Suspendido/Bloqueado',
                        ])
                        ->default('active')
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Cliente / Marca')
                    ->sortable()
                    ->searchable()
                    ->description(fn($record) => $record->domain ?: null)
                    ->wrap(),

                Tables\Columns\TextColumn::make('pais')
                    ->label('País')
                    ->sortable()
                    ->color('gray'),

                Tables\Columns\BadgeColumn::make('moneda_principal')
                    ->label('Moneda')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'USD' => 'USD',
                        'VES' => 'Bs',
                        'COP' => 'COP',
                        'EUR' => 'EUR',
                        default => $state,
                    })
                    ->colors([
                        'USD' => 'primary',
                        'VES' => 'warning',
                        'COP' => 'success',
                        'EUR' => 'info',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('tasa_bs')
                    ->label('Tasa Bs')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->moneda_principal === 'VES' && $state
                            ? number_format($state, 2)
                            : '-'
                    )
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('notify_email')
                    ->label('Notif. a')
                    ->icon('heroicon-m-envelope')
                    ->placeholder('—')
                    ->copyable()
                    ->copyMessage('Email copiado')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->color('gray')
                    ->copyable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'active' => 'Activo',
                        'paused' => 'Pausado',
                        'inactive' => 'Inactivo',
                        'banned' => 'Suspendido',
                        default => $state,
                    })
                    ->colors([
                        'active'   => 'success',
                        'paused'   => 'warning',
                        'inactive' => 'gray',
                        'banned'   => 'danger',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('moneda_principal')
                    ->label('Moneda')
                    ->options([
                        'USD' => 'Dólares',
                        'VES' => 'Bolívares',
                        'COP' => 'Pesos Colombianos',
                        'EUR' => 'Euros',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active'   => 'Activo',
                        'paused'   => 'Pausado',
                        'inactive' => 'Inactivo',
                        'banned'   => 'Suspendido',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit'   => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
