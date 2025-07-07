<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppdb extends Model
{
    use HasFactory;

    protected $table = 'ppdb';

    protected $fillable = [
        'judul_ppdb',
        'kuota_peserta_ppdb',
        'tanggal_mulai_ppdb',
        'tanggal_selesai_ppdb',
    ];

    protected $casts = [
        'tanggal_mulai_ppdb' => 'date',
        'tanggal_selesai_ppdb' => 'date',
    ];

    public function tahapSeleksi()
    {
        return $this->hasMany(TahapSeleksiPpdb::class, 'ppdb_id');
    }
}
