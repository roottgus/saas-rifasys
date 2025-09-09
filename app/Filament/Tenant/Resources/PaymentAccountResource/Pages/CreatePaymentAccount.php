<?php

namespace App\Filament\Tenant\Resources\PaymentAccountResource\Pages;

use App\Filament\Tenant\Resources\PaymentAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentAccount extends CreateRecord
{
    protected static string $resource = PaymentAccountResource::class;
}
