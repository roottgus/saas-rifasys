<?php

namespace App\Filament\Tenant\Resources\RifaResource\Pages;

use App\Filament\Tenant\Resources\RifaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class CreateRifa extends CreateRecord
{
    protected static string $resource = RifaResource::class;

    protected function afterCreate(): void
    {
        // Generar números 1..N para la rifa recién creada
        Artisan::call('rifa:generate-numbers', [
            'rifa_id' => $this->record->id,
            '--force' => true,
        ]);

        Notification::make()
            ->title('Números generados')
            ->body('Se generaron los números del 1 al ' . $this->record->total_numeros . '.')
            ->success()
            ->send();
    }
}
