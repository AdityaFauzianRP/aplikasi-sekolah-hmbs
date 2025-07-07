<?php

namespace App\Filament\Resources\PpdbResource\Pages;

use App\Filament\Resources\PpdbResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use App\Models\Ppdb;
use App\Filament\Resources\PpdbResource\Widgets\NavigasiBar;

class DetailPpdb extends Page
{
    public Ppdb $record;

    protected static ?string $title = null;

    public function mount(Ppdb $record): void
    {
        $this->record = $record;
        static::$title = 'Detail PPDB' . ' - ' . ($this->record->judul_ppdb ?? '');
    }

    protected static string $resource = PpdbResource::class;

    protected static string $view = 'filament.resources.ppdb-resource.pages.detail-ppdb';

    // Removed duplicate mount method
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('judul_ppdb')
                ->label('Judul PPDB')
                ->default($this->record->judul_ppdb)
                ->required(),

            DatePicker::make('tanggal_mulai_ppdb')
                ->label('Tanggal Mulai PPDB')
                ->default($this->record->tanggal_mulai_ppdb)
                ->required(),

            DatePicker::make('tanggal_selesai_ppdb')
                ->label('Tanggal Selesai PPDB')
                ->default($this->record->tanggal_selesai_ppdb)
                ->required(),
        ];
    }

    public function save()
    {
        $this->record->update([
            'judul_ppdb' => $this->form->getState()['judul_ppdb'],
            'tanggal_mulai_ppdb' => $this->form->getState()['tanggal_mulai_ppdb'],
            'tanggal_selesai_ppdb' => $this->form->getState()['tanggal_selesai_ppdb'],
        ]);

        // Arahkan atau beri pesan sukses
        $this->redirect($this->resource::getUrl('index'));
    }
}
