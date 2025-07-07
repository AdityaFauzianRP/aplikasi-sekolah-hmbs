<?php

namespace App\Filament\Resources\PpdbPaymentResource\Pages;

use App\Filament\Resources\PpdbPaymentResource;
use Filament\Resources\Pages\Page;
use App\Models\PpdbPayment;
use App\Models\PesertaDidik;
use App\Models\Ppdb;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Midtrans\Config;
use Midtrans\Snap;


class PaymentReceipt extends Page
{
    protected static string $resource = PpdbPaymentResource::class;

    protected static string $view = 'filament.resources.ppdb-payment-resource.pages.payment-receipt';

    public $payments;
    public $nomorRegistrasi;
    public $statusPpdb;
    public $biayaSekolah;
    public $biayaMitrans;
    public $biayaPengembangan;
    public $totalBiaya;
    public $namaPesertaDidik;
    public $judulPPDB;
    public $ppdbId;
    public $pesertaDidikId;

    public $snapToken;

    public function mount(): void
    {
        // Ambil user login sekarang
        $userId = Auth::id();

        // Ambil peserta_didik yang memiliki user_id sesuai login
        $peserta = PesertaDidik::where('user_id', $userId)->first();

        // Simpan nomor registrasi jika ditemukan
        $this->nomorRegistrasi = $peserta?->nomor_registrasi;

        $this->statusPpdb = $peserta?->status_ppdb;

        $ppdb = Ppdb::where('id', $peserta?->ppdb_id)->first();

        $this->biayaSekolah = $ppdb?->nominal;

        $this->biayaMitrans = 5000;

        $this->biayaPengembangan = 10000;

        $this->totalBiaya = $this->biayaSekolah + $this->biayaMitrans + $this->biayaPengembangan;

        $this->judulPPDB = $ppdb?->judul_ppdb;

        $this->namaPesertaDidik = $peserta?->nama_lengkap;

        $this->pesertaDidikId = $peserta?->id;

        $this->ppdbId = $peserta?->ppdb_id;

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
            'enabled_payments' => ['other_qris', 'bank_transfer'],

            'transaction_details' => [
                'order_id' => 'ORDER-' . time(), // Unik setiap transaksi
                'gross_amount' => $this->totalBiaya,
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
                    'id' => $this->nomorRegistrasi ?? 'REG-000',
                    'price' => $this->biayaSekolah,
                    'quantity' => 1,
                    'name' => 'Biaya Sekolah - ' . ($this->judulPPDB ?? 'PPDB')
                ],
                [
                    'id' => 'biaya-mitrans',
                    'price' => $this->biayaMitrans,
                    'quantity' => 1,
                    'name' => 'Biaya Layanan (Midtrans)'
                ],
                [
                    'id' => 'biaya-pengembangan',
                    'price' => $this->biayaPengembangan,
                    'quantity' => 1,
                    'name' => 'Biaya Pengembangan Sistem'
                ],
            ]
        ];


        // Dapatkan Snap Token
        $this->snapToken = Snap::getSnapToken($params);
    }
}
