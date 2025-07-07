<?php

namespace App\Http\Controllers;

use App\Models\PesertaDidik;
use App\Models\ReportTransaksi;
use App\Models\TagihanSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function bayar()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Data transaksi
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . time(),
                'gross_amount' => 100000,
            ],
            'customer_details' => [
                'first_name' => 'Andi',
                'email' => 'andi@example.com',
            ],
        ];

        // Generate Snap Token
        $snapToken = Snap::getSnapToken($params);

        // Kirim ke view
        return view('bayar', compact('snapToken'));
    }

    public function updateStatusPembayaran(Request $request)
    {
        DB::beginTransaction();

        try {
            Log::info('Pembayaran Success:', $request->all());

            $request->validate([
                'nama' => 'required|string',
                'nisn' => 'nullable|numeric',
                'nomor_transaksi' => 'required|string',
                'metode_pembayaran' => 'required|string',
                'jumlah' => 'required|numeric',
                'biaya_pengembangan' => 'required|numeric',
                'biaya_mitrans' => 'required|numeric',
                'status' => 'required|string',
            ]);

            $record_id = $request->input('record_id');
            $ppdbId = $request->input('ppdb_id');
            $biayaPengembangan = (int) $request->input('biaya_pengembangan');
            $biaya_mitrans = (int) $request->input('biaya_mitrans');
            $jumlah = (int) $request->input('jumlah');


            $totalBiayaPengembangan = $biayaPengembangan + $biaya_mitrans;
            $totalBiaya =  $jumlah - $totalBiayaPengembangan;

            TagihanSiswa::where('id', $record_id)
                ->update([
                    'status_bayar' => 'lunas',
                ]);

            ReportTransaksi::create([
                'nama' => $request->input('nama'),
                'nisn' => $request->input('nisn'),
                'transaksi_id' => $request->input('nomor_transaksi'),
                'jenis_transaksi' => $request->input('metode_pembayaran'),
                'nominal' => $totalBiaya,
                'biaya_pengembangan' => $totalBiayaPengembangan,
                'total_pembayaran' => $request->input('jumlah'),
                'status' => $request->input('status'),
                'deskripsi' => $request->input('deskripsi'),
            ]);

            $pesertaDidikId =  $request->input('peserta_didik_id');

            $pesertaDidikId = $request->input('peserta_didik_id');

            $tagihanBelumLunas = TagihanSiswa::where('peserta_didik_id', $pesertaDidikId)
                ->where('status_bayar', 'belum')
                ->exists();

            if ($tagihanBelumLunas) {
                Log::info("Masih ada yang belum lunas untuk peserta_didik_id: {$pesertaDidikId}");
            } else {
                Log::info("Sudah tidak ada yang belum lunas untuk peserta_didik_id: {$pesertaDidikId}");

                PesertaDidik::where('id', $pesertaDidikId)
                    ->update([
                        'status_ppdb' => 'Menunggu Mendapat Kelas'
                    ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Status pembayaran berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal update status pembayaran: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui status pembayaran.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
