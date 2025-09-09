<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\RifaResource\Pages;
use App\Models\Rifa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;


class RifaResource extends Resource
{
    protected static ?string $model = Rifa::class;
    protected static ?string $tenantOwnershipRelationshipName = 'tenant';
    protected static ?string $tenantRelationshipName = 'rifas';

    protected static ?string $navigationIcon  = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Rifas';
    protected static ?string $pluralLabel     = 'Rifas';
    protected static ?string $modelLabel      = 'Rifa';
    protected static ?int    $navigationSort  = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Rifa')
                ->tabs([

                    // 1) Datos generales
                    Forms\Components\Tabs\Tab::make('General')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Grid::make(12)->schema([
                                Forms\Components\FileUpload::make('banner_path')
                                    ->label('Banner')
                                    ->directory('rifas')->image()->imageEditor()->maxSize(4096)
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Imagen principal de la rifa (se muestra en la tienda).')
                                    ->columnSpan(12),

                                Forms\Components\TextInput::make('titulo')
                                    ->label('Título')->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state)))
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Nombre público de la rifa.')
                                    ->columnSpan(6),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')->required()
                                    ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule) {
                                        return $rule->where('tenant_id', auth()->user()?->tenants()->first()?->id ?? 0);
                                    })
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Identificador de la URL (sin espacios).')
                                    ->columnSpan(6),

                                Forms\Components\Textarea::make('descripcion')
                                    ->label('Descripción')->rows(4)
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Texto informativo que verán los compradores.')
                                    ->columnSpan(12),

                                    // ==== FONDO PERSONALIZADO (color o imagen) ====
Forms\Components\Grid::make(12)->schema([
    Forms\Components\Select::make('bg_type')
        ->label('Fondo de la rifa')
        ->options([
            'default' => 'Blanco / Predeterminado',
            'color'   => 'Color personalizado',
            'image'   => 'Imagen de fondo',
        ])
        ->default('default')
        ->reactive()
        ->columnSpan(4),

    Forms\Components\ColorPicker::make('bg_color')
        ->label('Color de fondo')
        ->visible(fn ($get) => $get('bg_type') === 'color')
        ->columnSpan(4),

    Forms\Components\FileUpload::make('bg_image_path')
        ->label('Imagen de fondo')
        ->directory('rifa-backgrounds')
        ->image()
        ->imageEditor()
        ->maxSize(4096)
        ->visible(fn ($get) => $get('bg_type') === 'image')
        ->columnSpan(4)
        ->hint('Recomendado: horizontal, máx 1MB.'),
]),

                            ]),
                        ]),

                    // 2) Venta y números
                    Forms\Components\Tabs\Tab::make('Venta')
                        ->icon('heroicon-o-currency-dollar')
                        ->schema([
                            Forms\Components\Grid::make(12)->schema([
                                Forms\Components\TextInput::make('precio')
                                    ->label('Precio por número')->numeric()->prefix('$')->required()
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Precio unitario por número.')
                                    ->columnSpan(4),

                                Forms\Components\TextInput::make('total_numeros')
                                    ->label('Total de números')->numeric()->minValue(1)->maxValue(100000)->default(100)->required()
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Cantidad total disponible en la rifa.')
                                    ->columnSpan(4),

                                Forms\Components\TextInput::make('min_por_compra')
                                    ->label('Mínimo por compra')->numeric()->minValue(1)->default(1)
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Mínimo de números por pedido.')
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('max_por_compra')
                                    ->label('Máximo por compra')->numeric()->minValue(1)->default(10)
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Máximo de números por pedido.')
                                    ->columnSpan(2),
                            ]),
                        ]),

                    // 3) Fechas y estado
                    Forms\Components\Tabs\Tab::make('Fechas & Estado')
                        ->icon('heroicon-o-calendar')
                        ->schema([
                            Forms\Components\Grid::make(12)->schema([
                                Forms\Components\Select::make('estado')
                                    ->label('Estado')->options([
                                        'borrador'   => 'Borrador',
                                        'activa'     => 'Activa',
                                        'pausada'    => 'Pausada',
                                        'finalizada' => 'Finalizada',
                                    ])->default('borrador')
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Solo “Activa” permite reservas en la tienda.')
                                    ->columnSpan(3),

                                Forms\Components\DateTimePicker::make('starts_at')
                                    ->label('Inicio')->seconds(false)
                                    ->helperText('Desde cuándo se puede vender.')
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Fecha/hora de habilitación de ventas.')
                                    ->columnSpan(4),

                                Forms\Components\DateTimePicker::make('ends_at')
                                    ->label('Fin (sorteo)')->seconds(false)
                                    ->helperText('Si indicas Lotería, esta fecha es la del sorteo.')
                                    ->rule('after_or_equal:starts_at') // fin ≥ inicio
                                    ->required(fn (Get $get) => filled($get('lottery_name')))
                                    ->rule(fn (Get $get) => filled($get('lottery_name')) ? 'after:now' : null)
                                    ->hintIcon('heroicon-o-question-mark-circle')
                                    ->hint('Límite de venta y fecha del sorteo si hay Lotería.')
                                    ->columnSpan(5),
                            ]),
                        ]),

                    // 4) Sorteo / Lotería + Premios especiales
                    Forms\Components\Tabs\Tab::make('Sorteo')
                        ->icon('heroicon-o-trophy')
                        ->schema([
                            Forms\Components\Section::make('Lotería (opcional)')
                                ->description('Universal: escribe el nombre y, si aplica, el tipo. El “Fin” es la fecha del sorteo.')
                                ->schema([
                                    Forms\Components\Grid::make(12)->schema([
                                        Forms\Components\TextInput::make('lottery_name')
                                            ->label('Lotería')
                                            ->placeholder('Ej: Lotería del Táchira')
                                            ->maxLength(100)
                                            ->hintIcon('heroicon-o-question-mark-circle')
                                            ->hint('Nombre de la lotería oficial que define el resultado.')
                                            ->columnSpan(6),

                                        Forms\Components\TextInput::make('lottery_type')
                                            ->label('Tipo (opcional)')
                                            ->placeholder('Ej: Triple A')
                                            ->maxLength(100)
                                            ->hintIcon('heroicon-o-question-mark-circle')
                                            ->hint('Variante del juego (solo si tu país la usa).')
                                            ->columnSpan(6),
                                    ]),
                                ]),

                            // ======= Premios especiales (Repeater relacional) =======
                            Forms\Components\Section::make('Premios especiales')
                                ->description('Agrega premios adicionales con su lotería/tipo y fecha/hora del sorteo.')
                                ->collapsible()
                                ->collapsed()
                                ->schema([
                                    Forms\Components\Repeater::make('specialPrizes')
    ->label('Lista de premios')
    ->relationship('specialPrizes') // relación hasMany
    ->default([])
    ->reorderable(false)
    ->minItems(0)
    ->maxItems(20)
    ->grid(12)
    ->itemLabel(fn (?array $state) => $state['title'] ?? 'Premio')
    ->schema([
        Forms\Components\TextInput::make('title')
            ->label('Premio especial')
            ->required()
            ->maxLength(120)
            ->hintIcon('heroicon-o-question-mark-circle')
            ->hint('Ej: 2do premio, Bono extra, etc.')
            ->columnSpan(6),

        Forms\Components\TextInput::make('lottery_name')
            ->label('Lotería')
            ->required() // <— OBLIGATORIO
            ->minLength(2)
            ->dehydrateStateUsing(fn ($v) => trim((string) $v))
            ->validationMessages(['required' => 'La lotería es obligatoria.'])
            ->placeholder('Ej: Lotería del Zulia')
            ->maxLength(120)
            ->columnSpan(3),

        Forms\Components\TextInput::make('lottery_type')
            ->label('Tipo')
            ->placeholder('Ej: Triple A')
            ->maxLength(120)
            ->columnSpan(3),

        Forms\Components\DateTimePicker::make('draw_at')
            ->label('Fecha y hora del sorteo')
            ->seconds(false)
            ->native(false)
            ->columnSpan(4),
    ])
    // ——— Normalización y multi-tenant, para que nunca quede basura en BD ———
    ->mutateRelationshipDataBeforeCreateUsing(function (array $data) {
        $data['tenant_id'] = Filament::getTenant()?->id;

        // "tipo" es opcional: si viene vacío, lo guardamos como NULL
        $type = trim((string)($data['lottery_type'] ?? ''));
        $data['lottery_type'] = ($type === '') ? null : $type;

        return $data;
    })
    ->mutateRelationshipDataBeforeSaveUsing(function (array $data) {
        $data['tenant_id'] = Filament::getTenant()?->id;

        $type = trim((string)($data['lottery_type'] ?? ''));
        $data['lottery_type'] = ($type === '') ? null : $type;

        return $data;
    })
    ->addActionLabel('Agregar premio'),
                                ]),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
{
    return $table
        // 🔥 Filtra por tenant SIEMPRE
        ->modifyQueryUsing(function (Builder $query) {
    $tenant = \Filament\Facades\Filament::getTenant();
    // Debug temporal para consola:
    logger('Tenant en table RifaResource:', [$tenant?->id, $tenant?->name]);
    if ($tenant) {
        $query->where('tenant_id', $tenant->id);
        $query->withCount([
            'numeros as disponibles' => fn ($qq) => $qq->where('estado', 'disponible')
        ]);
    }
})

        ->columns([
            Tables\Columns\ImageColumn::make('banner_path')->label('Banner')->rounded(),
            Tables\Columns\TextColumn::make('titulo')->label('Título')->searchable()->wrap(),
            Tables\Columns\TextColumn::make('precio')->label('Precio')->prefix('$')->numeric(2),
            Tables\Columns\TextColumn::make('total_numeros')->label('Total'),
            Tables\Columns\TextColumn::make('disponibles')
                ->label('Disponibles')
                ->formatStateUsing(fn ($state) => $state ?? 0), // Fallback a 0
            Tables\Columns\TextColumn::make('lottery_name')
                ->label('Lotería')
                ->placeholder('—')
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('lottery_type')
                ->label('Tipo')
                ->placeholder('—')
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\BadgeColumn::make('estado')->label('Estado')->colors([
                'warning' => 'borrador',
                'success' => 'activa',
                'gray'    => 'pausada',
                'danger'  => 'finalizada',
            ]),
            Tables\Columns\TextColumn::make('updated_at')->since()->label('Actualizado'),
        ])
        ->actions([
            Tables\Actions\EditAction::make()->label('Editar'),
            Tables\Actions\Action::make('generar')
                ->label('Generar números')
                ->icon('heroicon-o-hashtag')
                ->requiresConfirmation()
                ->action(function (Rifa $record) {
                    \Artisan::call('rifa:generate-numbers', ['rifa_id' => $record->id, '--force' => true]);
                    Notification::make()
                        ->title('Números generados')
                        ->body('Se generaron los números del 1 al ' . $record->total_numeros . '.')
                        ->success()
                        ->send();
                }),
        ]);
}


    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRifas::route('/'),
            'create' => Pages\CreateRifa::route('/create'),
            'edit'   => Pages\EditRifa::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            // Usamos Repeater relacional en el propio formulario (no RelationManager separado)
        ];
    }
}
