<?php

namespace App\Filament\Resources\PpdbPaymentResource\Pages;

use App\Filament\Resources\PpdbPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPpdbPayment extends EditRecord
{
    protected static string $resource = PpdbPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
}
