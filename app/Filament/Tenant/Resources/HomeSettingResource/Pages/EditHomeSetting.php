<?php

namespace App\Filament\Tenant\Resources\HomeSettingResource\Pages;

use App\Filament\Tenant\Resources\HomeSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomeSetting extends EditRecord
{
    protected static string $resource = HomeSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
