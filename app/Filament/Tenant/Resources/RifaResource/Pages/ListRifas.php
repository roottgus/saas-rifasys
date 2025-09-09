<?php

namespace App\Filament\Tenant\Resources\RifaResource\Pages;

use App\Filament\Tenant\Resources\RifaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRifas extends ListRecords
{
    protected static string $resource = RifaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
