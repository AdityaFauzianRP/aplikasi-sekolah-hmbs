<?php

namespace App\Filament\Resources\LaporanPemasukanPpdbResource\Pages;

use App\Filament\Resources\LaporanPemasukanPpdbResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanPemasukanPpdb extends EditRecord
{
    protected static string $resource = LaporanPemasukanPpdbResource::class;

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
