<?php

namespace App\Http\Controllers;

use App\Models\NilaiTahapSeleksi;
use Illuminate\Http\Request;
use App\Models\PpdbPayment;
use App\Models\PesertaDidik;
use App\Models\Ppdb;
use App\Models\TahapSeleksiPpdb;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input jika diperlukan
        $data = $request->validate([
            'peserta_didik_id' => 'required|integer',
            'ppdb_id' => 'required|integer',
            'nomor_transaksi' => 'required|string',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'jumlah' => 'required|numeric',
            'biaya_sekolah' => 'nullable|numeric',
            'biaya_mitrans' => 'nullable|numeric',
            'biaya_pengembangan' => 'nullable|numeric',
            'bukti_pembayaran' => 'nullable|string',
            'status' => 'required|string',
        ]);

        PpdbPayment::create($data);

        PesertaDidik::where('id', $data['peserta_didik_id'])
            ->update(['status_ppdb' => 'Peserta Aktif']);

        $peserta = PesertaDidik::find($data['peserta_didik_id']);
        $ppdbId = $peserta->ppdb_id;

        $tahapSeleksi = TahapSeleksiPpdb::where('ppdb_id', $ppdbId);

        foreach ($tahapSeleksi->get() as $tahap) {
            $nilai = new NilaiTahapSeleksi();
            $nilai->peserta_id = $data['peserta_didik_id'];
            $nilai->tahap_seleksi_id = $tahap->id;
            $nilai->status_lulus = 'TAHAP PENILAIAN';
            $nilai->nilai = 0;
            $nilai->keterangan = '';
            $nilai->save();
        }

        return response()->json(['message' => 'Pembayaran berhasil disimpan'], 200);
    }
}
