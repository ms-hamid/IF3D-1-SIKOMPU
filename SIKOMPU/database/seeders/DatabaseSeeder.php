<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Dosen',
            'nidn' => '00000001',
            'email' => 'admin@example.com',
            'password' => Hash::make('dosen123'),
            'jabatan' => 'Dosen',
        ]);

        User::create([
            'nama' => 'Laboran',
            'nidn' => '00000002',
            'email' => 'laboran@example.com',
            'password' => Hash::make('laboran123'),
            'jabatan' => 'Laboran',
        ]);

        User::create([
            'nama' => 'Struktural',
            'nidn' => '00000003',
            'email' => 'struktur@example.com',
            'password' => Hash::make('struktur123'),
            'jabatan' => 'Struktural',
        ]);
    }
}
