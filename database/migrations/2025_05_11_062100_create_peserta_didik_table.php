<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peserta_didik', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ppdb_id');
            $table->unsignedBigInteger('jurusan_id');

            $table->string('nama_lengkap');
            $table->string('nisn', 50)->nullable();
            $table->string('nik', 50)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('agama', 50)->nullable();

            $table->text('alamat_lengkap')->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kabupaten', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kode_pos', 20)->nullable();

            $table->string('hobi')->nullable();
            $table->string('cita_cita')->nullable();

            $table->string('no_hp', 50)->nullable();
            $table->string('email')->nullable();

            $table->integer('anak_ke')->nullable();
            $table->integer('jumlah_saudara_kandung')->nullable();
            $table->integer('jumlah_saudara_tiri')->nullable();
            $table->integer('jumlah_saudara_angkat')->nullable();

            $table->string('status_tempat_tinggal', 100)->nullable();
            $table->decimal('jarak_rumah_km', 10, 2)->nullable();
            $table->string('alat_transportasi', 100)->nullable();
            $table->integer('waktu_tempuh_menit')->nullable();

            $table->string('info_sekolah_dari')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->text('alamat_asal_sekolah')->nullable();
            $table->string('rencana_setelah_lulus')->nullable();

            $table->text('prestasi')->nullable();
            $table->string('pelajaran_favorit')->nullable();

            $table->string('password')->nullable();
            $table->string('file_ktp')->nullable();
            $table->string('file_ijazah')->nullable();

            $table->string('nama_ayah')->nullable();
            $table->string('status_ayah', 50)->nullable();
            $table->date('ttl_ayah')->nullable();
            $table->string('no_ktp_ayah', 50)->nullable();
            $table->string('pendidikan_ayah', 100)->nullable();
            $table->text('alamat_ayah')->nullable();
            $table->string('profesi_ayah', 100)->nullable();
            $table->decimal('pendapatan_ayah', 15, 2)->nullable();
            $table->string('no_hp_ayah', 50)->nullable();
            $table->string('email_ayah')->nullable();

            $table->string('nama_ibu')->nullable();
            $table->string('status_ibu', 50)->nullable();
            $table->date('ttl_ibu')->nullable();
            $table->string('no_ktp_ibu', 50)->nullable();
            $table->string('pendidikan_ibu', 100)->nullable();
            $table->text('alamat_ibu')->nullable();
            $table->string('profesi_ibu', 100)->nullable();
            $table->decimal('pendapatan_ibu', 15, 2)->nullable();
            $table->string('no_hp_ibu', 50)->nullable();
            $table->string('email_ibu')->nullable();

            $table->string('nama_wali')->nullable();
            $table->string('status_wali', 50)->nullable();
            $table->date('ttl_wali')->nullable();
            $table->string('no_ktp_wali', 50)->nullable();
            $table->string('pendidikan_wali', 100)->nullable();
            $table->text('alamat_wali')->nullable();
            $table->string('profesi_wali', 100)->nullable();
            $table->decimal('pendapatan_wali', 15, 2)->nullable();
            $table->string('no_hp_wali', 50)->nullable();
            $table->string('email_wali')->nullable();

            $table->string('status_ppdb', 100)->nullable();

            $table->timestamps();

            $table->foreign('ppdb_id')->references('id')->on('ppdb')->onDelete('cascade');
            $table->foreign('jurusan_id')->references('id')->on('jurusan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_didik');
    }
};
