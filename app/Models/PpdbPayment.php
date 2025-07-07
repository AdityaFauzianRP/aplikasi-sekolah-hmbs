<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbPayment extends Model
{
    use HasFactory;

    protected $table = 'ppdb_payments';

    protected $fillable = [
        'peserta_didik_id',
        'nomor_transaksi',
        'tanggal_bayar',
        'metode_pembayaran',
        'jumlah',
        'biaya_sekolah',
        'biaya_mitrans',
        'biaya_pengembangan',
        'bukti_pembayaran',
        'status',
        'ppdb_id',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah' => 'decimal:2',
        'biaya_sekolah' => 'decimal:2',
        'biaya_mitrans' => 'decimal:2',
        'biaya_pengembangan' => 'decimal:2',
    ];

    // Relasi ke PesertaDidik
    public function pesertaDidik()
    {
        return $this->belongsTo(PesertaDidik::class);
    }

    public function ppdb()
    {
        return $this->belongsTo(Ppdb::class);
    }
}
