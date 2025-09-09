<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\BrandSettingResource\Pages;
use App\Models\BrandSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BrandSettingResource extends Resource
{
    protected static ?string $model = BrandSetting::class;

    /** Filament Tenancy: relación que “posee” el registro (belongsTo en el modelo) */
    protected static ?string $tenantOwnershipRelationshipName = 'tenant';

    /** Filament Tenancy: relación desde Tenant hacia este recurso (hasMany en Tenant) */
    protected static ?string $tenantRelationshipName = 'brandSettings';

    /** Navegación y etiquetas en español */
    protected static ?string $navigationIcon  = 'heroicon-o-swatch';
    protected static ?string $navigationLabel = 'Marca';
    protected static ?string $pluralLabel     = 'Marca';
    protected static ?string $modelLabel      = 'Marca';
    protected static ?int    $navigationSort  = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('logo_path')
                ->label('Logo')
                ->directory('logos')     // storage/app/public/logos
                ->image()
                ->imageEditor()
                ->maxSize(2048),

            Forms\Components\ColorPicker::make('color_primary')
                ->label('Color primario')
                ->required()
                ->default('#2563EB'),

            Forms\Components\Radio::make('mode')
                ->label('Modo')
                ->options([
                    'light' => 'Claro',
                    'dark'  => 'Oscuro',
                ])
                ->inline()
                ->default('light')
                ->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->square(),

                Tables\Columns\TextColumn::make('color_primary')
                    ->label('Color'),

                Tables\Columns\BadgeColumn::make('mode')
                    ->label('Modo')
                    ->colors([
                        'success' => 'light',
                        'warning' => 'dark',
                    ]),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->since()
                    ->sortable(),
            ])
            // Estado vacío en español
            ->emptyStateHeading('Sin configuración de marca')
            ->emptyStateDescription('Crea la configuración de marca para este cliente.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Crear marca'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make()->label('Eliminar'), // opcional
                ]),
            ]);
    }

    /** Solo permitir 1 registro por tenant (query ya está “scoped” por tenancy) */
    public static function canCreate(): bool
    {
        return ! static::getEloquentQuery()->exists();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBrandSettings::route('/'),
            'create' => Pages\CreateBrandSetting::route('/create'),
            'edit'   => Pages\EditBrandSetting::route('/{record}/edit'),
        ];
    }
}
