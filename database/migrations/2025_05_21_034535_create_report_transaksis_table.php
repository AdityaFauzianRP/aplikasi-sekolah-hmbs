<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nisn')->nullable();
            $table->string('transaksi_id')->unique();
            $table->string('jenis_transaksi');
            $table->bigInteger('nominal');
            $table->bigInteger('biaya_pengembangan')->default(0);
            $table->bigInteger('total_pembayaran');
            $table->string('status');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_transaksis');
    }
};
