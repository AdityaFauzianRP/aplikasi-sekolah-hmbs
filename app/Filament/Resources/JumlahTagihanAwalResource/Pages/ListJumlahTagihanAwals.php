<?php

namespace App\Filament\Resources\JumlahTagihanAwalResource\Pages;

use App\Filament\Resources\JumlahTagihanAwalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJumlahTagihanAwals extends ListRecords
{
    protected static string $resource = JumlahTagihanAwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
