<?php

namespace App\Filament\Resources\PesertaDidikResource\Pages;

use App\Filament\Resources\PesertaDidikResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use App\Models\PesertaDidik;

class ListPesertaDidiks extends ListRecords
{
    protected static string $resource = PesertaDidikResource::class;

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return PesertaDidik::query()
            ->where('user_id', Auth::id()); // Mengambil user_id yang sama dengan ID user yang sedang login
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
