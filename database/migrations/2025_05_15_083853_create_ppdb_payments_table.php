<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ppdb_payments', function (Blueprint $table) {
            $table->id();

            // Definisikan foreign key dengan cara manual untuk menghindari masalah tipe data
            $table->unsignedBigInteger('peserta_didik_id');
            $table->foreign('peserta_didik_id')->references('id')->on('peserta_didik')->onDelete('cascade');

            $table->string('nomor_transaksi')->unique();
            $table->dateTime('tanggal_bayar');
            $table->string('metode_pembayaran');
            $table->decimal('jumlah', 12, 2);
            $table->decimal('biaya_sekolah', 12, 2)->default(0);
            $table->decimal('biaya_mitrans', 12, 2)->default(0);
            $table->decimal('biaya_pengembangan', 12, 2)->default(0);
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['Menunggu Pembayaran', 'Pembayaran Sukses'])->default('Menunggu Pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_payments');
    }
};
