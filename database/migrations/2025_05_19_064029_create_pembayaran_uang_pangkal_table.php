<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayaran_uang_pangkal', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pembayaran', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->string('tahun_ajaran', 20)->nullable();

            $table->unsignedBigInteger('ppdb_id')->nullable();
            $table->unsignedBigInteger('jurusan_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('ppdb_id')->references('id')->on('ppdb')->onDelete('set null');
            $table->foreign('jurusan_id')->references('id')->on('jurusan')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_uang_pangkal');
    }
};

