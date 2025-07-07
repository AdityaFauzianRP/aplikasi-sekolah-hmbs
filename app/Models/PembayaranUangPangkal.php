<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranUangPangkal extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_uang_pangkal';

    protected $fillable = [
        'nama_pembayaran',
        'deskripsi',
        'nominal',
        'tahun_ajaran',
        'ppdb_id',
        'jurusan_id',
        'is_active',
    ];

    // Relasi ke tabel ppdb
    public function ppdb()
    {
        return $this->belongsTo(Ppdb::class);
    }

    // Relasi ke tabel jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
