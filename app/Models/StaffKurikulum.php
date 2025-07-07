<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffKurikulum extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'email',
        'telepon',
        'alamat',
        'pendidikan_terakhir',
        'status_kepegawaian',
        'foto',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
