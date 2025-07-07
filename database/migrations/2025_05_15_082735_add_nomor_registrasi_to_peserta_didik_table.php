<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('peserta_didik', function (Blueprint $table) {
            $table->string('nomor_registrasi')->nullable(); // Ganti 'nama' dengan kolom yang sesuai urutan
        });
    }

    public function down(): void
    {
        Schema::table('peserta_didik', function (Blueprint $table) {
            $table->dropColumn('nomor_registrasi');
        });
    }
};
