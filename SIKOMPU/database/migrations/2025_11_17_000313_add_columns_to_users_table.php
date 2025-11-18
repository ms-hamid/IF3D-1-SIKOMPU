<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom prodi jika belum ada
            if (!Schema::hasColumn('users', 'prodi')) {
                $table->string('prodi')->nullable()->after('jabatan');
            }
            
            // Tambah kolom status jika belum ada
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif')->after('prodi');
            }
            
            // Tambah kolom foto jika belum ada (opsional)
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable()->after('status');
            }
            
            // Tambah kolom beban_mengajar jika belum ada (opsional)
            if (!Schema::hasColumn('users', 'beban_mengajar')) {
                $table->integer('beban_mengajar')->default(0)->after('foto');
            }
            
            // Tambah kolom max_beban jika belum ada (opsional)
            if (!Schema::hasColumn('users', 'max_beban')) {
                $table->integer('max_beban')->default(16)->after('beban_mengajar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['prodi', 'status', 'foto', 'beban_mengajar', 'max_beban']);
        });
    }
};