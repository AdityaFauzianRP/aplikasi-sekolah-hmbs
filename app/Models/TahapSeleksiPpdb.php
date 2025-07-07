<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahapSeleksiPpdb extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi (tabel 'tahap_seleksi_ppdb')
    protected $table = 'tahap_seleksi_ppdb';

    // Tentukan kolom yang dapat diisi (fillable)
    protected $fillable = [
        'ppdb_id',
        'nama_tahap',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    // Definisikan relasi ke model Ppdb (one-to-many)
    public function ppdb()
    {
        return $this->belongsTo(Ppdb::class, 'ppdb_id');
    }

    public function nilai_tahap_seleksi()
    {
        return $this->hasMany(\App\Models\NilaiTahapSeleksi::class, 'tahap_seleksi_id');
    }
}
