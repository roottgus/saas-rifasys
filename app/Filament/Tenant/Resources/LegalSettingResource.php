<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\LegalSettingResource\Pages;
use App\Models\LegalSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LegalSettingResource extends Resource
{
    protected static ?string $model = LegalSetting::class;
    protected static ?string $tenantOwnershipRelationshipName = 'tenant';

    protected static ?string $navigationIcon  = 'heroicon-o-scale';
    protected static ?string $navigationLabel = 'Legales';
    protected static ?string $pluralLabel     = 'Legales';
    protected static ?string $modelLabel      = 'Legales';
    protected static ?int    $navigationSort  = 14;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Términos y Condiciones')->schema([
                Forms\Components\TextInput::make('titulo_terminos')->label('Título')->required(),
                Forms\Components\RichEditor::make('terminos')->label('Contenido')->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Política de Privacidad')->schema([
                Forms\Components\TextInput::make('titulo_politicas')->label('Título')->required(),
                Forms\Components\RichEditor::make('politicas')->label('Contenido')->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Política de Devoluciones')->schema([
                Forms\Components\TextInput::make('titulo_devoluciones')->label('Título')->required(),
                Forms\Components\RichEditor::make('devoluciones')->label('Contenido')->columnSpanFull(),
            ]),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('updated_at')->label('Actualizado')->since(),
            ])
            ->emptyStateHeading('Sin legales')
            ->emptyStateDescription('Crea tus textos legales.')
            ->emptyStateActions([ Tables\Actions\CreateAction::make()->label('Crear legales') ])
            ->actions([ Tables\Actions\EditAction::make()->label('Editar') ]);
    }

    public static function canCreate(): bool
    {
        return ! static::getEloquentQuery()->exists();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLegalSettings::route('/'),
            'create' => Pages\CreateLegalSetting::route('/create'),
            'edit'   => Pages\EditLegalSetting::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
{
    return false;
}

}
