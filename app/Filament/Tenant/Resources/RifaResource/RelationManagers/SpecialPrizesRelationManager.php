<?php

namespace App\Filament\Tenant\Resources\RifaResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class SpecialPrizesRelationManager extends RelationManager
{
    protected static string $relationship = 'specialPrizes';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $title = 'Premios especiales';
    protected static ?string $modelLabel = 'Premio especial';
    protected static ?string $pluralModelLabel = 'Premios especiales';
    protected static ?string $icon = 'heroicon-o-sparkles';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Premio especial')
                ->required()
                ->maxLength(255),

            Forms\Components\Group::make()->schema([
                Forms\Components\TextInput::make('lottery_name')
                    ->label('Lotería')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lottery_type')
                    ->label('Tipo (opcional)')
                    ->maxLength(255),
            ])->columns(2),

            Forms\Components\DateTimePicker::make('draw_at')
                ->label('Fecha/hora del sorteo')
                ->seconds(false)
                ->helperText('Si no indicas fecha aquí, se tomará la fecha fin de la rifa.'),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Premio')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('lottery_name')->label('Lotería')->searchable(),
                Tables\Columns\TextColumn::make('lottery_type')->label('Tipo')->toggleable(),
                Tables\Columns\TextColumn::make('draw_at')->label('Sorteo')->dateTime()->since()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->since()->label('Creado')->toggleable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Agregar premio'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
            ])
            ->defaultSort('id', 'desc');
    }
}
