<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanSiswaTable extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_siswa', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nominal');
            $table->integer('cicilan_ke');
            $table->enum('status_bayar', ['belum', 'lunas'])->default('belum');
            
            $table->foreignId('peserta_didik_id')->constrained('peserta_didik')->onDelete('cascade');
            $table->foreignId('ppdb_id')->constrained('ppdb')->onDelete('cascade');
            $table->foreignId('jurusan_id')->constrained('jurusan')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_siswa');
    }
}
