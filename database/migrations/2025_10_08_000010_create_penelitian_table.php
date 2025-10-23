<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penelitian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul_penelitian', 255);
            $table->year('tahun_publikasi');
            $table->enum('peran', ['Ketua', 'Anggota']);
            $table->string('link_publikasi', 255);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penelitian');
    }
};
