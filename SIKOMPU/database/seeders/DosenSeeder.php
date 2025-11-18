<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data dengan DELETE, bukan TRUNCATE (karena ada foreign key)
        User::query()->delete();

        $users = [
            // STRUKTURAL
            [
                'nama_lengkap' => 'Dr. Bambang Suryanto, M.T.',
                'nidn' => '0001011970',
                'password' => '123456',
                'jabatan' => 'Kepala Jurusan',
                'prodi' => 'Teknik Informatika',
                'status' => 'Aktif',
                'beban_mengajar' => 8,
                'max_beban' => 12,
            ],
            [
                'nama_lengkap' => 'Dr. Siti Nurhaliza, S.T., M.Kom.',
                'nidn' => '0002021975',
                'password' => '123456',
                'jabatan' => 'Sekretaris Jurusan',
                'prodi' => 'Teknik Informatika',
                'status' => 'Aktif',
                'beban_mengajar' => 10,
                'max_beban' => 12,
            ],
            [
                'nama_lengkap' => 'Prof. Dr. Agus Wiranto, M.Sc.',
                'nidn' => '0003031968',
                'password' => '123456',
                'jabatan' => 'Kepala Program Studi',
                'prodi' => 'Teknik Informatika',
                'status' => 'Aktif',
                'beban_mengajar' => 9,
                'max_beban' => 12,
            ],
            [
                'nama_lengkap' => 'Dra. Rina Kusuma, M.T.',
                'nidn' => '0004041972',
                'password' => '123456',
                'jabatan' => 'Kepala Program Studi',
                'prodi' => 'Teknik Geomatika',
                'status' => 'Aktif',
                'beban_mengajar' => 11,
                'max_beban' => 12,
            ],
            [
                'nama_lengkap' => 'Dr. Hendrik Santoso, S.Kom., M.Kom.',
                'nidn' => '0005051980',
                'password' => '123456',
                'jabatan' => 'Kepala Program Studi',
                'prodi' => 'Rekayasa Keamanan Siber',
                'status' => 'Aktif',
                'beban_mengajar' => 10,
                'max_beban' => 12,
            ],
            [
                'nama_lengkap' => 'Dr. Yuliana Dewi, M.T.',
                'nidn' => '0006061978',
                'password' => '123456',
                'jabatan' => 'Kepala Program Studi',
                'prodi' => 'Animasi',
                'status' => 'Aktif',
                'beban_mengajar' => 8,
                'max_beban' => 12,
            ],

            // DOSEN
            [
                'nama_lengkap' => 'Dr. Ahmad Fauzi, M.T.',
                'nidn' => '0123456789',
                'password' => '123456',
                'jabatan' => 'Dosen',
                'prodi' => 'Teknik Informatika',
                'status' => 'Aktif',
                'beban_mengajar' => 14,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Sari Indah, M.Kom.',
                'nidn' => '0123456790',
                'password' => '123456',
                'jabatan' => 'Dosen',
                'prodi' => 'Teknik Informatika',
                'status' => 'Aktif',
                'beban_mengajar' => 16,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Prof. Dr. Maya Kusuma, M.Sc.',
                'nidn' => '0123456792',
                'password' => '123456',
                'jabatan' => 'Dosen',
                'prodi' => 'Teknik Informatika',
                'status' => 'Aktif',
                'beban_mengajar' => 16,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Dedi Prasetyo, S.Kom., M.T.',
                'nidn' => '0123456793',
                'password' => '123456',
                'jabatan' => 'Dosen',
                'prodi' => 'Teknik Geomatika',
                'status' => 'Aktif',
                'beban_mengajar' => 12,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Fitri Handayani, M.T.',
                'nidn' => '0123456794',
                'password' => '123456',
                'jabatan' => 'Dosen',
                'prodi' => 'Animasi',
                'status' => 'Aktif',
                'beban_mengajar' => 14,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Arif Budiman, S.T., M.Kom.',
                'nidn' => '0123456795',
                'password' => '123456',
                'jabatan' => 'Dosen',
                'prodi' => 'Teknologi Permainan',
                'status' => 'Aktif',
                'beban_mengajar' => 15,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Hendra Gunawan, M.Kom.',
                'nidn' => '0123456798',
                'password' => '123456',
                'jabatan' => 'Dosen',
                'prodi' => 'Teknik Rekayasa Perangkat Lunak',
                'status' => 'Aktif',
                'beban_mengajar' => 13,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Lia Permata, S.T., M.T.',
                'nidn' => '0123456799',
                'password' => '123456',
                'jabatan' => 'Dosen',
                'prodi' => 'Rekayasa Keamanan Siber',
                'status' => 'Aktif',
                'beban_mengajar' => 14,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Rizki Ramadhan, M.Kom.',
                'nidn' => '0123456800',
                'password' => '123456',
                'jabatan' => 'Dosen',
                'prodi' => 'Teknologi Rekayasa Multimedia',
                'status' => 'Aktif',
                'beban_mengajar' => 16,
                'max_beban' => 16,
            ],

            // LABORAN
            [
                'nama_lengkap' => 'Budi Santoso, M.T.',
                'nidn' => '0123456791',
                'password' => '123456',
                'jabatan' => 'Laboran',
                'prodi' => 'Teknik Informatika',
                'status' => 'Aktif',
                'beban_mengajar' => 12,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Dewi Lestari, S.T.',
                'nidn' => '0123456796',
                'password' => '123456',
                'jabatan' => 'Laboran',
                'prodi' => 'Teknologi Rekayasa Multimedia',
                'status' => 'Aktif',
                'beban_mengajar' => 10,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Rudi Hermawan, A.Md.',
                'nidn' => '0123456797',
                'password' => '123456',
                'jabatan' => 'Laboran',
                'prodi' => 'Teknik Rekayasa Perangkat Lunak',
                'status' => 'Aktif',
                'beban_mengajar' => 8,
                'max_beban' => 16,
            ],
            [
                'nama_lengkap' => 'Sinta Puspita, S.Kom.',
                'nidn' => '0123456801',
                'password' => '123456',
                'jabatan' => 'Laboran',
                'prodi' => 'Animasi',
                'status' => 'Aktif',
                'beban_mengajar' => 11,
                'max_beban' => 16,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('✅ Berhasil membuat ' . count($users) . ' user dengan password: 123456');
    }
}