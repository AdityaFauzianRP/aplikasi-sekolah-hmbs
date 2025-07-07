<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ppdb', function (Blueprint $table) {
            $table->integer('nominal')->default(0); // sesuaikan letaknya
        });
    }

    public function down()
    {
        Schema::table('ppdb', function (Blueprint $table) {
            $table->dropColumn('nominal');
        });
    }
};
