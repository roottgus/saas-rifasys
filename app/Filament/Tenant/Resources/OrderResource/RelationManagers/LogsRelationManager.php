<?php

namespace App\Filament\Tenant\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class LogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';
    protected static ?string $title = 'Bitácora';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('action')
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->since(),

                Tables\Columns\BadgeColumn::make('action')
                    ->label('Acción')
                    ->colors([
                        'info'     => 'reserved',
                        'warning'  => 'submitted',
                        'gray'     => 'info_updated',
                        'success'  => 'paid',
                        'secondary'=> 'cancelled',
                        'danger'   => 'expired',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('actor.name')
                    ->label('Actor')
                    ->placeholder('Sistema')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notas')
                    ->wrap()
                    ->limit(120)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('meta')
                    ->label('Meta')
                    ->formatStateUsing(fn ($state) =>
                        empty($state) ? '—' : json_encode($state, JSON_UNESCAPED_UNICODE)
                    )
                    ->limit(120)
                    ->tooltip(fn ($state) =>
                        is_array($state) ? json_encode($state, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : $state
                    )
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('action')->label('Acción')->options([
                    'reserved'     => 'reserved',
                    'submitted'    => 'submitted',
                    'info_updated' => 'info_updated',
                    'paid'         => 'paid',
                    'cancelled'    => 'cancelled',
                    'expired'      => 'expired',
                ]),
            ])
            ->headerActions([]) // solo lectura
            ->actions([])       // sin editar/borrar
            ->bulkActions([]);
    }
}
