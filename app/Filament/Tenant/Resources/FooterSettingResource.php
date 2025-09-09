<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\FooterSettingResource\Pages;
use App\Models\FooterSetting;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class FooterSettingResource extends Resource
{
    protected static ?string $model = FooterSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Footer';
    protected static ?string $pluralLabel     = 'Pie de página';
    protected static ?string $modelLabel      = 'Footer';

    // USAR RELACIÓN SINGULAR:
    protected static ?string $tenantRelationshipName = 'footerSetting'; // <--- Importante

    // Íconos comunes para mostrar en el Select de redes
    public static function socialIconsList(): array
    {
        return [
            'fa-brands fa-facebook'  => 'Facebook',
            'fa-brands fa-instagram' => 'Instagram',
            'fa-brands fa-whatsapp'  => 'WhatsApp',
            'fa-brands fa-tiktok'    => 'TikTok',
            'fa-brands fa-youtube'   => 'YouTube',
            'fa-brands fa-x-twitter' => 'X / Twitter',
            'fa-brands fa-telegram'  => 'Telegram',
            'fa-brands fa-linkedin'  => 'LinkedIn',
            'fa-brands fa-threads'   => 'Threads',
            'fa-solid fa-globe'      => 'Sitio web',
            // Puedes agregar más si quieres
        ];
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Section::make('Marca')
                ->description('Configura los datos de tu marca para el pie de página.')
                ->schema([
                    Forms\Components\TextInput::make('brand_name')
                        ->label('Nombre de la marca')
                        ->maxLength(64)
                        ->required(),

                    Forms\Components\FileUpload::make('logo_path')
                        ->label('Logo (recomendado PNG cuadrado)')
                        ->directory('footers')
                        ->image()
                        ->imageEditor()
                        ->maxSize(2048)
                        ->columnSpan(2),

                    Forms\Components\Textarea::make('description')
                        ->label('Descripción breve')
                        ->maxLength(300)
                        ->rows(2)
                        ->helperText('Texto corto sobre tu empresa o eslogan.')
                ])
                ->columns(3),

            Forms\Components\Section::make('Contacto')
                ->description('Información de contacto visible en el footer.')
                ->schema([
                    Forms\Components\TextInput::make('email')
                        ->label('Correo electrónico'),
                    Forms\Components\TextInput::make('phone')
                        ->label('Teléfono'),
                    Forms\Components\TextInput::make('website_url')
                        ->label('Sitio web'),
                    Forms\Components\Textarea::make('address')
                        ->label('Dirección')
                        ->rows(2),
                ])
                ->columns(2),

            Forms\Components\Section::make('Redes sociales')
                ->description('Agrega todas las redes que quieras con su nombre y enlace. Elige el ícono que deseas mostrar.')
                ->schema([
                    Forms\Components\Repeater::make('socials')
                        ->label('Redes sociales')
                        ->minItems(0)
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nombre de la red')
                                ->required(),
                            Forms\Components\TextInput::make('url')
                                ->label('Enlace')
                                ->required(),
                            Forms\Components\Select::make('icon')
                                ->label('Ícono')
                                ->options(self::socialIconsList())
                                ->searchable()
                                ->required()
                                ->helperText('Selecciona el ícono (solo las redes más conocidas).'),
                            // (OPCIONAL) Si quieres permitir íconos personalizados, agrega este input:
                            // Forms\Components\TextInput::make('icon')->label('Ícono personalizado (opcional)')
                        ])
                        ->columns(3)
                ]),

            Forms\Components\Section::make('Links rápidos')
                ->description('Enlaces extra que quieras mostrar en el pie (Ejemplo: Sobre nosotros, Blog, Soporte).')
                ->schema([
                    Forms\Components\Repeater::make('quick_links')
                        ->label('Links rápidos')
                        ->minItems(0)
                        ->schema([
                            Forms\Components\TextInput::make('name')->label('Texto')->required(),
                            Forms\Components\TextInput::make('url')->label('Enlace')->required()
                        ])
                        ->columns(2)
                ]),

            Forms\Components\Section::make('Legal y diseño')
                ->description('Configura colores y enlaces legales del footer.')
                ->schema([
                    Forms\Components\ColorPicker::make('bg_color')
                        ->label('Color de fondo'),
                    Forms\Components\ColorPicker::make('text_color')
                        ->label('Color del texto'),
                    Forms\Components\TextInput::make('terms_url')->label('Enlace Términos'),
                    Forms\Components\TextInput::make('privacy_url')->label('Enlace Privacidad'),
                ])
                ->columns(2),

            Forms\Components\Section::make('HTML personalizado')
                ->description('Si necesitas agregar HTML adicional al footer.')
                ->schema([
                    Forms\Components\Textarea::make('custom_html')
                        ->label('Bloque HTML adicional')
                        ->rows(4)
                        ->maxLength(1000)
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')->label('Logo')->circular(),
                Tables\Columns\TextColumn::make('brand_name')->label('Marca')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Correo'),
                Tables\Columns\TextColumn::make('phone')->label('Teléfono'),
                Tables\Columns\TextColumn::make('bg_color')->label('Fondo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFooterSettings::route('/'),
            'create' => Pages\CreateFooterSetting::route('/crear'),
            'edit' => Pages\EditFooterSetting::route('/{record}/editar'),
        ];
    }
}
