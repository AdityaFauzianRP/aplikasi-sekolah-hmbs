<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akses extends Model
{
    use HasFactory;

    protected $table = 'akses'; // Pastikan ini sesuai dengan nama tabel di migrasi

    protected $fillable = [
        'name', // contoh kolom: nama akses seperti "Admin", "Guru", dll.
        'deskripsi',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'akses_id');
    }
}
