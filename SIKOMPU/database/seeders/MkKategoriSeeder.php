<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataKuliah;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class MkKategoriSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil semua matkul
        $matkuls = MataKuliah::all();

        // 2. Ambil semua kategori
        $kategoris = Kategori::all();

        // 3. Loop dan isi tabel pivot mk_kategori
        foreach ($matkuls as $mk) {
            foreach ($kategoris as $kategori) {

                // Tentukan bobot otomatis (contoh: sesuai nama)
                $bobot = $this->tentukanBobot($mk->nama_mk, $kategori->nama);

                DB::table('mk_kategori')->updateOrInsert(
                    [
                        'mata_kuliah_id' => $mk->id,
                        'kategori_id' => $kategori->id,
                    ],
                    [
                        'bobot' => $bobot,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    // ---- LOGIKA BOOTSTRAPPING BOBOT OTOMATIS ----
    private function tentukanBobot($mk_name, $kategori_name)
    {
        $mk = strtolower($mk_name);
        $kat = strtolower($kategori_name);

        // contoh logika dasar
        if (str_contains($mk, 'web') && str_contains($kat, 'programming')) {
            return 5;
        }

        if (str_contains($mk, 'basis data') && str_contains($kat, 'database')) {
            return 5;
        }

        if (str_contains($mk, 'ai') && str_contains($kat, 'machine learning')) {
            return 5;
        }

        // default
        return 1;
    }
}

