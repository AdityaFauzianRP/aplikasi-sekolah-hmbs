<?php

namespace App\Filament\Resources\AksesResource\Pages;

use App\Filament\Resources\AksesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAkses extends EditRecord
{
    protected static string $resource = AksesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
