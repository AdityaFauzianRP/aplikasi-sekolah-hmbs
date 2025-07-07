<?php

namespace App\Filament\Resources\PembayaranUangPangkalResource\Pages;

use App\Filament\Resources\PembayaranUangPangkalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembayaranUangPangkal extends EditRecord
{
    protected static string $resource = PembayaranUangPangkalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
