<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPpdbIdToPpdbPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('ppdb_payments', function (Blueprint $table) {
            $table->foreignId('ppdb_id')->after('peserta_didik_id')->constrained('ppdb')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('ppdb_payments', function (Blueprint $table) {
            $table->dropForeign(['ppdb_id']);
            $table->dropColumn('ppdb_id');
        });
    }
}
