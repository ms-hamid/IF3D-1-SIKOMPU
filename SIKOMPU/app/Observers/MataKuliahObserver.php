<?php

namespace App\Observers;

use App\Models\Kategori;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\DB;
use App\Services\RelevansiService;

class MataKuliahObserver
{
    public function created(MataKuliah $mk)
    {
        $this->updateKategori($mk);
    }

    public function updated(MataKuliah $mk)
    {
        $this->updateKategori($mk);
    }

    private function updateKategori(MataKuliah $mk)
    {
        $service = new RelevansiService();
        $kategoris = Kategori::all();

        foreach ($kategoris as $kategori) {
            $bobot = $service->hitung($mk->nama_mk, $kategori->nama);

            DB::table('mk_kategori')->updateOrInsert(
                [
                    'mata_kuliah_id' => $mk->id,
                    'kategori_id'   => $kategori->id,
                ],
                [
                    'bobot'      => $bobot,
                    'updated_at' => now(),
                ]
            );
        }
    }
}
