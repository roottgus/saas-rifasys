<?php
// ============================================
// Ubicación: app/Filament/Widgets/RecentActivityWidget.php
// ============================================

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User; // Ajusta según tu modelo de participantes

class RecentActivityWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Actividad Reciente';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Ajusta esta query según tu modelo real
                User::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('avatar')
                        ->circular()
                        ->defaultImageUrl(fn ($record) => 
                            'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF'
                        )
                        ->grow(false),
                    
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight('bold')
                            ->searchable()
                            ->sortable(),
                        
                        Tables\Columns\TextColumn::make('email')
                            ->color('gray')
                            ->icon('heroicon-m-envelope')
                            ->iconPosition('before')
                            ->searchable(),
                    ])->space(1),
                    
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\BadgeColumn::make('email_verified_at')
                            ->label('Estado')
                            ->formatStateUsing(fn ($state) => $state ? 'Verificado' : 'Pendiente')
                            ->colors([
                                'success' => fn ($state): bool => $state !== null,
                                'warning' => fn ($state): bool => $state === null,
                            ])
                            ->icons([
                                'heroicon-o-check-circle' => fn ($state): bool => $state !== null,
                                'heroicon-o-clock' => fn ($state): bool => $state === null,
                            ]),
                        
                        Tables\Columns\TextColumn::make('created_at')
                            ->since()
                            ->color('gray')
                            ->size('sm'),
                    ])->alignment('end'),
                ])->from('md'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->button()
                    ->size('sm'),
            ])
            ->paginated(false)
            ->striped()
            ->poll('10s');
    }
}