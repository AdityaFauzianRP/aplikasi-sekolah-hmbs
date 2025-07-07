<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nilai_tahap_seleksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peserta_id');
            $table->unsignedBigInteger('tahap_seleksi_id');
            $table->decimal('nilai', 5, 2)->nullable(); // Nilai bisa desimal, nullable jika belum dinilai
            $table->enum('status_lulus', ['LULUS', 'TIDAK LULUS', 'TAHAP PENILAIAN'])->default('TAHAP PENILAIAN');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('peserta_id')->references('id')->on('peserta_didik')->onDelete('cascade');
            $table->foreign('tahap_seleksi_id')->references('id')->on('tahap_seleksi_ppdb')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_tahap_seleksi');
    }
};
