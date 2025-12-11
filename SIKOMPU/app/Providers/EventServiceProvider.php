<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\Kategori;
use App\Models\MataKuliah;
use App\Observers\KategoriObserver;
use App\Observers\MataKuliahObserver;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Kategori::observe(KategoriObserver::class);
        MataKuliah::observe(MataKuliahObserver::class);
    }
}
