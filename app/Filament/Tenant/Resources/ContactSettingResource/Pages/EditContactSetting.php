<?php

namespace App\Filament\Tenant\Resources\ContactSettingResource\Pages;

use App\Filament\Tenant\Resources\ContactSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactSetting extends EditRecord
{
    protected static string $resource = ContactSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
