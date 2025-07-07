<?php

namespace App\Filament\Resources\PenilaianPesertaResource\Pages;

use App\Filament\Resources\PenilaianPesertaResource;
use App\Models\NilaiTahapSeleksi;
use App\Models\PesertaDidik;
use App\Models\TahapSeleksiPpdb;
use Filament\Resources\Pages\Page;

class RekapNilaiPserta extends Page
{
    protected static string $resource = PenilaianPesertaResource::class;

    protected static string $view = 'filament.resources.penilaian-peserta-resource.pages.rekap-nilai-pserta';

    public $rekapData;
    public $tahapList;

    public function mount()
    {
        // Ambil semua nama tahap seleksi berdasarkan urutan
        $tahapList = TahapSeleksiPpdb::orderBy('id')->pluck('nama_tahap', 'id')->toArray();

        // Ambil semua nilai tahap seleksi dengan relasi peserta dan tahap
        $penilaian = NilaiTahapSeleksi::with(['peserta', 'tahapSeleksi'])->get();

        // Kelompokkan berdasarkan peserta
        $grouped = $penilaian->groupBy('peserta_id')->map(function ($items) use ($tahapList) {
            $nilaiPerTahap = [];

            foreach ($tahapList as $tahapId => $namaTahap) {
                $nilai = $items->firstWhere('tahap_seleksi_id', $tahapId)?->nilai;
                $nilaiPerTahap[$namaTahap] = $nilai ?? '-';
            }

            $nilaiNumerik = $items->pluck('nilai')->filter(fn ($v) => is_numeric($v));
            $rataRata = $nilaiNumerik->count() > 0 ? round($nilaiNumerik->avg(), 2) : '-';

            return [
                'nama' => $items->first()?->peserta?->nama_lengkap ?? 'Tidak diketahui',
                'nilai_per_tahap' => $nilaiPerTahap,
                'rata_rata' => $rataRata,
            ];
        });

        $this->rekapData = $grouped;
        $this->tahapList = array_values($tahapList); // hanya nama tahap untuk header tabel
    }
}
