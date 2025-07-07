<?php

namespace App\Filament\Resources\TagihanSiswaResource\Pages;

use App\Filament\Resources\TagihanSiswaResource;
use App\Models\Jurusan;
use App\Models\PembayaranUangPangkal;
use App\Models\PesertaDidik;
use App\Models\Ppdb;
use App\Models\TagihanSiswa;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class TagihanAwalSiswaBaru extends Page
{
    protected static string $resource = TagihanSiswaResource::class;

    protected static string $view = 'filament.resources.tagihan-siswa-resource.pages.tagihan-awal-siswa-baru';

    public $snapToken;

    public $statusPeserta;
    public $nomorRegistrasi;
    public $namaPesertaDidik;
    public $jurusanPeserta;
    public $nisn;
    public $jalurPeserta;
    public $deskJurusan;
    public $totalBiaya;
    public $dataPeserta;
    public $pesertaDidikId;
    public $jurusanId;
    public $ppdbId;
    public $biayaMitrans;
    public $biayaPengembangan;
    public $recordId;
    public $dataTagihan;
    public $tagihanId;
    public $statusBayarCicilan;
    public $tagihanSiswa;
    public $detailTagihan;


    public function mount($record = null): void
    {


        $this->recordId = $record;

        $userId = Auth::id();

        $peserta = PesertaDidik::where('user_id', $userId)->first();

        // Ambil model TagihanSiswa dari record ID
        $tagihan = TagihanSiswa::find($record);
        Log::info('Tagihan :' . $tagihan);

        $this->detailTagihan = $tagihan;

        $tagihanSiswaTable = TagihanSiswa::where('peserta_didik_id', $peserta?->id)
            ->get();

        Log::info('Tagihan Siswa :' . $tagihanSiswaTable);

        $this->dataTagihan = $tagihanSiswaTable;

        $this->statusBayarCicilan = $tagihan?->status_bayar;

        $this->tagihanId = $tagihan?->id;

        $this->biayaMitrans = 5000;

        $this->biayaPengembangan = 10000;

        $this->statusPeserta = $peserta?->status_ppdb;

        $this->nomorRegistrasi = $peserta?->nomor_registrasi;

        $ppdb = Ppdb::where('id', $peserta?->ppdb_id)->first();

        $jurusan = Jurusan::where('id', $peserta?->jurusan_id)->first();

        $uangPangkal = PembayaranUangPangkal::where('jurusan_id', $jurusan?->id)
            ->where('ppdb_id', $ppdb?->id)
            ->get();

        $this->totalBiaya = $uangPangkal->sum('nominal');

        $this->namaPesertaDidik = $peserta?->nama_lengkap;
        $this->jurusanPeserta = $jurusan?->nama;
        $this->deskJurusan = $jurusan?->deskripsi;
        $this->nisn = $peserta?->nisn;
        $this->jalurPeserta = $ppdb?->judul_ppdb;

        $this->dataPeserta = $uangPangkal;

        $this->pesertaDidikId = $peserta?->id;

        $this->jurusanId = $jurusan?->id;
        $this->ppdbId = $ppdb?->id;

        // Ambil tagihan berdasarkan pesertaDidikId jika ada

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Konfigurasi Snap Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Buat parameter transaksi
        $params = [
            'enabled_payments' => ['bank_transfer', 'other_qris'    ],
            'transaction_details' => [
                'order_id' => 'AWAL-' . time(), // Unik setiap transaksi
                'gross_amount' => $tagihan?->nominal,
            ],

            'customer_details' => [
                'first_name' => $this->namaPesertaDidik,
                'email' => $peserta?->email ?? 'default@email.com',
                'phone' => $peserta?->no_hp ?? '08123456789',
                'billing_address' => [
                    'first_name' => $this->namaPesertaDidik,
                    'address' => $peserta?->alamat_lengkap ?? 'Alamat belum diisi',
                    'phone' => $peserta?->no_hp ?? '08123456789',
                ],
            ],
            'item_details' => [
                [
                    'id' => 'TAGIHAN-UTAMA-' . $tagihan?->id . '-' . time(),
                    'price' => $tagihan?->nominal,
                    'quantity' => 1,
                    'name' => 'Pembayaran Uang Masuk Cicilah Ke ' . $tagihan?->cicilann_ke,
                ],
                [
                    'id' => 'PENGEMBANGAN-PEMBAYARAN-MASUK-' . $tagihan?->id . '-' . time(),
                    'price' => $this->biayaPengembangan,
                    'quantity' => 1,
                    'name' => 'Biaya Pengembangan',
                ],
                [
                    'id' => 'MITRANS-PEMBAYARAN-MASUK-' . $tagihan?->id . '-' . time(),
                    'price' => $this->biayaMitrans,
                    'quantity' => 1,
                    'name' => 'Biaya Mitrans',
                ],
            ]
        ];

        // Dapatkan Snap Token
        $this->snapToken = Snap::getSnapToken($params);
    }
}
