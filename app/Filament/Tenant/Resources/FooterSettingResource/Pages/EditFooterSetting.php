<?php

namespace App\Filament\Tenant\Resources\FooterSettingResource\Pages;

use App\Filament\Tenant\Resources\FooterSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFooterSetting extends EditRecord
{
    protected static string $resource = FooterSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
