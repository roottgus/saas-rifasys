<?php

namespace App\Filament\Tenant\Resources\HomeSettingResource\Pages;

use App\Filament\Tenant\Resources\HomeSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomeSettings extends ListRecords
{
    protected static string $resource = HomeSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
