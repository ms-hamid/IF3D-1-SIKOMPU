<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hasil_rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('tahun_ajaran', 9);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_rekomendasi');
    }
};
