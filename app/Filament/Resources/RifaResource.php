<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RifaResource\Pages;
use App\Models\Rifa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RifaResource extends Resource
{
    protected static ?string $model = Rifa::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('🔒 Control Antifraude')
                ->description('Bloquea la edición de esta rifa para el cliente desde el panel administrativo. Útil para evitar fraudes o cambios posteriores al lanzamiento.')
                ->schema([
                    Forms\Components\Toggle::make('is_edit_locked')
                        ->label('Bloquear edición al cliente (antifraude)')
                        ->helperText('Si está activo, el cliente NO podrá modificar esta rifa desde su panel. Solo el admin podrá desbloquear.')
                ])
                ->icon('heroicon-m-lock-closed')
                ->columnSpanFull(),

            Forms\Components\Section::make('📄 Datos principales')
                ->schema([
                    Forms\Components\TextInput::make('titulo')
                        ->label('Título de la rifa')
                        ->required()
                        ->maxLength(120)
                        ->placeholder('Ej: Gran Rifa de la Casa Nueva 2024')
                        ->autofocus()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($set, $state) =>
                            $set('slug', Str::slug($state))
                        )
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('slug')
                        ->label('URL amigable (slug)')
                        ->prefix('rifas/')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Identificador único en la URL. Solo minúsculas y guiones, sin espacios.')
                        ->maxLength(120),

                    Forms\Components\Select::make('estado')
                        ->label('Estado de la rifa')
                        ->options([
                            'borrador'   => '📝 Borrador',
                            'activa'     => '✅ Activa',
                            'pausada'    => '⏸️ Pausada',
                            'finalizada' => '🏁 Finalizada',
                        ])
                        ->default('borrador')
                        ->required()
                        ->columnSpan(2),
                ])
                ->columns(3),

            Forms\Components\Section::make('ℹ️ Información (solo lectura)')
                ->visible(fn (?Rifa $record) => $record?->is_edit_locked)
                ->schema([
                    Forms\Components\Placeholder::make('edit_locked_info')
                        ->content('<b>🚫 Edición bloqueada por política antifraude.</b><br>Solo el administrador puede modificar o desbloquear esta rifa.<br>
                            <span style="color:#6b7280;">Para liberar la edición, desactiva el interruptor “Bloquear edición” y guarda los cambios.</span>')
                ])
                ->icon('heroicon-m-information-circle')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID')->color('gray'),
                Tables\Columns\TextColumn::make('titulo')->label('Título')->wrap()->searchable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug')->copyable(),
                Tables\Columns\IconColumn::make('is_edit_locked')
                    ->label('Antifraude')
                    ->boolean()
                    ->trueIcon('heroicon-m-lock-closed')
                    ->falseIcon('heroicon-m-lock-open')
                    ->color(fn ($state) => $state ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'borrador'   => 'Borrador',
                        'activa'     => 'Activa',
                        'pausada'    => 'Pausada',
                        'finalizada' => 'Finalizada',
                        default      => $state,
                    })
                    ->colors([
                        'borrador'   => 'gray',
                        'activa'     => 'success',
                        'pausada'    => 'warning',
                        'finalizada' => 'danger',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->date('d/m/Y')->label('Creada'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_edit_locked')
                    ->label('Antifraude')
                    ->trueLabel('Solo bloqueadas')
                    ->falseLabel('Solo desbloqueadas')
                    ->placeholder('Todas')
                    ->column('is_edit_locked'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('desbloquear')
                    ->label('Desbloquear')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->visible(fn ($record) => $record->is_edit_locked)
                    ->requiresConfirmation()
                    ->action(function (Rifa $record) {
                        $record->is_edit_locked = false;
                        $record->save();

                        \Filament\Notifications\Notification::make()
                            ->title('Antifraude desactivado')
                            ->success()
                            ->body('La rifa ahora está editable para el cliente.')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRifas::route('/'),
            'create' => Pages\CreateRifa::route('/create'),
            'edit'   => Pages\EditRifa::route('/{record}/edit'),
        ];
    }
}
