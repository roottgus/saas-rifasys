<?php

// ============================================
// Widget 3: RecentSalesWidget.php
// Ubicación: app/Filament/Tenant/Widgets/RecentSalesWidget.php
// ============================================

namespace App\Filament\Tenant\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\User; // Ajusta según tu modelo de ventas

class RecentSalesWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = [
        'lg' => 2,
        'xl' => 3,
    ];
    
    protected static ?string $heading = 'Ventas Recientes';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Ajusta esta query según tu modelo real de ventas
                User::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->weight('bold')
                        ->size('sm'),
                    Tables\Columns\TextColumn::make('email')
                        ->color('gray')
                        ->size('xs'),
                ])->space(1),
                
                Tables\Columns\BadgeColumn::make('amount')
                    ->label('Monto')
                    ->money('USD')
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->since()
                    ->color('gray')
                    ->size('xs'),
            ])
            ->paginated(false)
            ->poll('30s');
    }
}