<?php

namespace App\Filament\Resources\PpdbPaymentResource\Pages;

use App\Filament\Resources\PpdbPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPpdbPayments extends ListRecords
{
    protected static string $resource = PpdbPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
