<?php

namespace App\Filament\Tenant\Resources\ContactSettingResource\Pages;

use App\Filament\Tenant\Resources\ContactSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContactSetting extends CreateRecord
{
    protected static string $resource = ContactSettingResource::class;
}
