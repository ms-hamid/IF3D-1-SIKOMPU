<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_id')->constrained('hasil_rekomendasi')->onDelete('cascade');
            $table->foreignId('matakuliah_id')->constrained('mata_kuliah')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // koordinator/pengampu
            $table->enum('peran_penugasan', ['koordinator', 'pengampu_teori', 'pengampu_praktikum']);
            $table->decimal('skor_dosen_di_mk', 8, 2); // skor total dosen untuk MK ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_rekomendasi');
    }
};
