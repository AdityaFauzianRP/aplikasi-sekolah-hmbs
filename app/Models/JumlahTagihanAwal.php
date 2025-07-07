<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JumlahTagihanAwal extends Model
{
    protected $table = 'jumlah_tagihan_awal'; // optional jika nama tabel sudah benar

    protected $fillable = [
        'id',
        'jurusan_id',
        'ppdb_id',
        'total_tagihan',
        'jumlah_cicilan',
    ];

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function ppdb(): BelongsTo
    {
        return $this->belongsTo(Ppdb::class);
    }
}
