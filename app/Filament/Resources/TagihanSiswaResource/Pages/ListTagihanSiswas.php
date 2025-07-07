<?php

namespace App\Filament\Resources\TagihanSiswaResource\Pages;

use App\Filament\Resources\TagihanSiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTagihanSiswas extends ListRecords
{
    protected static string $resource = TagihanSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
