<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Matakuliah;
use App\Models\AiPrediction; // ← TAMBAHAN BARU
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

        $mkKategori = DB::table('mk_kategori')->get()->keyBy(function($item) {
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

        $debug_for_python = [];
        foreach ($dataDosen as $row) {
            $debug_for_python[] = [
                "kode_matkul" => $row["kode_matkul"],
                "dosen_id" => $row["id"],
                "features" => [
                    (int)$row["c1_self_assessment"],
                    (int)$row["c2_pendidikan"],
                    (int)$row["c3_sertifikat"],
                    (int)$row["c4_penelitian"],
                ]
            ];
        }

        file_put_contents(
            storage_path("logs/debug_features.json"),
            json_encode($debug_for_python, JSON_PRETTY_PRINT)
        );

        \Log::info("DATA_YANG_DIKIRIM_KE_AI", [
            'total' => count($dataDosen),
            'sample_10' => array_slice($dataDosen, 0, 10),
        ]);

        try {
            $payload = ['dosen' => $dataDosen];

            \Log::info("PAYLOAD_YANG_DIKIRIM_KE_AI", $payload);

            $response = Http::asJson()->post($this->flaskUrl . '/api/predict', $payload);

            if ($response->successful()) {
                $hasil = $response->json();

                $rekomendasi = [];
                foreach ($hasil as $kodeMk => $listDosen) {
                    $mk = Matakuliah::where('kode_mk', $kodeMk)->first();
                    if (!$mk) continue;

                    $dosens = [];
                    foreach ($listDosen as $d) {
                        $dosens[] = [
                            'user_id' => $d['dosen_id'],
                            'skor' => $d['skor']
                        ];
                    }

                    if (!empty($dosens)) {
                        $rekomendasi[] = [
                            'matakuliah_id' => $mk->id,
                            'dosens' => $dosens
                        ];
                    }
                }

                // ========================================
                // 🆕 TAMBAHAN BARU: SIMPAN KE AI PREDICTIONS
                // ========================================
                $this->saveAIPredictions($hasil, $dataDosen);
                // ========================================

                if (!empty($rekomendasi)) {
                    $requestDB = new Request([
                        'semester' => 'Ganjil 2024/2025',
                        'rekomendasi' => $rekomendasi
                    ]);

                    $hasilController = new HasilRekomendasiController();
                    $dbResponse = $hasilController->storeAIRecommendation($requestDB);
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
    private function saveAIPredictions($hasilAI, $dataDosen)
    {
        try {
            foreach ($hasilAI as $kodeMk => $listDosen) {
                // Ambil top 3 rekomendasi (dianggap "diterima" oleh AI)
                foreach ($listDosen as $index => $dosen) {
                    // Cek apakah dosen ini masuk top 3
                    $isPredictedAccepted = $index < 3;
                    
                    // Untuk saat ini, kita set actual_status = pending
                    // Nanti diupdate manual oleh admin setelah seleksi selesai
                    AiPrediction::create([
                        'dosen_id' => $dosen['dosen_id'],
                        'predicted_status' => $isPredictedAccepted ? 'diterima' : 'ditolak',
                        'actual_status' => 'pending', // Default pending
                        'confidence_score' => $dosen['skor'],
                        'features_used' => json_encode([
                            'kode_matkul' => $kodeMk,
                            'ranking' => $index + 1,
                        ]),
                        'predicted_at' => now(),
                    ]);
                }
            }
            
            \Log::info("✅ AI Predictions saved successfully for metrics tracking");
        } catch (\Exception $e) {
            // Kalau error, tidak masalah, rekomendasi tetap jalan
            \Log::error("❌ Failed to save AI predictions: " . $e->getMessage());
        }
    }
}