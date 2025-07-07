<?php

namespace App\Filament\Resources\KurikulumMataPelajaranGuruResource\Pages;

use App\Filament\Resources\KurikulumMataPelajaranGuruResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKurikulumMataPelajaranGuru extends EditRecord
{
    protected static string $resource = KurikulumMataPelajaranGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
