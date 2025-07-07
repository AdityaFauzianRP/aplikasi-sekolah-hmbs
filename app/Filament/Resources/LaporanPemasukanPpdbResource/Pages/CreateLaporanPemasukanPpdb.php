<?php

namespace App\Filament\Resources\LaporanPemasukanPpdbResource\Pages;

use App\Filament\Resources\LaporanPemasukanPpdbResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporanPemasukanPpdb extends CreateRecord
{
    protected static string $resource = LaporanPemasukanPpdbResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
