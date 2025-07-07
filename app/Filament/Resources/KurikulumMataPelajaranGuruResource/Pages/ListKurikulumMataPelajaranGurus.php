<?php

namespace App\Filament\Resources\KurikulumMataPelajaranGuruResource\Pages;

use App\Filament\Resources\KurikulumMataPelajaranGuruResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKurikulumMataPelajaranGurus extends ListRecords
{
    protected static string $resource = KurikulumMataPelajaranGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
