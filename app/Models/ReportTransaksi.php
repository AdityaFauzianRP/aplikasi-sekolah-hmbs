<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTransaksi extends Model
{
    use HasFactory;

    protected $table = 'report_transaksis';

    protected $fillable = [
        'nama',
        'nisn',
        'transaksi_id',
        'jenis_transaksi',
        'nominal',
        'biaya_pengembangan',
        'total_pembayaran',
        'status',
        'deskripsi',
    ];
}
