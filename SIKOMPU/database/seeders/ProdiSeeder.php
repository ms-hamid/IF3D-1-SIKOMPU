<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProdiSeeder extends Seeder
{
    public function run()
    {
        $prodis = [
            ['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'D3'],
            ['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'D4'],
            ['kode_prodi' => 'RPL', 'nama_prodi' => 'Rekayasa Perangkat Lunak', 'jenjang' => 'D4'],
        ];

        foreach ($prodis as $prodi) {
            DB::table('prodi')->insert($prodi);
        }
    }
}
