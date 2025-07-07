<?php

namespace App\Filament\Resources\PenilaianPesertaResource\Pages;

use App\Filament\Resources\PenilaianPesertaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenilaianPesertas extends ListRecords
{
    protected static string $resource = PenilaianPesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
