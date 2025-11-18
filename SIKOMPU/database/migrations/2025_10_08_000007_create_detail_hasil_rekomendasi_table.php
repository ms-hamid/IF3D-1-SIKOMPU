<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_hasil_rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_id')->constrained('hasil_rekomendasi')->cascadeOnDelete();
            $table->foreignId('matakuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('peran_penugasan', ['Koordinator', 'Pengampu']);
            $table->decimal('skor_dosen_di_mk', 5, 2);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_hasil_rekomendasi');
    }
};
