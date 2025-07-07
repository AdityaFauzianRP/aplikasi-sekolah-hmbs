<?php

namespace App\Filament\Resources\JumlahTagihanAwalResource\Pages;

use App\Filament\Resources\JumlahTagihanAwalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJumlahTagihanAwal extends EditRecord
{
    protected static string $resource = JumlahTagihanAwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
