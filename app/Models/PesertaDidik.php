<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaDidik extends Model
{
    use HasFactory;

    protected $table = 'peserta_didik';

    protected $fillable = [
        'ppdb_id',
        'jurusan_id',
        'nama_lengkap',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat_lengkap',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'hobi',
        'cita_cita',
        'no_hp',
        'email',
        'anak_ke',
        'jumlah_saudara_kandung',
        'jumlah_saudara_tiri',
        'jumlah_saudara_angkat',
        'status_tempat_tinggal',
        'jarak_rumah_km',
        'alat_transportasi',
        'waktu_tempuh_menit',
        'info_sekolah_dari',
        'asal_sekolah',
        'alamat_asal_sekolah',
        'rencana_setelah_lulus',
        'prestasi',
        'pelajaran_favorit',
        'password',
        'file_ktp',
        'file_ijazah',
        'nama_ayah',
        'status_ayah',
        'ttl_ayah',
        'no_ktp_ayah',
        'pendidikan_ayah',
        'alamat_ayah',
        'profesi_ayah',
        'pendapatan_ayah',
        'no_hp_ayah',
        'email_ayah',
        'nama_ibu',
        'status_ibu',
        'ttl_ibu',
        'no_ktp_ibu',
        'pendidikan_ibu',
        'alamat_ibu',
        'profesi_ibu',
        'pendapatan_ibu',
        'no_hp_ibu',
        'email_ibu',
        'nama_wali',
        'status_wali',
        'ttl_wali',
        'no_ktp_wali',
        'pendidikan_wali',
        'alamat_wali',
        'profesi_wali',
        'pendapatan_wali',
        'no_hp_wali',
        'email_wali',
        'status_ppdb',
        'user_id', // Pastikan user_id ditambahkan ke sini
        'nomor_registrasi', // Nomor registrasi
    ];

    public function ppdb()
    {
        return $this->belongsTo(Ppdb::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ppdbPayments()
    {
        return $this->hasMany(PpdbPayment::class);
    }

}
