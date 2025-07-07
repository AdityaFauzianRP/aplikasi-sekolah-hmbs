<?php

namespace App\Filament\Resources\AksesResource\Pages;

use App\Filament\Resources\AksesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAkses extends ListRecords
{
    protected static string $resource = AksesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
