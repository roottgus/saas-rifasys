<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\RifaResource\Pages;
use App\Models\Rifa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;

class RifaResource extends Resource
{
    protected static ?string $model = Rifa::class;
    protected static ?string $tenantOwnershipRelationshipName = 'tenant';
    protected static ?string $tenantRelationshipName = 'rifas';

    protected static ?string $navigationIcon  = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Rifas';
    protected static ?string $pluralLabel     = 'Rifas';
    protected static ?string $modelLabel      = 'Rifa';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $navigationGroup = 'Rifas y Sorteos';
    
    public static function getNavigationBadge(): ?string
    {
        $tenant = Filament::getTenant();
        if (!$tenant) return null;
        
        $count = static::getModel()::query()
            ->where('tenant_id', $tenant->id)
            ->where('estado', 'activa')
            ->count();
            
        return $count > 0 ? (string) $count : null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Configuración de Rifa')
                ->tabs([
                    
                    // TAB 1: INFORMACIÓN GENERAL
                    Forms\Components\Tabs\Tab::make('Información General')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Section::make('Datos principales')
                                ->description('Información básica que verán los participantes')
                                ->schema([
                                    Forms\Components\FileUpload::make('banner_path')
                                        ->label('Banner principal')
                                        ->directory('rifas')
                                        ->image()
                                        ->imageEditor()
                                        ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                                        ->maxSize(4096)
                                        ->required()
                                        ->helperText('Imagen principal de la rifa (Recomendado: 1920x1080px)')
                                        ->columnSpanFull(),

                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('titulo')
                                            ->label('Título de la rifa')
                                            ->required()
                                            ->maxLength(120)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Set $set, $state) => 
                                                $set('slug', Str::slug($state))
                                            )
                                            ->prefixIcon('heroicon-m-sparkles')
                                            ->placeholder('Ej: Gran Rifa Benéfica 2024'),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('URL amigable')
                                            ->required()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule) {
                                                $tenant = Filament::getTenant();
                                                return $rule->where('tenant_id', $tenant?->id ?? 0);
                                            })
                                            ->prefixIcon('heroicon-m-link')
                                            ->prefix('rifas/')
                                            ->helperText('URL única para esta rifa'),
                                    ]),

                                    Forms\Components\Textarea::make('descripcion')
                                        ->label('Descripción detallada')
                                        ->rows(5)
                                        ->helperText('Describe los premios y la mecánica del sorteo')
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),
                        ]),

                    // TAB 2: CONFIGURACIÓN DE VENTA
                    Forms\Components\Tabs\Tab::make('Venta y Números')
                        ->icon('heroicon-o-currency-dollar')
                        ->schema([
                            Forms\Components\Section::make('Configuración de precios')
                                ->schema([
                                    Forms\Components\Grid::make(4)->schema([
                                        Forms\Components\TextInput::make('precio')
                                            ->label('Precio por número')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required()
                                            ->minValue(0.01)
                                            ->step(0.01)
                                            ->live(onBlur: true),

                                        Forms\Components\TextInput::make('total_numeros')
                                            ->label('Total de números')
                                            ->numeric()
                                            ->required()
                                            ->minValue(10)
                                            ->maxValue(100000)
                                            ->default(100),

                                        Forms\Components\TextInput::make('min_por_compra')
                                            ->label('Mínimo por compra')
                                            ->numeric()
                                            ->minValue(1)
                                            ->default(1),

                                        Forms\Components\TextInput::make('max_por_compra')
                                            ->label('Máximo por compra')
                                            ->numeric()
                                            ->minValue(1)
                                            ->default(10),
                                    ]),

                                    Forms\Components\Placeholder::make('ingresos_estimados')
                                        ->label('Ingresos potenciales')
                                        ->content(function (Get $get): HtmlString {
                                            $precio = $get('precio') ?? 0;
                                            $total = $get('total_numeros') ?? 0;
                                            $meta = $precio * $total;
                                            
                                            return new HtmlString('
                                                <div class="flex items-center gap-4 p-4 bg-success-50 dark:bg-success-900/20 rounded-lg">
                                                    <div class="text-3xl font-bold text-success-600 dark:text-success-400">
                                                        $' . number_format($meta, 2) . 
                                                    '</div>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                                        Si se venden todos los números
                                                    </div>
                                                </div>
                                            ');
                                        }),
                                ]),
                        ]),

                    // TAB 3: FECHAS Y ESTADO
                    Forms\Components\Tabs\Tab::make('Fechas y Estado')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Forms\Components\Section::make('Programación')
                                ->schema([
                                    Forms\Components\Grid::make(3)->schema([
                                        Forms\Components\Select::make('estado')
                                            ->label('Estado de la rifa')
                                            ->options([
                                                'borrador'   => '📝 Borrador',
                                                'activa'     => '✅ Activa',
                                                'pausada'    => '⏸️ Pausada',
                                                'finalizada' => '🏁 Finalizada',
                                            ])
                                            ->default('borrador')
                                            ->native(false)
                                            ->required(),

                                        Forms\Components\DatePicker::make('starts_at')
    ->label('Fecha de inicio')
    ->default(today())
    ->minDate(today())
    ->native(false)
    ->displayFormat('d/m/Y'),


                                        Forms\Components\DateTimePicker::make('ends_at')
                                            ->label('Fecha de cierre')
                                            ->seconds(false)
                                            ->native(false)
                                            ->displayFormat('d/m/Y H:i')
                                            ->minDate(fn (Get $get) => $get('starts_at')),
                                    ]),
                                ]),
                        ]),

                    // TAB 4: CONFIGURACIÓN DEL SORTEO
                    Forms\Components\Tabs\Tab::make('Sorteo')
                        ->icon('heroicon-o-trophy')
                        ->schema([
                            Forms\Components\Section::make('Configuración del sorteo')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('lottery_name')
                                            ->label('Nombre de la lotería')
                                            ->placeholder('Ej: Lotería Nacional, Lotería del Táchira')
                                            ->maxLength(100)
                                            ->helperText('Lotería oficial que determinará el ganador'),

                                        Forms\Components\TextInput::make('lottery_type')
                                            ->label('Tipo o modalidad')
                                            ->placeholder('Ej: Triple A, Zodiacal')
                                            ->maxLength(100)
                                            ->helperText('Solo si la lotería tiene modalidades'),
                                    ]),

                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\DateTimePicker::make('draw_at')
                                            ->label('Fecha y hora del sorteo')
                                            ->seconds(false)
                                            ->native(false)
                                            ->displayFormat('d/m/Y H:i')
                                            ->helperText('¿Cuándo se realizará el sorteo?'),

                                        Forms\Components\TextInput::make('external_draw_ref')
                                            ->label('Referencia externa')
                                            ->placeholder('Ej: Número de sorteo, código')
                                            ->helperText('Referencia opcional del sorteo oficial'),
                                    ]),
                                ]),

                            Forms\Components\Section::make('Premios adicionales')
                                ->description('Configura premios secundarios si lo deseas')
                                ->collapsed()
                                ->schema([
                                    Forms\Components\Repeater::make('specialPrizes')
                                        ->label('')
                                        ->relationship('specialPrizes')
                                        ->schema([
                                            Forms\Components\Grid::make(12)->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Nombre del premio')
                                                    ->required()
                                                    ->placeholder('Ej: Segundo premio')
                                                    ->columnSpan(6),

                                                Forms\Components\TextInput::make('lottery_name')
                                                    ->label('Lotería')
                                                    ->required()
                                                    ->placeholder('Ej: Lotería del Zulia')
                                                    ->columnSpan(3),
                                                    
                                                Forms\Components\TextInput::make('lottery_type')
                                                    ->label('Tipo')
                                                    ->placeholder('Opcional')
                                                    ->columnSpan(3),

                                                Forms\Components\DateTimePicker::make('draw_at')
                                                    ->label('Fecha del sorteo')
                                                    ->seconds(false)
                                                    ->native(false)
                                                    ->displayFormat('d/m/Y H:i')
                                                    ->columnSpan(6),
                                            ]),
                                        ])
                                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data) {
                                            $data['tenant_id'] = Filament::getTenant()?->id;
                                            return $data;
                                        })
                                        ->defaultItems(0)
                                        ->maxItems(10)
                                        ->addActionLabel('➕ Agregar premio adicional')
                                        ->itemLabel(fn (array $state): ?string => 
                                            $state['title'] ?? 'Premio especial'
                                        )
                                        ->collapsible()
                                        ->reorderable(),
                                ]),
                        ]),

                    // TAB 5: PERSONALIZACIÓN VISUAL
                    Forms\Components\Tabs\Tab::make('Personalización')
                        ->icon('heroicon-o-paint-brush')
                        ->schema([
                            Forms\Components\Section::make('Apariencia visual')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\ColorPicker::make('bg_color')
                                            ->label('Color de fondo')
                                            ->helperText('Color de fondo personalizado (opcional)'),

                                        Forms\Components\FileUpload::make('bg_image_path')
                                            ->label('Imagen de fondo')
                                            ->directory('rifa-backgrounds')
                                            ->image()
                                            ->imageEditor()
                                            ->maxSize(2048)
                                            ->helperText('Imagen de fondo (opcional, máx. 2MB)'),
                                    ]),
                                ]),
                        ]),
                ])
                ->columnSpanFull()
                ->persistTabInQueryString(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $tenant = Filament::getTenant();
                if ($tenant) {
                    $query->where('tenant_id', $tenant->id)
                          ->withCount([
                              'numeros as vendidos' => fn ($q) => $q->where('estado', 'vendido'),
                              'numeros as disponibles' => fn ($q) => $q->where('estado', 'disponible')
                          ]);
                }
            })
            ->columns([
                Tables\Columns\ImageColumn::make('banner_path')
                    ->label('Banner')
                    ->square()
                    ->size(80),
                    
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight(FontWeight::Bold)
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('precio')
                    ->label('Precio')
                    ->money('USD')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('progreso')
                    ->label('Progreso')
                    ->state(function ($record): HtmlString {
                        $vendidos = $record->vendidos ?? 0;
                        $total = $record->total_numeros ?? 0;
                        $porcentaje = $total > 0 ? round(($vendidos / $total) * 100) : 0;
                        
                        $color = match(true) {
                            $porcentaje >= 80 => 'bg-success-500',
                            $porcentaje >= 50 => 'bg-warning-500',
                            default => 'bg-gray-300'
                        };
                        
                        return new HtmlString("
                            <div class='w-full'>
                                <div class='flex justify-between text-xs mb-1'>
                                    <span>{$vendidos}/{$total}</span>
                                    <span class='font-bold'>{$porcentaje}%</span>
                                </div>
                                <div class='w-full bg-gray-200 rounded-full h-2'>
                                    <div class='{$color} h-2 rounded-full' style='width: {$porcentaje}%'></div>
                                </div>
                            </div>
                        ");
                    }),
                    
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'gray' => 'borrador',
                        'success' => 'activa',
                        'warning' => 'pausada',
                        'danger' => 'finalizada',
                    ]),
                    
                Tables\Columns\TextColumn::make('draw_at')
                    ->label('Sorteo')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->placeholder('Sin fecha')
                    ->color(fn ($state) => 
                        $state && Carbon::parse($state)->isPast() ? 'danger' : 'gray'
                    ),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'borrador' => 'Borrador',
                        'activa' => 'Activa',
                        'pausada' => 'Pausada',
                        'finalizada' => 'Finalizada',
                    ])
                    ->multiple()
                    ->indicator('Estado'),
                    
                Tables\Filters\Filter::make('proximas')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereNotNull('draw_at')
                              ->where('draw_at', '>=', now())
                              ->where('draw_at', '<=', now()->addDays(7))
                    )
                    ->label('Sorteos próximos (7 días)')
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('generar_numeros')
                        ->label('Generar números')
                        ->icon('heroicon-o-hashtag')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Generar números')
                        ->modalDescription(fn (Rifa $record) => 
                            "Se generarán {$record->total_numeros} números para esta rifa."
                        )
                        ->action(function (Rifa $record) {
                            // Generar números directamente
                            for ($i = 1; $i <= $record->total_numeros; $i++) {
                                $record->numeros()->firstOrCreate(
                                    ['numero' => $i],
                                    [
                                        'estado' => 'disponible',
                                        'tenant_id' => $record->tenant_id
                                    ]
                                );
                            }
                            
                            Notification::make()
                                ->title('✅ Números generados')
                                ->body("Se generaron {$record->total_numeros} números exitosamente.")
                                ->success()
                                ->send();
                        })
                        ->visible(fn (Rifa $record) => $record->numeros()->count() === 0),
                    
                    Tables\Actions\Action::make('duplicar')
                        ->label('Duplicar')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->action(function (Rifa $record) {
                            $nueva = $record->replicate();
                            $nueva->titulo = $record->titulo . ' (Copia)';
                            $nueva->slug = Str::slug($nueva->titulo) . '-' . now()->timestamp;
                            $nueva->estado = 'borrador';
                            $nueva->save();
                            
                            Notification::make()
                                ->title('📋 Rifa duplicada')
                                ->body('Se creó una copia de la rifa.')
                                ->success()
                                ->send();
                        }),
                ])
                ->label('Acciones')
                ->icon('heroicon-m-ellipsis-vertical')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No hay rifas creadas')
            ->emptyStateDescription('Crea tu primera rifa para comenzar a vender números.')
            ->emptyStateIcon('heroicon-o-ticket')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear primera rifa')
                    ->icon('heroicon-m-plus-circle'),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRifas::route('/'),
            'create' => Pages\CreateRifa::route('/create'),
            'edit' => Pages\EditRifa::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tenant_id', Filament::getTenant()?->id);
    }
    
    public static function getGloballySearchableAttributes(): array
    {
        return ['titulo', 'slug', 'descripcion'];
    }
}