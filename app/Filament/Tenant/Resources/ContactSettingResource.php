<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\ContactSettingResource\Pages;
use App\Models\ContactSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactSettingResource extends Resource
{
    protected static ?string $model = ContactSetting::class;
    protected static ?string $tenantOwnershipRelationshipName = 'tenant';

    protected static ?string $navigationIcon  = 'heroicon-o-phone';
    protected static ?string $navigationLabel = 'Contacto & Redes';
    protected static ?string $pluralLabel     = 'Contacto & Redes';
    protected static ?string $modelLabel      = 'Contacto & Redes';
    protected static ?int    $navigationSort  = 15;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('WhatsApp')
                ->description('Se usa para el botón flotante y enlaces de contacto.')
                ->schema([
                    Forms\Components\TextInput::make('whatsapp_phone')
                        ->label('Teléfono (formato E.164)')
                        ->helperText('Ej: +584121234567 (con + y código de país)')
                        ->tel()
                        ->maxLength(30),
                    Forms\Components\TextInput::make('whatsapp_message')
                        ->label('Mensaje por defecto')
                        ->placeholder('Hola, tengo una consulta...')
                        ->maxLength(160),
                    Forms\Components\Toggle::make('show_whatsapp_widget')
                        ->label('Mostrar botón flotante de WhatsApp')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Contacto')
                ->schema([
                    Forms\Components\TextInput::make('email')->label('Correo')->email(),
                    Forms\Components\TextInput::make('website_url')->label('Sitio web')->url(),
                    Forms\Components\TextInput::make('address')->label('Dirección')->maxLength(190),
                ])->columns(2),

            Forms\Components\Section::make('Redes sociales')
                ->schema([
                    Forms\Components\TextInput::make('instagram_url')->label('Instagram')->url(),
                    Forms\Components\TextInput::make('facebook_url')->label('Facebook')->url(),
                    Forms\Components\TextInput::make('tiktok_url')->label('TikTok')->url(),
                    Forms\Components\TextInput::make('youtube_url')->label('YouTube')->url(),
                    Forms\Components\TextInput::make('telegram_url')->label('Telegram')->url(),
                ])->columns(2),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('show_whatsapp_widget')->label('WhatsApp')->boolean(),
                Tables\Columns\TextColumn::make('whatsapp_phone')->label('Teléfono'),
                Tables\Columns\TextColumn::make('email')->label('Correo'),
                Tables\Columns\TextColumn::make('instagram_url')->label('Instagram')->limit(20),
                Tables\Columns\TextColumn::make('updated_at')->label('Actualizado')->since(),
            ])
            ->emptyStateHeading('Sin datos de contacto')
            ->emptyStateDescription('Agrega tu WhatsApp, correo y redes sociales.')
            ->emptyStateActions([ Tables\Actions\CreateAction::make()->label('Agregar contacto') ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
            ]);
    }

    public static function canCreate(): bool
    {
        return ! static::getEloquentQuery()->exists();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContactSettings::route('/'),
            'create' => Pages\CreateContactSetting::route('/create'),
            'edit'   => Pages\EditContactSetting::route('/{record}/edit'),
        ];
    }
}
