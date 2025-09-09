<?php

namespace App\Filament\Tenant\Resources\FaqItemResource\Pages;

use App\Filament\Tenant\Resources\FaqItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFaqItem extends CreateRecord
{
    protected static string $resource = FaqItemResource::class;
}
