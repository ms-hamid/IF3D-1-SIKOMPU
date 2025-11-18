<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prodi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_prodi', 20)->unique();
            $table->string('nama_prodi', 255);
            $table->enum('jenjang', ['D3', 'D4', 'S2 Terapan']);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodi');
    }
};
