<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KurikulumMataPelajaranGuru extends Model
{
    use HasFactory;

    protected $table = 'kurikulum_mata_pelajaran_guru';

    protected $fillable = [
        'kurikulum_id',
        'mata_pelajaran_id',
        'guru_id',
        'jam_pelajaran',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
