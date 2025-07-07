<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiTahapSeleksi extends Model
{
    use HasFactory;

    protected $table = 'nilai_tahap_seleksi';

    protected $fillable = [
        'peserta_id',
        'tahap_seleksi_id',
        'nilai',
        'status_lulus',
        'keterangan',
    ];

    // Relasi ke Peserta (PesertaDidik)
    public function peserta()
    {
        return $this->belongsTo(PesertaDidik::class, 'peserta_id');
    }

    // Relasi ke Tahap Seleksi
    public function tahapSeleksi()
    {
        return $this->belongsTo(TahapSeleksiPpdb::class, 'tahap_seleksi_id');
    }
}

