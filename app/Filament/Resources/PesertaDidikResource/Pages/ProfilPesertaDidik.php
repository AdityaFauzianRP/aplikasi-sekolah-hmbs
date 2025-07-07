<?php

namespace App\Filament\Resources\PesertaDidikResource\Pages;

use App\Filament\Resources\PesertaDidikResource;
use Filament\Resources\Pages\Page;
use App\Models\PesertaDidik;
use Illuminate\Support\Facades\Auth;

class ProfilPesertaDidik extends Page
{
    protected static string $resource = PesertaDidikResource::class;
    protected static string $view = 'filament.resources.peserta-didik-resource.pages.profil-peserta-didik';

    public $peserta;

    public function mount(): void
    {
        $this->peserta = PesertaDidik::where('user_id', Auth::id())->first(); // ganti sesuai kebutuhan
    }
}
