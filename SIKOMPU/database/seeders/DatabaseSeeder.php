<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil DosenSeeder untuk populate data users
        $this->call([
            DosenSeeder::class,
        ]);

        // Tambahkan seeder lain di sini jika ada
        // $this->call([
        //     ProdiSeeder::class,
        //     MatakuliahSeeder::class,
        // ]);
    }
}