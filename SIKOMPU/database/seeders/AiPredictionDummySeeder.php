<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiPrediction;
use App\Models\User;

class AiPredictionDummySeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user berdasarkan jabatan (kolom jabatan, bukan role)
        $dosens = User::whereIn('jabatan', ['Dosen', 'Laboran'])->get();
        
        if ($dosens->isEmpty()) {
            $this->command->warn('Tidak ada user dengan jabatan Dosen/Laboran!');
            $this->command->info('Mengambil semua user...');
            $dosens = User::all();
        }
        
        if ($dosens->isEmpty()) {
            $this->command->error('Tidak ada user di database!');
            return;
        }

        $this->command->info("Ditemukan {$dosens->count()} user");

        // Data dummy untuk confusion matrix yang bagus
        // TP = 45, FN = 5, FP = 3, TN = 47
        
        $predictions = [];
        $dosenArray = $dosens->toArray(); // Convert ke array
        
        // True Positive: 45 (Prediksi: diterima, Aktual: diterima)
        for ($i = 0; $i < 45; $i++) {
            $dosen = $dosenArray[array_rand($dosenArray)]; // Random dari array
            $predictions[] = [
                'dosen_id' => $dosen['id'],
                'mata_kuliah_id' => rand(1, 50),
                'predicted_status' => 'diterima',
                'actual_status' => 'diterima',
                'confidence_score' => rand(80, 99),
                'features_used' => json_encode([
                    'ranking' => rand(1, 3),
                ]),
                'predicted_at' => now()->subDays(rand(1, 30)),
                'is_verified' => true,
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // False Negative: 5 (Prediksi: ditolak, Aktual: diterima)
        for ($i = 0; $i < 5; $i++) {
            $dosen = $dosenArray[array_rand($dosenArray)];
            $predictions[] = [
                'dosen_id' => $dosen['id'],
                'mata_kuliah_id' => rand(1, 50),
                'predicted_status' => 'ditolak',
                'actual_status' => 'diterima',
                'confidence_score' => rand(60, 75),
                'features_used' => json_encode([
                    'ranking' => rand(4, 10),
                ]),
                'predicted_at' => now()->subDays(rand(1, 30)),
                'is_verified' => true,
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // False Positive: 3 (Prediksi: diterima, Aktual: ditolak)
        for ($i = 0; $i < 3; $i++) {
            $dosen = $dosenArray[array_rand($dosenArray)];
            $predictions[] = [
                'dosen_id' => $dosen['id'],
                'mata_kuliah_id' => rand(1, 50),
                'predicted_status' => 'diterima',
                'actual_status' => 'ditolak',
                'confidence_score' => rand(70, 85),
                'features_used' => json_encode([
                    'ranking' => rand(1, 3),
                ]),
                'predicted_at' => now()->subDays(rand(1, 30)),
                'is_verified' => true,
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // True Negative: 47 (Prediksi: ditolak, Aktual: ditolak)
        for ($i = 0; $i < 47; $i++) {
            $dosen = $dosenArray[array_rand($dosenArray)];
            $predictions[] = [
                'dosen_id' => $dosen['id'],
                'mata_kuliah_id' => rand(1, 50),
                'predicted_status' => 'ditolak',
                'actual_status' => 'ditolak',
                'confidence_score' => rand(75, 95),
                'features_used' => json_encode([
                    'ranking' => rand(4, 10),
                ]),
                'predicted_at' => now()->subDays(rand(1, 30)),
                'is_verified' => true,
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Insert semua data
        foreach (array_chunk($predictions, 50) as $chunk) {
            AiPrediction::insert($chunk);
        }
        
        $this->command->info('✅ Seeded 100 AI predictions (Dummy Data)');
        $this->command->info('📊 Metrics: Accuracy 94%, TP=45, FN=5, FP=3, TN=47');
        $this->command->info('🎯 User yang dipakai bisa berulang (13 user untuk 100 predictions)');
    }
}