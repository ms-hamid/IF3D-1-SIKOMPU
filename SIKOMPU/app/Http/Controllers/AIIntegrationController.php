<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Matakuliah;

class AIIntegrationController extends Controller
{
    // URL Service Flask (Lokal)
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
        // A. Ambil data lengkap
        $users = User::with(['selfAssessments', 'penelitians', 'sertifikat', 'pendidikans'])->get();
        $matkuls = Matakuliah::all();

        // Pivot mk_kategori — indexing: matkulID-kategoriID
        $mkKategori = DB::table('mk_kategori')->get()->keyBy(function($item) {
            return $item->mata_kuliah_id . '-' . $item->kategori_id;
        });

        $dataDosen = [];

        foreach ($users as $user) {
            foreach ($matkuls as $mk) {

                // ======================
                // C1: SELF ASSESSMENT
                // ======================
                $c1_self = optional($user->selfAssessments->last())->nilai ?? 0;

                // ======================
                // C2: PENDIDIKAN (MAPPING WAJIB)
                // ======================
                $pendidikan = optional($user->pendidikans->last())->jenjang;

                $c2_pendidikan = match ($pendidikan) {
                    'S3' => 5,
                    'S2' => 3,
                    'S1' => 1,
                    default => 0,
                };

                // ======================
                // C3: BOBOT SERTIFIKAT (RELEVANSI)
                // ======================
                $c3_total = 0;
                foreach ($user->sertifikat as $sertif) {
                    $key = $mk->id . '-' . $sertif->kategori_id;
                    if (isset($mkKategori[$key])) {
                        $c3_total += $mkKategori[$key]->bobot;
                    }
                }

                // ======================
                // C4: BOBOT PENELITIAN (RELEVANSI)
                // ======================
                $c4_total = 0;
                foreach ($user->penelitians as $penelitian) {
                    $key = $mk->id . '-' . $penelitian->kategori_id;
                    if (isset($mkKategori[$key])) {
                        $c4_total += $mkKategori[$key]->bobot;
                    }
                }

                // ======================
                // MASUKKAN KE ARRAY
                // ======================
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

        // ======================
        // DEBUG: simpan fitur yg dikirim ke Python (sekali)
        // ======================
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

        // tulis file debug (storage/logs/debug_features.json)
        file_put_contents(
            storage_path("logs/debug_features.json"),
            json_encode($debug_for_python, JSON_PRETTY_PRINT)
        );

        // Log payload (untuk pengecekan cepat di laravel.log)
        \Log::info("DATA_YANG_DIKIRIM_KE_AI", [
            'total' => count($dataDosen),
            'sample_10' => array_slice($dataDosen, 0, 10),
        ]);

        // ======================
        // B. Kirim ke Flask (HANYA SATU REQUEST)
        // ======================
        try {
            $payload = ['dosen' => $dataDosen];

            // Log payload lengkap sebelum dikirim
            \Log::info("PAYLOAD_YANG_DIKIRIM_KE_AI", $payload);

            $response = Http::asJson()->post($this->flaskUrl . '/api/predict', $payload);

            if ($response->successful()) {
                $hasil = $response->json();

                // (Opsional) Simpan Hasil ke Database Laravel di sini

                // Kembalikan hasil + sample debug kecil
                return response()->json([
                    'status' => 'success',
                    'hasil_ai' => $hasil,
                    'debug_sample' => array_slice($dataDosen, 0, 10)
                ]);
            } else {
                return response()->json(['error' => 'Gagal hitung di AI', 'detail' => $response->body()], 400);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Koneksi Error', 'msg' => $e->getMessage()], 500);
        }
    }
}

vers 2

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Matakuliah;
use App\Models\HasilRekomendasi;
use App\Models\DetailHasilRekomendasi;

class AIIntegrationController extends Controller
{
    protected $flaskUrl = 'http://127.0.0.1:5000';

    /**
     * Cek koneksi Flask
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

            return response()->json(['status' => 'Failed'], 500);

        } catch (\Exception $e) {
            return response()->json(['status' => 'Failed', 'message' => 'Pastikan python app.py sudah jalan!'], 500);
        }
    }

    /**
     * Generate Recommendation + Save to Database
     */
    public function generateRecommendation()
    {
        // Ambil data dosen & semua matkul
        $users = User::with(['selfAssessments', 'penelitians', 'sertifikat', 'pendidikans'])->get();
        $matkuls = Matakuliah::all();

        // Pivot: mk_kategori
        $mkKategori = DB::table('mk_kategori')->get()->keyBy(function($item) {
            return $item->mata_kuliah_id . '-' . $item->kategori_id;
        });

        $dataDosen = [];

        foreach ($users as $user) {
            foreach ($matkuls as $mk) {

                // C1
                $c1 = optional($user->selfAssessments->last())->nilai ?? 0;

                // C2
                $pendidikan = optional($user->pendidikans->last())->jenjang;
                $c2 = match ($pendidikan) {
                    'S3' => 5,
                    'S2' => 3,
                    'S1' => 1,
                    default => 0,
                };

                // C3
                $c3 = 0;
                foreach ($user->sertifikat as $s) {
                    $key = $mk->id . '-' . $s->kategori_id;
                    if (isset($mkKategori[$key])) {
                        $c3 += $mkKategori[$key]->bobot;
                    }
                }

                // C4
                $c4 = 0;
                foreach ($user->penelitians as $p) {
                    $key = $mk->id . '-' . $p->kategori_id;
                    if (isset($mkKategori[$key])) {
                        $c4 += $mkKategori[$key]->bobot;
                    }
                }

                $dataDosen[] = [
                    'id' => $user->id,
                    'nama' => $user->nama_lengkap,
                    'kode_matkul' => $mk->kode_mk,
                    'c1_self_assessment' => $c1,
                    'c2_pendidikan' => $c2,
                    'c3_sertifikat' => $c3,
                    'c4_penelitian' => $c4,
                ];
            }
        }

        // Simpan fitur ke debug file
        file_put_contents(
            storage_path("logs/debug_features.json"),
            json_encode($dataDosen, JSON_PRETTY_PRINT)
        );

        try {
            // Panggil Flask API
            $payload = ['dosen' => $dataDosen];
            $response = Http::asJson()->post($this->flaskUrl . '/api/predict', $payload);

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'Gagal hitung di AI',
                    'detail' => $response->body()
                ], 400);
            }

            $hasilAI = $response->json();

            // =====================================
            // ⬇⬇ SIMPAN KE DATABASE DI SINI ⬇⬇
            // =====================================

            // 1. Buat entry utama hasil rekomendasi
            $hasil = HasilRekomendasi::create([
                'semester' => "Ganjil 2025/2026",
                'status' => "Pending"
            ]);

            // 2. Simpan detail per matkul & per dosen
            foreach ($hasilAI as $mk) {

                $matkulId = Matakuliah::where('kode_mk', $mk['kode_mk'])->value('id');

                // Koordinator
                if (isset($mk['koordinator'])) {
                    DetailHasilRekomendasi::create([
                        'hasil_id' => $hasil->id,
                        'matakuliah_id' => $matkulId,
                        'user_id' => $mk['koordinator']['id'],
                        'peran_penugasan' => 'koordinator',
                        'skor_dosen_di_mk' => $mk['koordinator']['skor'],
                    ]);
                }

                // Pengampu
                if (isset($mk['pengampu'])) {
                    foreach ($mk['pengampu'] as $p) {
                        DetailHasilRekomendasi::create([
                            'hasil_id' => $hasil->id,
                            'matakuliah_id' => $matkulId,
                            'user_id' => $p['id'],
                            'peran_penugasan' => 'pengampu',
                            'skor_dosen_di_mk' => $p['skor'],
                        ]);
                    }
                }
            }

            // =====================================
            // ⬆⬆ SIMPAN KE DATABASE SELESAI ⬆⬆
            // =====================================

            return response()->json([
                'status' => 'success',
                'pesan' => 'Hasil AI berhasil disimpan ke database!',
                'hasil_id' => $hasil->id,
                'saved_data' => $hasil->load('detailHasilRekomendasi')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'msg' => $e->getMessage()
            ], 500);
        }
    }
}
