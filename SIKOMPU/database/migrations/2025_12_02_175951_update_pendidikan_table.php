<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pendidikan', function (Blueprint $table) {
            // Hapus kolom lama jika ada (memerlukan doctrine/dbal)
            if (Schema::hasColumn('pendidikan', 'tingkat')) {
                $table->dropColumn('tingkat');
            }

            // Tambah kolom baru
            $table->enum('jenjang', ['D3','D4','S1','S2','S3'])->after('user_id');
            $table->string('jurusan', 255)->after('jenjang');
            $table->string('universitas', 255)->after('jurusan');
            $table->year('tahun_lulus')->after('universitas');
            $table->softDeletes()->after('tahun_lulus');
        });
    }

    public function down(): void
    {
        Schema::table('pendidikan', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['jenjang', 'jurusan', 'universitas', 'tahun_lulus', 'deleted_at']);
            
            // Tambah kembali kolom lama
            $table->string('tingkat')->after('user_id');
        });
    }
};

