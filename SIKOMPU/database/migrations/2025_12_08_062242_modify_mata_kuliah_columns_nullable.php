<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->integer('sks')->nullable()->change();
            $table->string('sesi')->nullable()->change();
            $table->integer('semester')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->integer('sks')->nullable(false)->change();
            $table->string('sesi')->nullable(false)->change();
            $table->integer('semester')->nullable(false)->change();
        });
    }
};