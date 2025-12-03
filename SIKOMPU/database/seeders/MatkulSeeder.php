<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Prodi;

class MatkulSeeder extends Seeder
{
    public function run()
    {
        // Ambil data prodi
        $prodiIF = Prodi::where('kode_prodi', 'IF')->first();
        $prodiTI = Prodi::where('kode_prodi', 'TI')->first();
        $prodiSI = Prodi::where('kode_prodi', 'SI')->first();
        $prodiRPL = Prodi::where('kode_prodi', 'RPL')->first();

        if (!$prodiIF || !$prodiTI || !$prodiSI || !$prodiRPL) {
            $this->command->error("Pastikan data prodi sudah ada di tabel `prodi`!");
            return;
        }

        $matkuls = [
            ['kode_mk' => 'IF101', 'nama_mk' => 'Matematika Dasar', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 3],
            ['kode_mk' => 'IF102', 'nama_mk' => 'Fisika Dasar', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 3],
            ['kode_mk' => 'IF103', 'nama_mk' => 'Kimia Dasar', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 3],
            ['kode_mk' => 'IF201', 'nama_mk' => 'Algoritma & Pemrograman', 'prodi_id' => $prodiIF->id, 'semester' => 'Genap', 'sesi' => 2, 'sks' => 4],
            ['kode_mk' => 'IF202', 'nama_mk' => 'Struktur Data', 'prodi_id' => $prodiIF->id, 'semester' => 'Genap', 'sesi' => 2, 'sks' => 3],
            ['kode_mk' => 'IF203', 'nama_mk' => 'Basis Data', 'prodi_id' => $prodiIF->id, 'semester' => 'Genap', 'sesi' => 2, 'sks' => 3],
            ['kode_mk' => 'IF301', 'nama_mk' => 'Sistem Operasi', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 3],
            ['kode_mk' => 'IF302', 'nama_mk' => 'Jaringan Komputer', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 3],
            ['kode_mk' => 'IF303', 'nama_mk' => 'Pemrograman Web', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 3],
        ];


        DB::table('mata_kuliah')->insert($matkuls);

        $this->command->info("Seeder mata kuliah berhasil dijalankan tanpa timestamps!");
    }
}
