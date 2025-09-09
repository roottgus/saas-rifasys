<?php

namespace App\Filament\Tenant\Resources\LegalSettingResource\Pages;

use App\Filament\Tenant\Resources\LegalSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLegalSetting extends EditRecord
{
    protected static string $resource = LegalSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
