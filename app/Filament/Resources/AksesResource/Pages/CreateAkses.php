<?php

namespace App\Filament\Resources\AksesResource\Pages;

use App\Filament\Resources\AksesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAkses extends CreateRecord
{
    protected static string $resource = AksesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
