<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->foreignId('kategori_id')
                ->nullable()
                ->constrained('kategori')
                ->nullOnDelete()
                ->after('tahun_diperoleh');
        });

        Schema::table('penelitian', function (Blueprint $table) {
            $table->foreignId('kategori_id')
                ->nullable()
                ->constrained('kategori')
                ->nullOnDelete()
                ->after('judul_penelitian'); // ✔ ini benar
        });
    }

    public function down(): void {
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_id');
        });

        Schema::table('penelitian', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_id');
        });
    }
};
