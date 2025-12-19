<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Matakuliah;
use App\Models\AiPrediction; 
use App\Http\Controllers\HasilRekomendasiController;

class AIIntegrationController extends Controller
{
    protected $flaskUrl = 'http://127.0.0.1:5000';

    /**
     * 1. Cek Koneksi ke Flask (GET)
     */
    public function checkConnection()
    {
        try {
            $response = Http::timeout(5)->get($this->flaskUrl . '/');

            if ($response->successful()) {
                return response()->json([
                    'laravel_status' => 'Connected',
                    'ai_service_response' => $response->json()
                ]);
            }
            return response()->json(['status' => 'Failed', 'error' => 'AI Service Error'], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failed', 'message' => 'Pastikan python app.py sudah jalan!'], 500);
        }
    }

    /**
     * 2. Generate Hasil Rekomendasi (POST ke Flask)
     */
    public function generateRecommendation()
    {
        $users = User::with(['selfAssessments', 'penelitians', 'sertifikat', 'pendidikans'])->get();
        $matkuls = Matakuliah::all();

        $mkKategori = DB::table('mk_kategori')->get()->keyBy(function ($item) {
            return $item->mata_kuliah_id . '-' . $item->kategori_id;
        });

        $dataDosen = [];

        foreach ($users as $user) {
            foreach ($matkuls as $mk) {

                $c1_self = optional(
                    $user->selfAssessments()
                        ->where('matakuliah_id', $mk->id)
                        ->latest()
                        ->first()
                )->nilai ?? 0;

                $pendidikan = optional($user->pendidikans->last())->jenjang;

                $c2_pendidikan = match ($pendidikan) {
                    'S3' => 5,
                    'S2' => 3,
                    'S1' => 1,
                    default => 0,
                };

                $c3_total = 0;
                foreach ($user->sertifikat as $sertif) {
                    $key = $mk->id . '-' . $sertif->kategori_id;
                    if (isset($mkKategori[$key])) {
                        $c3_total += $mkKategori[$key]->bobot;
                    }
                }

                $c4_total = 0;
                foreach ($user->penelitians as $penelitian) {
                    $key = $mk->id . '-' . $penelitian->kategori_id;
                    if (isset($mkKategori[$key])) {
                        $c4_total += $mkKategori[$key]->bobot;
                    }
                }

                $dataDosen[] = [
                    'id' => $user->id,
                    'nama' => $user->nama_lengkap,
                    'kode_matkul' => $mk->kode_mk,
                    'c1_self_assessment' => $c1_self,
                    'c2_pendidikan' => $c2_pendidikan,
                    'c3_sertifikat' => $c3_total,
                    'c4_penelitian' => $c4_total,
                ];
            }
        }

        try {
            $payload = ['dosen' => $dataDosen];

            $response = Http::asJson()->post($this->flaskUrl . '/api/predict', $payload);

            if ($response->successful()) {
                $hasil = $response->json();

                $hasilAI = $hasil['hasil_rekomendasi'] ?? [];

                // =====================
                // GROUP & FORMAT
                // =====================
                $grouped = collect($hasilAI)->groupBy('kode_matkul');
                $rekomendasi = [];

                foreach ($grouped as $kodeMk => $items) {

                    $mk = Matakuliah::where('kode_mk', $kodeMk)->first();
                    if (!$mk) continue;

                    // Urutkan skor tertinggi
                    $sorted = collect($items)->sortByDesc('skor_prediksi')->values();
                    $dosens = [];
                    $usedUserIds = [];
                    $MAX_PENGAMPU = 10;

                    foreach ($sorted as $item) {
                        $user = User::where('nama_lengkap', $item['nama'])->first();
                        if (!$user || in_array($user->id, $usedUserIds)) continue;
                        // if (!$user) continue;

                        // // ❌ Cegah dosen duplikat
                        // if (in_array($user->id, $usedUserIds)) continue;

                        // ✅ 1 Koordinator saja
                        if (count($dosens) === 0) {
                            $role = 'Koordinator';
                        } else {
                            $jumlahPengampu = collect($dosens)
                                ->where('role', 'Pengampu')
                                ->count();

                            if ($jumlahPengampu >= $MAX_PENGAMPU) break;

                            $role = 'Pengampu';
                        }

                        $dosens[] = [
                            'user_id' => $user->id,
                            'role' => $role,
                            'skor' => $item['skor_prediksi'],
                        ];

                        $usedUserIds[] = $user->id;
                    }

                    if (!empty($dosens)) {
                        $rekomendasi[] = [
                            'matakuliah_id' => $mk->id,
                            'dosens' => $dosens,
                        ];
                    }
                }

                // ===============================
                // CONSTRAINT: NONAKTIFKAN DATA LAMA
                // ===============================
                DB::table('hasil_rekomendasi')
                    ->where('semester', 'Ganjil 2024/2025')
                    ->update(['is_active' => false]);

                // ========================================
                // 🆕 TAMBAHAN BARU: SIMPAN KE AI PREDICTIONS
                // ========================================
                $this->saveAIPredictions($hasil['hasil_rekomendasi']);    

                if (!empty($rekomendasi)) {

                // PAKAI LARAVEL CONTAINER (WAJIB)
                $hasilController = app(HasilRekomendasiController::class);

                $dbResponse = $hasilController->saveFromAI(
                    'Ganjil 2024/2025',
                    $rekomendasi
                );

               // LOG WAJIB BIAR KELIHATAN MASUK / TIDAK
                    \Log::info('HASIL_SAVE_FROM_AI', [
                        'response' => $dbResponse instanceof \Illuminate\Http\JsonResponse
                            ? $dbResponse->getData(true)
                            : $dbResponse
                    ]);

                    } else {
                        $dbResponse = ['success' => false, 'message' => 'Tidak ada data rekomendasi untuk disimpan'];
                    }

                    return response()->json([
                        'status' => 'success',
                        'hasil_ai' => $hasil,
                        'debug_sample' => array_slice($dataDosen, 0, 10),
                        'db_response' => $dbResponse instanceof \Illuminate\Http\JsonResponse
                        ? $dbResponse->getData(true)
                        : $dbResponse
                    ]);

                } else {
                    return response()->json([
                        'error' => 'Gagal hitung di AI',
                        'detail' => $response->body()
                    ], 400);
                }

            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Koneksi Error',
                    'msg' => $e->getMessage()
                ], 500);
            }
        }

        // ========================================
        // 🆕 METHOD BARU: Simpan AI Predictions
        // ========================================
        private function saveAIPredictions(array $hasilAI)
        {
            try {
                foreach ($hasilAI as $item) {

                    $user = User::where('nama_lengkap', $item['nama'])->first();
                    if (!$user) continue;

                    AiPrediction::create([
                        'dosen_id' => $user->id,
                        'predicted_status' => 'direkomendasikan',
                        'actual_status' => 'pending',
                        'confidence_score' => $item['skor_prediksi'],
                        'features_used' => json_encode([
                            'kode_matkul' => $item['kode_matkul']
                        ]),
                        'predicted_at' => now(),
                    ]);
                }

                \Log::info("✅ AI Predictions saved");
            } catch (\Exception $e) {
                \Log::error("❌ AI Prediction error: " . $e->getMessage());
            }
        }

}