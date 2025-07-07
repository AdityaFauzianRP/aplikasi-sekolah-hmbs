<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MataPelajaran extends Model
{
    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'jurusan_id',
        'jenjang_pendidikan',
        'semester',
    ];

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function kurikulumMapelGuru()
    {
        return $this->hasMany(KurikulumMataPelajaranGuru::class);
    }
}
