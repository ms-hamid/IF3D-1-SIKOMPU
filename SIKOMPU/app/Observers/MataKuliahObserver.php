<?php

namespace App\Observers;

use App\Models\Kategori;
use App\Models\MataKuliah;
use App\Services\RelevansiService;
use Illuminate\Support\Facades\DB;

class MataKuliahObserver
{
    public function created(MataKuliah $mk): void
    {
        $this->syncKategori($mk);
    }

    public function updated(MataKuliah $mk): void
    {
        $this->syncKategori($mk);
    }

    private function syncKategori(MataKuliah $mk): void
    {
        $service = new RelevansiService();

        foreach (Kategori::all() as $kategori) {
            $bobot = $service->hitung(
                $mk->nama_mk,
                $kategori->nama
            );

            DB::table('mk_kategori')->updateOrInsert(
                [
                    'mata_kuliah_id' => $mk->id,
                    'kategori_id'    => $kategori->id,
                ],
                [
                    'bobot'      => $bobot,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
