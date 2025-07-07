<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kurikulum extends Model
{
    use HasFactory;

    protected $table = 'kurikulums';

    protected $fillable = [
        'kode_kurikulum',
        'nama_kurikulum',
        'jenjang_pendidikan',
        'jurusan_id',
        'tahun_mulai',
        'tahun_selesai',
        'status',
    ];

    // Relasi ke Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function kurikulumMapelGuru()
    {
        return $this->hasMany(KurikulumMataPelajaranGuru::class);
    }
}
