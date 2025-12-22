<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('hasil_rekomendasi', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

public function down()
{
    Schema::table('hasil_rekomendasi', function (Blueprint $table) {
        $table->string('status')->default('Pending')->after('tahun_ajaran');
    });
}
};
