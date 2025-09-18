<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Models\Contract;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\HtmlString;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Contratos de Servicio';
    protected static ?string $navigationGroup = 'Gestión Legal';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'contract_number';
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() > 0 ? 'warning' : 'success';
    }

    // Permitir acceso a superadmin y super_admin
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('superadmin') || $user->hasRole('super_admin'));
    }

    public static function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            Tabs::make('Información del Contrato')
                ->tabs([
                    Tabs\Tab::make('Datos del Cliente')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Section::make('Información Personal')
                                ->description('Datos principales del cliente')
                                ->icon('heroicon-o-identification')
                                ->collapsible()
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('client_name')
                                                ->label('Nombre completo del cliente')
                                                ->prefixIcon('heroicon-o-user')
                                                ->placeholder('Ingrese nombre completo')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('client_id_number')
                                                ->label('Número de identificación')
                                                ->prefixIcon('heroicon-o-identification')
                                                ->placeholder('Cédula o pasaporte')
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->maxLength(50),
                                        ]),
                                ]),
                            Section::make('Información de Contacto')
                                ->description('Medios de comunicación del cliente')
                                ->icon('heroicon-o-phone')
                                ->collapsible()
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('client_email')
                                                ->label('Correo electrónico')
                                                ->prefixIcon('heroicon-o-envelope')
                                                ->placeholder('ejemplo@dominio.com')
                                                ->email()
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('client_phone')
                                                ->label('Teléfono')
                                                ->prefixIcon('heroicon-o-phone')
                                                ->placeholder('+506 0000-0000')
                                                ->tel()
                                                ->required()
                                                ->maxLength(20),
                                        ]),
                                    Forms\Components\TextInput::make('client_address')
                                        ->label('Dirección completa')
                                        ->prefixIcon('heroicon-o-map-pin')
                                        ->placeholder('Dirección física del cliente')
                                        ->disabled()
                                        ->hiddenOn('create')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Tabs\Tab::make('Detalles del Servicio')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Información del Contrato')
                                ->description('Datos específicos del servicio contratado')
                                ->icon('heroicon-o-clipboard-document-list')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('contract_number')
                                                ->label('Número de Contrato')
                                                ->prefixIcon('heroicon-o-hashtag')
                                                ->disabled()
                                                ->dehydrated()
                                                ->hiddenOn('create'),
                                            Forms\Components\TextInput::make('raffle_name')
                                                ->label('Nombre de la rifa')
                                                ->prefixIcon('heroicon-o-ticket')
                                                ->disabled()
                                                ->hiddenOn('create'),
                                        ]),
                                    Forms\Components\Select::make('tenant_id')
                                        ->relationship('tenant', 'name')
                                        ->label('Cliente/Empresa asociada')
                                        ->prefixIcon('heroicon-o-building-office')
                                        ->searchable()
                                        ->preload()
                                        ->hiddenOn('create')
                                        ->placeholder('Seleccione el tenant'),
                                    Forms\Components\Textarea::make('disclaimer_accepted_text')
                                        ->label('Términos y condiciones aceptados')
                                        ->rows(4)
                                        ->disabled()
                                        ->hiddenOn('create')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Tabs\Tab::make('Documentación')
                        ->icon('heroicon-o-paper-clip')
                        ->hiddenOn('create')
                        ->schema([
                            Section::make('Documentos Adjuntos')
                                ->description('Archivos y documentación del contrato')
                                ->icon('heroicon-o-folder-open')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Forms\Components\FileUpload::make('cedula_file')
                                                ->label('Foto de la cédula')
                                                ->helperText('Documento de identidad del cliente')
                                                ->directory('contracts/cedulas')
                                                ->image()
                                                ->imageEditor()
                                                ->imageResizeMode('cover')
                                                ->imageCropAspectRatio('16:9')
                                                ->imageResizeTargetWidth('1920')
                                                ->imageResizeTargetHeight('1080')
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                                                ->maxSize(5120)
                                                ->disabled()
                                                ->downloadable()
                                                ->openable()
                                                ->previewable(),
                                            Forms\Components\FileUpload::make('conalot_permit_file')
                                                ->label('Permiso CONALOT')
                                                ->helperText('Documento oficial de autorización')
                                                ->directory('contracts/permisos')
                                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                                                ->maxSize(10240)
                                                ->disabled()
                                                ->downloadable()
                                                ->openable()
                                                ->previewable(),
                                        ]),
                                ]),
                        ]),
                    Tabs\Tab::make('Firma Digital')
                        ->icon('heroicon-o-pencil-square')
                        ->visible(fn ($livewire) =>
                            $livewire instanceof \Filament\Resources\Pages\ViewRecord ||
                            $livewire instanceof \Filament\Resources\Pages\EditRecord
                        )
                        ->schema([
                            Section::make('Enlace de Firma Electrónica')
                                ->description('Comparta este enlace con el cliente para que firme el contrato digitalmente')
                                ->icon('heroicon-o-link')
                                ->schema([
                                    // Enlace copiable
                                    Forms\Components\Placeholder::make('signature_link')
                                        ->label('Enlace único de firma')
                                        ->content(function ($record) {
                                            if (! $record || ! $record->uuid) {
                                                return 'No disponible';
                                            }
                                            $link = url('/contrato/firma/' . $record->uuid);
                                            return new HtmlString(
                                                '<div class="flex items-center gap-2">' .
                                                    '<input type="text" readonly value="' . $link . '" class="w-full bg-gray-50 text-sm font-mono px-2 py-1 rounded border border-gray-300" onclick="this.select()">' .
                                                    '<button type="button" onclick="navigator.clipboard.writeText(\'' . $link . '\');this.innerText = \'Copiado\'; setTimeout(()=>this.innerText=\'Copiar\', 1200);" class="px-2 py-1 rounded bg-primary-600 text-white hover:bg-primary-700 transition">Copiar</button>' .
                                                '</div>' .
                                                '<div class="text-xs text-gray-500 mt-1">Haz clic para copiar el enlace o selecciona el texto.</div>'
                                            );
                                        })
                                        ->hiddenOn('create')
                                        ->columnSpanFull(),
                                    // QR del link
                                    Forms\Components\Placeholder::make('qr_code')
                                        ->label('Código QR')
                                        ->content(fn ($record) => $record ? new HtmlString('
                                            <div class="flex justify-center p-4 bg-white rounded-lg">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . 
                                                urlencode(url('/contrato/firma/' . $record->uuid)) . 
                                                '" alt="QR Code" class="border-2 border-gray-300 rounded-lg shadow-lg"/>
                                            </div>
                                            <p class="text-center text-xs text-gray-500 mt-2">Escanee para acceder al formulario de firma</p>
                                        ') : 'QR no disponible')
                                        ->hiddenOn('create'),
                                    // Botón ver PDF firmado
                                    Forms\Components\Placeholder::make('contract_pdf')
                                        ->label('Contrato firmado (PDF)')
                                        ->content(function ($record) {
                                            if (! $record || ! $record->file_path) {
                                                return '<span class="text-xs text-gray-500">No disponible aún</span>';
                                            }
                                            $url = asset('storage/' . $record->file_path);
                                            return new HtmlString(
                                                '<a href="' . $url . '" target="_blank" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    Ver o Descargar Contrato PDF
                                                </a>'
                                            );
                                        })
                                        ->hiddenOn('create')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ])
                ->columnSpanFull()
                ->persistTabInQueryString(),
        ]);
}


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contract_number')
                    ->label('N° Contrato')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Número copiado')
                    ->copyMessageDuration(1500)
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-document-duplicate')
                    ->tooltip('Número único del contrato'),
                
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-o-building-office-2')
                    ->color('gray')
                    ->limit(20)
                    ->tooltip(fn ($state) => $state),
                
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user')
                    ->weight('medium')
                    ->limit(25)
                    ->tooltip(fn ($state) => $state),
                
                Tables\Columns\TextColumn::make('raffle_name')
                    ->label('Rifa')
                    ->searchable()
                    ->icon('heroicon-o-ticket')
                    ->color('info')
                    ->limit(20)
                    ->tooltip(fn ($state) => $state)
                    ->toggleable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'Pendiente',
                            'signed' => 'Firmado',
                            'active' => 'Activo',
                            'terminated' => 'Terminado',
                            default => ucfirst($state),
                        };
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'signed',
                        'primary' => 'active',
                        'danger' => 'terminated',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'signed',
                        'heroicon-o-play-circle' => 'active',
                        'heroicon-o-x-circle' => 'terminated',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('signed_at')
                    ->label('Fecha Firma')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->color('success')
                    ->since()
                    ->tooltip(fn ($state) => $state ? $state->format('d/m/Y H:i:s') : null)
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('client_email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-o-envelope')
                    ->color('gray')
                    ->copyable()
                    ->copyMessage('Email copiado')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('client_phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->color('gray')
                    ->copyable()
                    ->copyMessage('Teléfono copiado')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('cedula_file')
                    ->label('Cédula')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn($state) => $state ? 'Documento cargado' : 'Sin documento')
                    ->action(fn ($record) => $record->cedula_file ? 
                        Tables\Actions\Action::make('view_cedula')
                            ->url(asset('storage/' . $record->cedula_file), true)
                            ->openUrlInNewTab()
                        : null
                    )
                    ->alignCenter(),
                
                Tables\Columns\IconColumn::make('conalot_permit_file')
                    ->label('CONALOT')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->tooltip(fn($state) => $state ? 'Permiso disponible' : 'Sin permiso')
                    ->action(fn ($record) => $record->conalot_permit_file ? 
                        Tables\Actions\Action::make('view_permit')
                            ->url(asset('storage/' . $record->conalot_permit_file), true)
                            ->openUrlInNewTab()
                        : null
                    )
                    ->alignCenter()
                    ->toggleable(),

                    Tables\Columns\IconColumn::make('file_path')
    ->label('Contrato PDF')
    ->boolean(fn ($state) => !empty($state)) // muestra true/false según exista archivo
    ->trueIcon('heroicon-o-document-arrow-down')
    ->trueColor('success')
    ->falseIcon('heroicon-o-x-mark')
    ->falseColor('gray')
    ->url(
        fn ($record) => $record->file_path ? asset('storage/' . $record->file_path) : null,
        true // abrir en nueva pestaña
    )
    ->tooltip(fn($state) => $state ? 'Ver o descargar contrato firmado' : 'No disponible')
    ->alignCenter()
    ->toggleable(),

                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->since()
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado del contrato')
                    ->options([
                        'pending' => 'Pendiente',
                        'signed' => 'Firmado',
                        'active' => 'Activo',
                        'terminated' => 'Terminado',
                    ])
                    ->placeholder('Todos los estados')
                    ->indicator('Estado'),
                
                SelectFilter::make('tenant_id')
                    ->label('Empresa')
                    ->relationship('tenant', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Todas las empresas')
                    ->indicator('Empresa'),
                
                Filter::make('signed_contracts')
                    ->label('Contratos firmados')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('signed_at'))
                    ->toggle(),
                
                Filter::make('pending_signature')
                    ->label('Pendientes de firma')
                    ->query(fn (Builder $query): Builder => $query->whereNull('signed_at'))
                    ->toggle(),
                
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Creado desde'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Creado hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Desde: ' . \Carbon\Carbon::parse($data['created_from'])->format('d/m/Y');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Hasta: ' . \Carbon\Carbon::parse($data['created_until'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),
            ])
            
            ->filtersFormColumns(4)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Ver detalles')
                        ->icon('heroicon-o-eye')
                        ->color('info'),
                    
                    Tables\Actions\EditAction::make()
                        ->label('Editar')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning'),
                    
                    Tables\Actions\Action::make('send_signature')
                        ->label('Enviar link de firma')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Enviar enlace de firma')
                        ->modalDescription('¿Desea enviar el enlace de firma al cliente?')
                        ->modalIcon('heroicon-o-envelope')
                        ->modalIconColor('success')
                        ->action(function ($record) {
                            // Aquí puedes agregar la lógica para enviar el email
                            \Filament\Notifications\Notification::make()
                                ->title('Enlace enviado')
                                ->body('El enlace de firma ha sido enviado a ' . $record->client_email)
                                ->success()
                                ->send();
                        })
                        ->visible(fn ($record) => $record->status === 'pending'),
                    
                    Tables\Actions\Action::make('copy_link')
                        ->label('Copiar link')
                        ->icon('heroicon-o-clipboard-document')
                        ->color('primary')
                        ->action(function ($record) {
                            \Filament\Notifications\Notification::make()
                                ->title('Enlace copiado')
                                ->body('El enlace de firma ha sido copiado al portapapeles')
                                ->success()
                                ->duration(2000)
                                ->send();
                        }),
                    
                    Tables\Actions\DeleteAction::make()
                        ->label('Eliminar')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar contrato')
                        ->modalDescription('¿Está seguro de que desea eliminar este contrato? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Sí, eliminar'),
                ])
                ->icon('heroicon-o-ellipsis-horizontal-circle')
                ->label('Acciones')
                ->color('gray')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar contratos seleccionados')
                        ->modalDescription('¿Está seguro de que desea eliminar los contratos seleccionados? Esta acción no se puede deshacer.')
                        ->deselectRecordsAfterCompletion(),
                    
                    Tables\Actions\BulkAction::make('export')
                        ->label('Exportar seleccionados')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            // Aquí puedes agregar la lógica de exportación
                            \Filament\Notifications\Notification::make()
                                ->title('Exportación completada')
                                ->body('Se han exportado ' . $records->count() . ' contratos')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->emptyStateHeading('No hay contratos registrados')
            ->emptyStateDescription('Comience creando un nuevo contrato de servicio')
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear primer contrato')
                    ->icon('heroicon-o-plus'),
            ])
            ->poll('60s')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->extremePaginationLinks()
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [
            // Aquí puedes agregar relaciones como:
            // ContractDocumentsRelationManager::class,
            // ContractHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit'   => Pages\EditContract::route('/{record}/edit'),
            'view'   => Pages\ViewContract::route('/{record}'),
        ];
    }
    
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'contract_number',
            'client_name',
            'client_email',
            'client_id_number',
            'raffle_name',
        ];
    }
    
    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'N° Contrato' => $record->contract_number,
            'Cliente' => $record->client_name,
            'Estado' => match($record->status) {
                'pending' => '⏳ Pendiente',
                'signed' => '✅ Firmado',
                'active' => '▶️ Activo',
                'terminated' => '❌ Terminado',
                default => $record->status,
            },
        ];
    }
    
    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->contract_number . ' - ' . $record->client_name;
    }
    
    public static function getWidgets(): array
    {
        return [
            // Aquí puedes agregar widgets personalizados
            // ContractStatsWidget::class,
        ];
    }
}