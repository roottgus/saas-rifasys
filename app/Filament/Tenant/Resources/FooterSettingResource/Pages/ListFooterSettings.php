<?php

namespace App\Filament\Tenant\Resources\FooterSettingResource\Pages;

use App\Filament\Tenant\Resources\FooterSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFooterSettings extends ListRecords
{
    protected static string $resource = FooterSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
