<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\FaqItemResource\Pages;
use App\Models\FaqItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqItemResource extends Resource
{
    protected static ?string $model = FaqItem::class;
    protected static ?string $tenantOwnershipRelationshipName = 'tenant';

    protected static ?string $navigationIcon  = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = 'FAQ';
    protected static ?string $pluralLabel     = 'FAQ';
    protected static ?string $modelLabel      = 'FAQ';
    protected static ?int    $navigationSort  = 13;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('pregunta')
                ->label('Pregunta')
                ->required()
                ->maxLength(200),

            Forms\Components\RichEditor::make('respuesta')
                ->label('Respuesta')
                ->toolbarButtons(['bold','italic','strike','underline','link','orderedList','bulletList'])
                ->columnSpanFull()
                ->required(),

            Forms\Components\TextInput::make('orden')
                ->label('Orden')
                ->numeric()
                ->default(1),

            Forms\Components\Toggle::make('activo')
                ->label('Activo')
                ->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('orden')->label('Orden')->sortable(),
                Tables\Columns\TextColumn::make('pregunta')->label('Pregunta')->wrap()->searchable(),
                Tables\Columns\IconColumn::make('activo')->label('Activo')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Actualizado')->since(),
            ])
            ->defaultSort('orden')
            ->emptyStateHeading('Sin preguntas frecuentes')
            ->emptyStateDescription('Agrega tus preguntas y respuestas más comunes.')
            ->emptyStateActions([ Tables\Actions\CreateAction::make()->label('Agregar pregunta') ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFaqItems::route('/'),
            'create' => Pages\CreateFaqItem::route('/create'),
            'edit'   => Pages\EditFaqItem::route('/{record}/edit'),
        ];
    }
}
