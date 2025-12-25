<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Matakuliah;
use App\Http\Controllers\HasilRekomendasiController;

class AIIntegrationController extends Controller
{
    protected $flaskUrl = 'http://127.0.0.1:5000';

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
            return response()->json([
                'status' => 'Failed', 
                'message' => 'Pastikan python app.py sudah jalan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generateRecommendation()
    {
        \Log::info('🚀 === GENERATE REKOMENDASI DIMULAI ===');
        
        // AMBIL HANYA DOSEN & LABORAN (pakai kolom jabatan)
        $users = User::with(['selfAssessments', 'penelitian', 'sertifikat', 'pendidikan'])
            ->whereIn('jabatan', ['Dosen', 'Laboran'])
            ->where('status', 'Aktif')
            ->get();
        
        $matkuls = Matakuliah::all();

        \Log::info('📊 Data Summary', [
            'total_users' => $users->count(),
            'total_matkul' => $matkuls->count()
        ]);

        if ($users->isEmpty()) {
            return response()->json([
                'error' => 'Tidak ada dosen aktif di database',
                'hint' => 'Pastikan ada user dengan jabatan Dosen/Laboran dan status Aktif'
            ], 400);
        }

        if ($matkuls->isEmpty()) {
            return response()->json(['error' => 'Tidak ada mata kuliah di database'], 400);
        }

        // AMBIL MK_KATEGORI
        $mkKategori = DB::table('mk_kategori')->get()->keyBy(function ($item) {
            return $item->mata_kuliah_id . '-' . $item->kategori_id;
        });

        \Log::info('📚 MK Kategori Count', ['count' => $mkKategori->count()]);

        // SIAPKAN DATA UNTUK AI
        $dataDosen = [];
        $statsPerDosen = [];

        foreach ($users as $user) {
            $userStats = [
                'id' => $user->id,
                'nama' => $user->nama_lengkap,
                'jabatan' => $user->jabatan,
                'has_pendidikan' => $user->pendidikan->isNotEmpty(),
                'has_self_assessment' => $user->selfAssessments->isNotEmpty(),
                'has_sertifikat' => $user->sertifikat->isNotEmpty(),
                'has_penelitian' => $user->penelitian->isNotEmpty(),
                'valid_mk_count' => 0
            ];

            foreach ($matkuls as $mk) {

                $c1_self = optional(
                    $user->selfAssessments()
                        ->where('matakuliah_id', $mk->id)
                        ->latest()
                        ->first()
                )->nilai ?? 0;

                $pendidikan = optional($user->pendidikan->last())->jenjang;
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

                $totalSkor = $c1_self + $c2_pendidikan + $c3_total + $c4_total;
                
                if ($totalSkor == 0) {
                    continue;
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
                
                $userStats['valid_mk_count']++;
            }

            if ($userStats['valid_mk_count'] > 0) {
                $statsPerDosen[] = $userStats;
            }
        }

        \Log::info('📊 Stats Per Dosen', $statsPerDosen);
        \Log::info('📊 Total Valid Records', ['count' => count($dataDosen)]);

        if (empty($dataDosen)) {
            return response()->json([
                'error' => 'Tidak ada dosen yang memenuhi syarat',
                'hint' => 'Dosen harus mengisi minimal: Pendidikan ATAU Self Assessment ATAU Sertifikat ATAU Penelitian',
                'stats' => $statsPerDosen
            ], 400);
        }

        // KIRIM KE FLASK
        try {
            $payload = ['dosen' => $dataDosen];

            \Log::info('📤 Sending to Flask', [
                'url' => $this->flaskUrl . '/api/predict',
                'record_count' => count($dataDosen)
            ]);

            $response = Http::timeout(60)->asJson()->post($this->flaskUrl . '/api/predict', $payload);

            if (!$response->successful()) {
                \Log::error('❌ Flask Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'error' => 'Flask gagal memproses data',
                    'status' => $response->status(),
                    'detail' => $response->body()
                ], 500);
            }

            $hasil = $response->json();
            $hasilAI = $hasil['hasil_rekomendasi'] ?? [];

            \Log::info('✅ Flask Response', ['count' => count($hasilAI)]);

            if (empty($hasilAI)) {
                return response()->json([
                    'error' => 'Flask tidak mengembalikan hasil',
                    'flask_response' => $hasil
                ], 400);
            }

            // FILTER SKOR >= 70
            $hasilFiltered = collect($hasilAI)
                ->filter(fn($item) => $item['skor_prediksi'] >= 70)
                ->values()
                ->all();

            \Log::info('🔍 Filter Skor >= 70', [
                'before' => count($hasilAI),
                'after' => count($hasilFiltered),
                'rejected_scores' => collect($hasilAI)
                    ->filter(fn($item) => $item['skor_prediksi'] < 70)
                    ->pluck('skor_prediksi', 'nama')
            ]);

            if (empty($hasilFiltered)) {
                return response()->json([
                    'error' => 'Tidak ada dosen yang lulus threshold (skor >= 70)',
                    'hint' => 'Tingkatkan kualitas data dosen atau turunkan threshold',
                    'all_scores' => collect($hasilAI)->map(fn($item) => [
                        'nama' => $item['nama'],
                        'mk' => $item['kode_matkul'],
                        'skor' => $item['skor_prediksi']
                    ])
                ], 400);
            }

            // DISTRIBUSI KE MATA KULIAH
            $grouped = collect($hasilFiltered)->groupBy('kode_matkul');
            $rekomendasi = [];
            $globalUsedUserIds = [];

            \Log::info('📚 Grouped by MK', [
                'mk_count' => $grouped->count(),
                'mk_list' => $grouped->keys()
            ]);

            foreach ($grouped as $kodeMk => $items) {

                $mk = Matakuliah::where('kode_mk', $kodeMk)->first();
                if (!$mk) {
                    \Log::warning('⚠️ MK not found', ['kode_mk' => $kodeMk]);
                    continue;
                }

                $sorted = collect($items)->sortByDesc('skor_prediksi')->values();
                $dosens = [];
                $MAX_PENGAMPU = 10;

                foreach ($sorted as $item) {
                    $user = User::where('nama_lengkap', $item['nama'])->first();
                    
                    if (!$user) {
                        \Log::warning('⚠️ User not found', ['nama' => $item['nama']]);
                        continue;
                    }

                    if (in_array($user->id, $globalUsedUserIds)) {
                        \Log::info('⏩ Skip duplikat', [
                            'user' => $user->nama_lengkap,
                            'mk' => $kodeMk,
                            'skor' => $item['skor_prediksi'],
                            'reason' => 'Sudah ditugaskan di MK lain'
                        ]);
                        continue;
                    }

                    if (count($dosens) === 0) {
                        $role = 'Koordinator';
                    } else {
                        $jumlahPengampu = collect($dosens)->where('role', 'Pengampu')->count();
                        
                        if ($jumlahPengampu >= $MAX_PENGAMPU) {
                            \Log::info('⏩ Max pengampu reached', ['mk' => $kodeMk]);
                            break;
                        }
                        
                        $role = 'Pengampu';
                    }

                    $dosens[] = [
                        'user_id' => $user->id,
                        'role' => $role,
                        'skor' => $item['skor_prediksi'],
                    ];

                    $globalUsedUserIds[] = $user->id;

                    \Log::info('✅ Assigned', [
                        'user' => $user->nama_lengkap,
                        'mk' => $mk->kode_mk . ' - ' . $mk->nama_mk,
                        'role' => $role,
                        'skor' => round($item['skor_prediksi'], 2)
                    ]);
                }

                if (!empty($dosens)) {
                    $rekomendasi[] = [
                        'matakuliah_id' => $mk->id,
                        'dosens' => $dosens,
                    ];
                }
            }

            \Log::info('📊 Final Rekomendasi', [
                'total_mk' => count($rekomendasi),
                'total_penugasan' => collect($rekomendasi)->sum(fn($r) => count($r['dosens'])),
                'mk_dengan_koordinator' => collect($rekomendasi)
                    ->filter(fn($r) => collect($r['dosens'])->contains('role', 'Koordinator'))
                    ->count()
            ]);

            if (empty($rekomendasi)) {
                return response()->json([
                    'error' => 'Tidak ada penugasan yang bisa dibuat'
                ], 400);
            }

            // NONAKTIFKAN DATA LAMA
            DB::table('hasil_rekomendasi')
                ->where('is_active', true)
                ->update(['is_active' => false]);

            \Log::info('🔄 Old data deactivated');

            // SIMPAN KE DATABASE
            $hasilController = app(HasilRekomendasiController::class);
            $dbResponse = $hasilController->saveFromAI('Ganjil 2024/2025', $rekomendasi);

            \Log::info('💾 Saved to DB');

            return response()->json([
                'status' => 'success',
                'message' => '🎉 Rekomendasi berhasil digenerate!',
                'summary' => [
                    'total_dosen_input' => count($dataDosen),
                    'total_hasil_ai' => count($hasilAI),
                    'total_lulus_threshold' => count($hasilFiltered),
                    'total_mk_ditugaskan' => count($rekomendasi),
                    'total_penugasan' => collect($rekomendasi)->sum(fn($r) => count($r['dosens'])),
                    'dosen_ditugaskan' => count($globalUsedUserIds),
                    'dosen_tidak_ditugaskan' => $users->count() - count($globalUsedUserIds)
                ],
                'details' => [
                    'mk_list' => collect($rekomendasi)->map(fn($r) => [
                        'mk_id' => $r['matakuliah_id'],
                        'jumlah_dosen' => count($r['dosens'])
                    ])
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('💥 EXCEPTION', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'error' => 'Terjadi kesalahan sistem',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}