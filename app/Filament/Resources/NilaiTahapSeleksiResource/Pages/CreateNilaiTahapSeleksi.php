<?php

namespace App\Filament\Resources\NilaiTahapSeleksiResource\Pages;

use App\Filament\Resources\NilaiTahapSeleksiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNilaiTahapSeleksi extends CreateRecord
{
    protected static string $resource = NilaiTahapSeleksiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
