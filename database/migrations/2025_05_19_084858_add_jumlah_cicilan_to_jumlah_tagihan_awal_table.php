<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJumlahCicilanToJumlahTagihanAwalTable extends Migration
{
    public function up(): void
    {
        Schema::table('jumlah_tagihan_awal', function (Blueprint $table) {
            $table->integer('jumlah_cicilan')->default(1)->after('total_tagihan');
            // default 1 supaya kalau tidak ada cicilan dianggap lunas 1x bayar
        });
    }

    public function down(): void
    {
        Schema::table('jumlah_tagihan_awal', function (Blueprint $table) {
            $table->dropColumn('jumlah_cicilan');
        });
    }
}
