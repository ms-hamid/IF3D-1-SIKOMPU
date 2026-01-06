<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MataKuliah;
use App\Models\Kategori;
use App\Services\RelevansiService;

class SyncMkKategori extends Command
{
    protected $signature = 'sync:mk-kategori';
    protected $description = 'Generate pivot mk_kategori otomatis';

    public function handle()
    {
        $service = new RelevansiService();

        foreach (MataKuliah::all() as $mk) {
            foreach (Kategori::all() as $kat) {
                $mk->kategori()->syncWithoutDetaching([
                    $kat->id => [
                        'bobot' => $service->hitung($mk->nama_mk, $kat->nama)
                    ]
                ]);
            }
        }

        $this->info('Pivot mk_kategori berhasil di-generate');
    }
}
