<?php

namespace App\Filament\Resources\LaporanPemasukanPpdbResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\PpdbPayment;

class PemasukanOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Jumlah', 'Rp. ' . number_format(PpdbPayment::sum('jumlah'), 0, ',', '.')),
            Stat::make('Total Biaya Midtrans', 'Rp. ' . number_format(PpdbPayment::sum('biaya_mitrans'), 0, ',', '.')),
            Stat::make('Total Biaya Pengembangan', 'Rp. ' . number_format(PpdbPayment::sum('biaya_pengembangan'), 0, ',', '.')),
        ];
    }
}
