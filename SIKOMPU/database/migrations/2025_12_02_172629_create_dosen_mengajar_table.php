<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dosen_mengajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // dosen
            $table->foreignId('matakuliah_id')->constrained('mata_kuliah')->onDelete('cascade'); // matkul
            $table->float('skor_prediksi')->nullable(); // dari AI
            $table->integer('rank')->nullable(); // ranking AI
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dosen_mengajar');
    }
};
