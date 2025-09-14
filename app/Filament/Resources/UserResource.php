<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Password; // reglas de password

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon  = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $modelLabel      = 'Usuario';
    protected static ?string $pluralModelLabel= 'Usuarios';
    protected static ?int    $navigationSort  = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del usuario')
                ->description('Información básica de la cuenta.')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(64)
                        ->autocomplete('name'),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(128)
                        ->unique(ignoreRecord: true)
                        ->mutateDehydratedStateUsing(fn ($state) => $state ? mb_strtolower(trim($state)) : $state)
                        ->helperText('Será el usuario de acceso.')
                        ->disabled(fn (string $context) => $context === 'edit'),
                ])
                ->columns(2)
                ->icon('heroicon-o-identification'),

            Forms\Components\Section::make('Seguridad')
                ->description('Establece o cambia la contraseña.')
                ->schema([
                    // Toggle para edición: mostrar campos solo si desea cambiar
                    Forms\Components\Toggle::make('reset_password')
                        ->label('Cambiar contraseña')
                        ->visible(fn (string $context) => $context === 'edit')
                        ->live(),

                    Forms\Components\TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->revealable()
                        ->required(fn (string $context) => $context === 'create')
                        ->visible(fn (callable $get, string $context) => $context === 'create' || $get('reset_password'))
                        ->rule(Password::min(8)->letters()->mixedCase()->numbers())
                        ->confirmed()
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn ($state) => filled($state)),

                    Forms\Components\TextInput::make('password_confirmation')
                        ->label('Confirmar contraseña')
                        ->password()
                        ->revealable()
                        ->same('password')
                        ->visible(fn (callable $get, string $context) => $context === 'create' || $get('reset_password')),
                ])
                ->columns(2)
                ->icon('heroicon-o-lock-closed'),

            Forms\Components\Section::make('Accesos')
                ->description('Roles y tenants asignados al usuario.')
                ->schema([
                    Forms\Components\Select::make('roles')
                        ->label('Roles')
                        ->relationship('roles', 'name') // Spatie HasRoles
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->native(false)
                        ->helperText('Ej.: super_admin, tenant_admin, tenant_demo'),

                    Forms\Components\Select::make('tenants')
                        ->label('Tenants')
                        ->relationship('tenants', 'name') // pivot tenant_user
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->native(false)
                        ->helperText('Tenants a los que tendrá acceso'),
                ])
                ->columns(2)
                ->icon('heroicon-o-shield-check'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email copiado')
                    ->copyMessageDuration(1500),

                Tables\Columns\TagsColumn::make('roles.name')
                    ->label('Roles')
                    ->separator(', ')
                    ->limitList(3)
                    ->expandableLimitedList(),

                Tables\Columns\TagsColumn::make('tenants.name')
                    ->label('Tenants')
                    ->separator(', ')
                    ->limitList(2)
                    ->expandableLimitedList(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Rol')
                    ->relationship('roles', 'name')
                    ->multiple(),

                Tables\Filters\SelectFilter::make('tenants')
                    ->label('Tenant')
                    ->relationship('tenants', 'name')
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Eliminar seleccionados'),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
}
