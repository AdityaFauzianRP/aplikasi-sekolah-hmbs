<?php

namespace App\Filament\Resources\ReportTransaksiResource\Pages;

use App\Filament\Resources\ReportTransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportTransaksis extends ListRecords
{
    protected static string $resource = ReportTransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
