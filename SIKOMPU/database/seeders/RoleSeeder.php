<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
         $roles = [
            ['nama_peran' => 'dosen'],
            ['nama_peran' => 'laboran'],
            ['nama_peran' => 'kajur'],
            ['nama_peran' => 'kaprodi'],
            ['nama_peran' => 'sekjur'],
            ['nama_peran' => 'kps'],
        ];

        foreach ($roles as $role) {
            DB::table('role')->insert($role);
        }
    }
}
