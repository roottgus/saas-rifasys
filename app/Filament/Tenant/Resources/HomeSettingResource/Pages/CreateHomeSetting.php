<?php

namespace App\Filament\Tenant\Resources\HomeSettingResource\Pages;

use App\Filament\Tenant\Resources\HomeSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHomeSetting extends CreateRecord
{
    protected static string $resource = HomeSettingResource::class;
}
