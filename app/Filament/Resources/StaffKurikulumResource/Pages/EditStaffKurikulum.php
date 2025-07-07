<?php

namespace App\Filament\Resources\StaffKurikulumResource\Pages;

use App\Filament\Resources\StaffKurikulumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStaffKurikulum extends EditRecord
{
    protected static string $resource = StaffKurikulumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
