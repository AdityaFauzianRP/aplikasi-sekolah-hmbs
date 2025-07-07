<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGurusTable extends Migration
{
    public function up()
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nuptk')->nullable(); // Nomor Unik Pendidik dan Tenaga Kependidikan
            $table->string('nip')->nullable();   // Nomor Induk Pegawai
            $table->string('nik')->nullable();   // Nomor Induk Kependudukan
            $table->string('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('status_kepegawaian')->nullable(); // PNS, Honorer, dll
            $table->string('foto')->nullable(); // kalau ingin simpan foto
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gurus');
    }
}
