<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SertifikatDummySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->error('❌ Tidak ada user');
            return;
        }

        $namaSertifikatPool = [
            'Web Developer',
            'Backend Developer',
            'Frontend Engineering',
            'Database Administrator',
            'Software Engineering',
            'Network Engineer',
            'Cisco Networking Basics',
            'Computer Network Fundamentals',
            'Data Science',
            'Machine Learning Fundamentals',
            'Artificial Intelligence',
            'Sistem Informasi Geografis',
            'Pengolahan Data Peta',
            'GIS Fundamentals',
            'Game Development',
            '3D Animation',
            'Game Programming',
            'Manajemen Teknologi Informasi',
            'Keamanan Informasi',
        ];

        $institusi = [
            'BNSP',
            'Dicoding',
            'Cisco Academy',
            'Coursera',
            'Google',
        ];

        $kategoriIds = DB::table('kategori')->pluck('id')->toArray();

        if (empty($kategoriIds)) {
            $this->command->error('❌ Tidak ada kategori di tabel kategori');
            return;
        }

        foreach ($users as $user) {
            DB::table('sertifikat')->insert([
                'user_id'           => $user->id,
                'nama_sertifikat'   => $namaSertifikatPool[array_rand($namaSertifikatPool)],
                'kategori_id'       => $kategoriIds[array_rand($kategoriIds)],
                'institusi_pemberi' => $institusi[array_rand($institusi)],
                'tahun_diperoleh'   => rand(2019, 2024),
                'file_path'         => 'sertifikat_dummy/user_' . $user->id . '.pdf',
                'status_verifikasi' => 'Disetujui',
            ]);
        }

        $this->command->info('✅ Sertifikat dummy berhasil dibuat dengan kategori terisi');
    }
}
