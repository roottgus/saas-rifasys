<?php

namespace App\Filament\Tenant\Resources\LegalSettingResource\Pages;

use App\Filament\Tenant\Resources\LegalSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLegalSettings extends ListRecords
{
    protected static string $resource = LegalSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
