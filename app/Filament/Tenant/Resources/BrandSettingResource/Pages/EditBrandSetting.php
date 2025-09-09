<?php

namespace App\Filament\Tenant\Resources\BrandSettingResource\Pages;

use App\Filament\Tenant\Resources\BrandSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrandSetting extends EditRecord
{
    protected static string $resource = BrandSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
