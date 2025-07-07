<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJumlahTagihanAwalTable extends Migration
{
    public function up(): void
    {
        Schema::create('jumlah_tagihan_awal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->constrained('jurusan')->onDelete('cascade');
            $table->foreignId('ppdb_id')->constrained('ppdb')->onDelete('cascade');
            $table->bigInteger('total_tagihan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jumlah_tagihan_awal');
    }
}
