<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Prodi;

class MatkulSeeder extends Seeder
{
    public function run()
    {
        $prodiIF = Prodi::where('kode_prodi', 'IF')->first();

        if (!$prodiIF) {
            $this->command->error("Pastikan data prodi IF sudah ada di tabel `prodi`!");
            return;
        }

        $matkuls = [

            // Semester 1 (Ganjil) - Sesi 1
            ['kode_mk' => 'IF101', 'nama_mk' => 'Pengantar Proyek Perangkat Lunak', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 3],
            
            ['kode_mk' => 'IF102', 'nama_mk' => 'Pengantar Teknologi Informasi', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 3],
            ['kode_mk' => 'IF102P', 'nama_mk' => 'Pengantar Teknologi Informasi (Praktikum)', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 1],

            ['kode_mk' => 'IF103', 'nama_mk' => 'Dasar Pemrograman Web', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 3],
            ['kode_mk' => 'IF103P', 'nama_mk' => 'Dasar Pemrograman Web (Praktikum)', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 1],

            ['kode_mk' => 'IF104', 'nama_mk' => 'Dasar Pemrograman', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 3],
            ['kode_mk' => 'IF104P', 'nama_mk' => 'Dasar Pemrograman (Praktikum)', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 1],

            ['kode_mk' => 'IF105', 'nama_mk' => 'Sistem Komputer', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 3],
            ['kode_mk' => 'IF105P', 'nama_mk' => 'Sistem Komputer (Praktikum)', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 1],

            ['kode_mk' => 'IF106', 'nama_mk' => 'Matematika', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 3],

            ['kode_mk' => 'PK2IF', 'nama_mk' => 'Pendidikan Pancasila', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 1, 'sks' => 2],


            // Semester 3 (Ganjil) - Sesi 3
            ['kode_mk' => 'IF314', 'nama_mk' => 'Proyek Inovasi Agile', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 3],

            ['kode_mk' => 'IF315', 'nama_mk' => 'Rekayasa Perangkat Lunak Lanjut', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 3],
            ['kode_mk' => 'IF315P', 'nama_mk' => 'Rekayasa Perangkat Lunak Lanjut (Praktikum)', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 1],

            ['kode_mk' => 'IF316', 'nama_mk' => 'Mata Kuliah Pilihan 1', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 3],
            ['kode_mk' => 'IF316P', 'nama_mk' => 'Mata Kuliah Pilihan 1 (Praktikum)', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 1],

            ['kode_mk' => 'IF317', 'nama_mk' => 'Interaksi Manusia Komputer', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 3],
            ['kode_mk' => 'IF317P', 'nama_mk' => 'Interaksi Manusia Komputer (Praktikum)', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 1],

            ['kode_mk' => 'IF318', 'nama_mk' => 'Statistika', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 3],

            ['kode_mk' => 'PK1IF', 'nama_mk' => 'Pendidikan Agama', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 2],
            ['kode_mk' => 'PK3IF', 'nama_mk' => 'Pendidikan Kewarganegaraan', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 3, 'sks' => 2],


            // Semester 5 (Ganjil) - Sesi 5
            ['kode_mk' => 'IF525', 'nama_mk' => 'Proposal Proyek Akhir', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 5, 'sks' => 3],

            ['kode_mk' => 'IF628', 'nama_mk' => 'Magang Industri', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 5, 'sks' => 4],

            ['kode_mk' => 'IF629', 'nama_mk' => 'Etika Profesi Dunia Kerja', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 5, 'sks' => 2],

            ['kode_mk' => 'IF630', 'nama_mk' => 'Pelaporan Kerja', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 5, 'sks' => 2],

            ['kode_mk' => 'MB2IF', 'nama_mk' => 'Studi Independen', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 5, 'sks' => 3],

            ['kode_mk' => 'MB4IF', 'nama_mk' => 'Proyek Kemanusiaan', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 5, 'sks' => 3],

            ['kode_mk' => 'MB6IF', 'nama_mk' => 'Asistensi Mengajar', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 5, 'sks' => 3],

            ['kode_mk' => 'MB8IF', 'nama_mk' => 'Pertukaran Mahasiswa Merdeka', 'prodi_id' => $prodiIF->id, 'semester' => 'Ganjil', 'sesi' => 5, 'sks' => 3],
        ];

        DB::table('mata_kuliah')->insert($matkuls);

        $this->command->info("Seeder mata kuliah berhasil diupdate!");
    }
}
