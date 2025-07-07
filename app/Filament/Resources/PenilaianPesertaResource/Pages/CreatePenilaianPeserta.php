<?php

namespace App\Filament\Resources\PenilaianPesertaResource\Pages;

use App\Filament\Resources\PenilaianPesertaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenilaianPeserta extends CreateRecord
{
    protected static string $resource = PenilaianPesertaResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
