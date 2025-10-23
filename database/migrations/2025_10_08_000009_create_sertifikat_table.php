<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_sertifikat', 255);
            $table->string('institusi_pemberi', 255);
            $table->year('tahun_diperoleh');
            $table->string('file_path', 512);
            $table->enum('status_verifikasi', ['Menunggu', 'Disetujui', 'Ditolak']);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};
