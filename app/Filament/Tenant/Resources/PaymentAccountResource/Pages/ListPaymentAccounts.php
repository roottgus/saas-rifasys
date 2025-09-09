<?php

namespace App\Filament\Tenant\Resources\PaymentAccountResource\Pages;

use App\Filament\Tenant\Resources\PaymentAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentAccounts extends ListRecords
{
    protected static string $resource = PaymentAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
