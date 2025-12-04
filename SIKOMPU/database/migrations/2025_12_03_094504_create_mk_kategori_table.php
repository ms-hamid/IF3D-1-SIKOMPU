<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mk_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->unsignedTinyInteger('bobot')->default(1); // 1,3,5
            $table->timestamps();

            $table->unique(['mata_kuliah_id','kategori_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('mk_kategori');
    }
};
