<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    // Tentukan nama tabel
    protected $table = 'jurusan';

    // Tentukan kolom yang dapat diisi massal
    protected $fillable = ['nama', 'keterangan'];

    // Tentukan kolom yang tidak boleh diisi secara massal (secara default, ID dan timestamps diabaikan)
    protected $guarded = ['id'];

    // Jika Anda ingin menggunakan timestamps dengan nama custom (bukan created_at dan updated_at)
    // protected $timestamps = true; // defaultnya true
}
