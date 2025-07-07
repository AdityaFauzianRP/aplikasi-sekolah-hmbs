<?php

namespace App\Filament\Resources\PpdbPaymentResource\Pages;

use App\Filament\Resources\PpdbPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePpdbPayment extends CreateRecord
{
    protected static string $resource = PpdbPaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
