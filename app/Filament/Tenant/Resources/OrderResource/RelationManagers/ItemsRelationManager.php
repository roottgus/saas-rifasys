<?php

namespace App\Filament\Tenant\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Ítems de la orden';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numero')
            ->columns([
                Tables\Columns\TextColumn::make('numero')->label('Número')->sortable(),
                Tables\Columns\TextColumn::make('price')->label('Precio')->prefix('$')->numeric(2),
                Tables\Columns\TextColumn::make('rifa.titulo')->label('Rifa')->wrap(),
            ])
            ->paginated(false)
            ->headerActions([])   // No crear desde aquí
            ->actions([])         // No editar/borrar ítems
            ->bulkActions([]);
    }
}
