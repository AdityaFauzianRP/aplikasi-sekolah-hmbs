<?php

namespace App\Filament\Resources\PembayaranUangPangkalResource\Pages;

use App\Filament\Resources\PembayaranUangPangkalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembayaranUangPangkals extends ListRecords
{
    protected static string $resource = PembayaranUangPangkalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
