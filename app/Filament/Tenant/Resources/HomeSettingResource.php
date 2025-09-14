<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\HomeSettingResource\Pages;
use App\Models\HomeSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;

class HomeSettingResource extends Resource
{
    protected static ?string $model = HomeSetting::class;
    protected static ?string $tenantOwnershipRelationshipName = 'tenant';

    protected static ?string $navigationIcon  = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel = 'Configuración de Portada';
    protected static ?string $pluralLabel     = 'Configuración de Portada';
    protected static ?string $modelLabel      = 'Portada';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $navigationGroup = 'Configuración del Sitio';
    
    // Badge en el menú mostrando estado
    public static function getNavigationBadge(): ?string
    {
        $setting = static::getEloquentQuery()->first();
        return $setting ? '✓' : '!';
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        $setting = static::getEloquentQuery()->first();
        return $setting ? 'success' : 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Tabs para mejor organización
            Tabs::make('Configuración')
                ->tabs([
                    // Tab 1: Contenido Principal
                    Tabs\Tab::make('Contenido Principal')
                        ->icon('heroicon-m-pencil-square')
                        ->schema([
                            Section::make('Información Principal')
                                ->description('Define el contenido principal que verán los visitantes')
                                ->icon('heroicon-o-document-text')
                                ->collapsible()
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('titulo')
                                            ->label('Título Principal')
                                            ->placeholder('Ej: Gran Rifa Benéfica 2024')
                                            ->required()
                                            ->maxLength(120)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state) => 
                                                strlen($state) > 100 ? 
                                                Notification::make()
                                                    ->warning()
                                                    ->title('Título muy largo')
                                                    ->body('Considera usar un título más corto para mejor visualización')
                                                    ->send() : null
                                            )
                                            ->helperText('Máximo 120 caracteres')
                                            ->prefixIcon('heroicon-m-h1'),

                                        Forms\Components\TextInput::make('subtitulo')
                                            ->label('Subtítulo')
                                            ->placeholder('Ej: Participa y gana increíbles premios')
                                            ->maxLength(160)
                                            ->helperText('Opcional - Máximo 160 caracteres')
                                            ->prefixIcon('heroicon-m-bars-3-bottom-left'),
                                    ]),

                                    Forms\Components\Textarea::make('descripcion')
                                        ->label('Descripción / Presentación')
                                        ->placeholder('Describe aquí tu empresa, negocio o evento principal...')
                                        ->rows(4)
                                        ->maxLength(400)
                                        ->columnSpanFull()
                                        ->helperText(function ($state) {
                                            $length = strlen($state ?? '');
                                            $remaining = 400 - $length;
                                            return new HtmlString("
                                                <span class='" . ($remaining < 50 ? 'text-warning-600' : '') . "'>
                                                    {$remaining} caracteres restantes
                                                </span>
                                            ");
                                        })
                                        ->live(debounce: 500),
                                ]),

                            Section::make('Llamada a la Acción')
                                ->description('Configura el botón principal de acción')
                                ->icon('heroicon-o-cursor-arrow-rays')
                                ->schema([
                                    Forms\Components\TextInput::make('cta_label')
                                        ->label('Texto del Botón Principal')
                                        ->default('Elegir mis números')
                                        ->maxLength(40)
                                        ->helperText('Este es el botón que los usuarios verán para participar')
                                        ->prefixIcon('heroicon-m-cursor-arrow-ripple')
                                        ->suffixAction(
                                            Forms\Components\Actions\Action::make('preview')
                                                ->icon('heroicon-m-eye')
                                                ->label('Vista previa')
                                                ->modalHeading('Vista previa del botón')
                                                ->modalContent(fn ($state) => new HtmlString("
                                                    <div class='flex justify-center p-8'>
                                                        <button class='px-6 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-colors'>
                                                            {$state}
                                                        </button>
                                                    </div>
                                                "))
                                                ->modalFooterActions([])
                                        ),
                                ]),
                        ]),

                    // Tab 2: Imagen y Multimedia
                    Tabs\Tab::make('Imagen de Portada')
                        ->icon('heroicon-m-photo')
                        ->schema([
                            Section::make('Banner Principal')
    ->description('La imagen principal que se mostrará en la portada')
    ->schema([
        Forms\Components\FileUpload::make('banner_path')
    ->label('Banner')
    ->directory('banners')
    ->image()
    ->imageEditor()
    ->imageEditorAspectRatios([
        '16:9',
        '4:3',
        '1:1',
    ])
    ->maxSize(4096)
    ->columnSpanFull()
    ->helperText('Recomendado: 1920x1080px, máximo 4MB. Formatos: JPG, PNG, WebP')
    ->imagePreviewHeight('300')
    ->panelLayout('integrated')
    ->removeUploadedFileButtonPosition('right')
    ->uploadButtonPosition('left')
    ->uploadProgressIndicatorPosition('left')

    ])

                        ]),

                    // Tab 3: Configuración del Sorteo
                    Tabs\Tab::make('Configuración del Sorteo')
                        ->icon('heroicon-m-clock')
                        ->badge(fn ($record) => $record?->countdown_at ? 
                            Carbon::parse($record->countdown_at)->diffForHumans() : null
                        )
                        ->schema([
                            Section::make('Fecha y Hora del Sorteo')
                                ->description('Define cuándo se realizará el sorteo')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Forms\Components\DateTimePicker::make('countdown_at')
                                            ->label('Fecha y hora del sorteo')
                                            ->seconds(false)
                                            ->native(false)
                                            ->displayFormat('d/m/Y H:i')
                                            ->minDate(now())
                                            ->prefixIcon('heroicon-m-calendar-days')
                                            ->helperText('Selecciona la fecha y hora exacta del sorteo')
                                            ->live()
                                            ->afterStateUpdated(function ($state) {
                                                if ($state && Carbon::parse($state)->isPast()) {
                                                    Notification::make()
                                                        ->danger()
                                                        ->title('Fecha inválida')
                                                        ->body('La fecha del sorteo debe ser futura')
                                                        ->send();
                                                }
                                            }),

                                        Forms\Components\Select::make('time_zone')
                                            ->label('Zona horaria')
                                            ->options([
                                                'Zonas de América' => [
                                                    'America/Caracas' => '🇻🇪 Caracas (UTC-4)',
                                                    'America/Bogota' => '🇨🇴 Bogotá (UTC-5)',
                                                    'America/Lima' => '🇵🇪 Lima (UTC-5)',
                                                    'America/Mexico_City' => '🇲🇽 Ciudad de México (UTC-6)',
                                                    'America/Santiago' => '🇨🇱 Santiago (UTC-3)',
                                                    'America/Argentina/Buenos_Aires' => '🇦🇷 Buenos Aires (UTC-3)',
                                                    'America/Sao_Paulo' => '🇧🇷 São Paulo (UTC-3)',
                                                    'America/New_York' => '🇺🇸 Nueva York (UTC-5)',
                                                ],
                                                'Otras Zonas' => [
                                                    'UTC' => '🌍 UTC (Tiempo Universal)',
                                                    'Europe/Madrid' => '🇪🇸 Madrid (UTC+1)',
                                                    'Europe/London' => '🇬🇧 Londres (UTC+0)',
                                                ],
                                            ])
                                            ->searchable()
                                            ->default('America/Caracas')
                                            ->prefixIcon('heroicon-m-globe-americas')
                                            ->helperText('Selecciona la zona horaria de tu ubicación'),
                                    ]),

                                    // Información adicional
                                    Forms\Components\Placeholder::make('countdown_info')
                                        ->label('Tiempo restante')
                                        ->content(function ($record) {
                                            if (!$record?->countdown_at) {
                                                return 'No configurado';
                                            }
                                            
                                            $date = Carbon::parse($record->countdown_at, $record->time_zone);
                                            $now = Carbon::now($record->time_zone);
                                            
                                            if ($date->isPast()) {
                                                return new HtmlString('<span class="text-danger-600 font-bold">⚠️ Sorteo finalizado</span>');
                                            }
                                            
                                            $diff = $date->diff($now);
                                            return new HtmlString("
                                                <div class='space-y-2'>
                                                    <div class='text-2xl font-bold text-primary-600'>
                                                        {$diff->days} días, {$diff->h} horas, {$diff->i} minutos
                                                    </div>
                                                    <div class='text-sm text-gray-500'>
                                                        Fecha exacta: {$date->format('d/m/Y H:i')} ({$record->time_zone})
                                                    </div>
                                                </div>
                                            ");
                                        })
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ])
                ->contained(false)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('banner_path')
                        ->label('Banner')
                        ->height('200px')
                        ->width('100%')
                        ->extraImgAttributes(['class' => 'rounded-lg object-cover']),
                    
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('titulo')
                                ->label('Título')
                                ->weight(FontWeight::Bold)
                                ->size('lg')
                                ->color('primary')
                                ->searchable(),
                            
                            Tables\Columns\TextColumn::make('subtitulo')
                                ->label('Subtítulo')
                                ->color('gray')
                                ->searchable(),
                            
                            Tables\Columns\TextColumn::make('descripcion')
                                ->label('Descripción')
                                ->limit(100)
                                ->tooltip(function ($record) {
                                    return $record->descripcion;
                                })
                                ->wrap()
                                ->color('gray'),
                        ])->space(1),
                        
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('countdown_at')
                                ->label('Fecha del Sorteo')
                                ->badge()
                                ->color(fn ($state) => 
                                    !$state ? 'gray' : 
                                    (Carbon::parse($state)->isPast() ? 'danger' : 'success')
                                )
                                ->formatStateUsing(fn ($state) => 
                                    $state ? Carbon::parse($state)->format('d/m/Y H:i') : 'Sin definir'
                                )
                                ->description(fn ($record) => 
                                    $record->countdown_at ? 
                                    'Zona: ' . $record->time_zone : null
                                )
                                ->icon(fn ($state) => 
                                    !$state ? 'heroicon-o-clock' :
                                    (Carbon::parse($state)->isPast() ? 
                                        'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                                ),
                            
                            Tables\Columns\TextColumn::make('cta_label')
                                ->label('Botón CTA')
                                ->badge()
                                ->color('info')
                                ->icon('heroicon-m-cursor-arrow-ripple'),
                            
                            Tables\Columns\TextColumn::make('updated_at')
                                ->label('Última actualización')
                                ->since()
                                ->color('gray')
                                ->icon('heroicon-m-clock'),
                        ])->space(2),
                    ]),
                ])->space(3),
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 1,
            ])
            ->emptyStateHeading('Sin configuración de portada')
            ->emptyStateDescription('Configura la portada de tu sitio para que los visitantes vean información atractiva.')
            ->emptyStateIcon('heroicon-o-home')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Configurar Portada')
                    ->icon('heroicon-m-plus-circle')
                    ->size('lg')
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Ver')
                        ->icon('heroicon-m-eye')
                        ->color('info'),
                    
                    Tables\Actions\EditAction::make()
                        ->label('Editar')
                        ->icon('heroicon-m-pencil-square')
                        ->color('warning'),
                    
                    Tables\Actions\Action::make('preview')
                        ->label('Vista previa en sitio')
                        ->icon('heroicon-m-arrow-top-right-on-square')
                        ->color('success')
                        ->url(fn () => url('/'))
                        ->openUrlInNewTab(),
                ])
                ->label('Acciones')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button(),
            ])
            ->paginated(false);
    }

    public static function canCreate(): bool
    {
        return ! static::getEloquentQuery()->exists();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHomeSettings::route('/'),
            'create' => Pages\CreateHomeSetting::route('/create'),
            'edit'   => Pages\EditHomeSetting::route('/{record}/edit'),
        ];
    }
    
    // Personalizar los breadcrumbs
    public static function getBreadcrumbs(): array
    {
        return [
            url('/admin') => 'Dashboard',
            url('/admin/home-settings') => 'Configuración de Portada',
        ];
    }
}