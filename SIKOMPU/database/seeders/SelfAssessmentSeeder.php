<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Matakuliah;
use App\Models\SelfAssessment;

class SelfAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosens = User::all();
        $matkuls = Matakuliah::all();

        foreach ($dosens as $dosen) {
            foreach ($matkuls as $mk) {

                // Skip jika sudah ada data
                $exists = SelfAssessment::where('user_id', $dosen->id)
                    ->where('matakuliah_id', $mk->id)
                    ->exists();
                if ($exists) continue;

                // Generate nilai 1-8
                $nilai = rand(1, 8);

                // Buat catatan dummy
                $catatanOptions = [
                    'Berpengalaman mengajar',
                    'Memerlukan pendampingan',
                    'Ahli di topik terkait',
                    'Baru mengajar',
                    'Kurikulum terbaru',
                ];
                $catatan = $catatanOptions[array_rand($catatanOptions)];

                SelfAssessment::create([
                    'user_id' => $dosen->id,
                    'matakuliah_id' => $mk->id,
                    'nilai' => $nilai,
                    'catatan' => $catatan,
                ]);
            }
        }

        $this->command->info('✅ Dummy Self Assessment berhasil dibuat (nilai 1-8) untuk semua dosen dan mata kuliah.');
    }
}
