<?php

namespace App\Filament\Resources\PenilaianPesertaResource\Pages;

use App\Filament\Resources\PenilaianPesertaResource;
use App\Models\NilaiTahapSeleksi;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenilaianPeserta extends EditRecord
{
    protected static string $resource = PenilaianPesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['nilai_tahap_seleksi'])) {
            foreach ($data['nilai_tahap_seleksi'] as $item) {
                NilaiTahapSeleksi::where('id', $item['id'])->update([
                    'nilai' => $item['nilai'],
                ]);
            }
        }

        // Karena update sudah dilakukan manual, hapus dari data utama supaya tidak error
        unset($data['nilai_tahap_seleksi']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
