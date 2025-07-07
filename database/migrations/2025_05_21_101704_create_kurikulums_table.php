<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kurikulums', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kurikulum'); // contoh: 2013, K13, KMD
            $table->string('nama_kurikulum');
            $table->enum('jenjang_pendidikan', ['SD', 'SMP', 'SMA', 'SMK']);
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->nullOnDelete(); // Tambahan jurusan
            $table->string('tahun_mulai');
            $table->string('tahun_selesai')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kurikulums');
    }
};

