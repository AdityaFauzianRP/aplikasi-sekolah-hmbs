<?php

namespace App\Filament\Resources\NilaiTahapSeleksiResource\Pages;

use App\Filament\Resources\NilaiTahapSeleksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNilaiTahapSeleksis extends ListRecords
{
    protected static string $resource = NilaiTahapSeleksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
