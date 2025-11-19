<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update ENUM jenjang untuk include S1 dan S2
        DB::statement("ALTER TABLE prodi MODIFY COLUMN jenjang ENUM('D3', 'D4', 'S1', 'S2', 'S2 Terapan') NOT NULL");
    }

    public function down(): void
    {
        // Rollback ke ENUM lama
        DB::statement("ALTER TABLE prodi MODIFY COLUMN jenjang ENUM('D3', 'D4', 'S2 Terapan') NOT NULL");
    }
};