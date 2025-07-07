<?php

namespace App\Filament\Resources\StaffKurikulumResource\Pages;

use App\Filament\Resources\StaffKurikulumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStaffKurikulums extends ListRecords
{
    protected static string $resource = StaffKurikulumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
