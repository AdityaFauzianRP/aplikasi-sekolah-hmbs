<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus'; // Pastikan ini sama dengan nama tabel di database

    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'nuptk',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'email',
        'telepon',
        'pendidikan_terakhir',
        'status_kepegawaian',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kurikulumMapelGuru()
    {
        return $this->hasMany(KurikulumMataPelajaranGuru::class);
    }
}
