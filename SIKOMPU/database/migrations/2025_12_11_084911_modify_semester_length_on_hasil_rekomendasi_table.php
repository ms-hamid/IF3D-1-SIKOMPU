<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hasil_rekomendasi', function (Blueprint $table) {
            $table->string('semester', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('hasil_rekomendasi', function (Blueprint $table) {
            $table->string('semester', 10)->change(); // sesuaikan jika sebelumnya 10
        });
    }
};
