<?php

namespace App\Observers;

use App\Models\Kategori;
use App\Models\MataKuliah;
use App\Services\RelevansiService;
use Illuminate\Support\Facades\DB;

class KategoriObserver
{
    public function created(Kategori $kategori): void
    {
        $service = new RelevansiService();

        foreach (MataKuliah::all() as $mk) {
            $bobot = $service->hitung($mk->nama_mk, $kategori->nama);

            DB::table('mk_kategori')->updateOrInsert(
                [
                    'mata_kuliah_id' => $mk->id,
                    'kategori_id' => $kategori->id,
                ],
                [
                    'bobot' => $bobot,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
