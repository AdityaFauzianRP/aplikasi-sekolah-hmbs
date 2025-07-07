<?php

namespace App\Filament\Resources\ReportTransaksiResource\Pages;

use App\Filament\Resources\ReportTransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportTransaksi extends EditRecord
{
    protected static string $resource = ReportTransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
