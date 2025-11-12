<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            // ================================
            // 5 STRUKTURAL JURUSAN
            // ================================
            [
                'nama_lengkap' => 'Dr. Andi Saputra, M.Kom',
                'nidn' => '001234501',
                'password' => Hash::make('123456'),
                'jabatan' => 'Kepala Jurusan',
            ],
            [
                'nama_lengkap' => 'Dr. Rina Kurniawati, M.Kom',
                'nidn' => '001234502',
                'password' => Hash::make('123456'),
                'jabatan' => 'Sekretaris Jurusan',
            ],
            [
                'nama_lengkap' => 'Dr. Budi Santoso, M.T',
                'nidn' => '001234503',
                'password' => Hash::make('123456'),
                'jabatan' => 'Kepala Program Studi',
            ],
            [
                'nama_lengkap' => 'Ir. Sinta Mariani, M.T',
                'nidn' => '001234504',
                'password' => Hash::make('123456'),
                'jabatan' => 'Kepala Program Studi',
            ],
            [
                'nama_lengkap' => 'Dr. Yudi Kurniawan, M.Kom',
                'nidn' => '001234505',
                'password' => Hash::make('123456'),
                'jabatan' => 'Sekretaris Jurusan',
            ],

            // ================================
            // 5 DOSEN BIASA
            // ================================
            [
                'nama_lengkap' => 'Dewi Lestari, M.Kom',
                'nidn' => '001234506',
                'password' => Hash::make('123456'),
                'jabatan' => 'Dosen',
            ],
            [
                'nama_lengkap' => 'Ahmad Rifai, M.T',
                'nidn' => '001234507',
                'password' => Hash::make('123456'),
                'jabatan' => 'Dosen',
            ],
            [
                'nama_lengkap' => 'Fitriani Hapsari, M.Kom',
                'nidn' => '001234508',
                'password' => Hash::make('123456'),
                'jabatan' => 'Dosen',
            ],
            [
                'nama_lengkap' => 'Eko Prasetyo, M.Kom',
                'nidn' => '001234509',
                'password' => Hash::make('123456'),
                'jabatan' => 'Dosen',
            ],
            [
                'nama_lengkap' => 'Rani Amelia, M.T',
                'nidn' => '001234510',
                'password' => Hash::make('123456'),
                'jabatan' => 'Dosen',
            ],

            // ================================
            // 5 LABORAN (D3–S1)
            // ================================
            [
                'nama_lengkap' => 'Rahmat Hidayat, S.Kom',
                'nidn' => '001234511',
                'password' => Hash::make('123456'),
                'jabatan' => 'Laboran',
            ],
            [
                'nama_lengkap' => 'Nina Sari, D4-TI',
                'nidn' => '001234512',
                'password' => Hash::make('123456'),
                'jabatan' => 'Laboran',
            ],
            [
                'nama_lengkap' => 'Imam Setiawan, D3-TKJ',
                'nidn' => '001234513',
                'password' => Hash::make('123456'),
                'jabatan' => 'Laboran',
            ],
            [
                'nama_lengkap' => 'Lisa Anggraini, S.Kom',
                'nidn' => '001234514',
                'password' => Hash::make('123456'),
                'jabatan' => 'Laboran',
            ],
            [
                'nama_lengkap' => 'Bagus Saputra, D4-RPL',
                'nidn' => '001234515',
                'password' => Hash::make('123456'),
                'jabatan' => 'Laboran',
            ],
        ]);
    }
}
