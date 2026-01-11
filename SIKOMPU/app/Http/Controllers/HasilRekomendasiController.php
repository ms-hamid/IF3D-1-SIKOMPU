<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\HasilRekomendasi;
use App\Models\DetailHasilRekomendasi;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AIIntegrationController;

class HasilRekomendasiController extends Controller
{
    /**
     * GET /api/hasil-rekomendasis
     * Menampilkan list hasil rekomendasi
     */
    public function index()
    {
        $hasil = HasilRekomendasi::withCount('detailHasilRekomendasi')->get();

        return response()->json([
            'success' => true,
            'data' => $hasil
        ]);
    }

    /**
     * GET /api/hasil-rekomendasis/{id}
     * Menampilkan detail lengkap 1 hasil rekomendasi
     */
    public function show(HasilRekomendasi $hasilRekomendasi)
    {
        $hasilRekomendasi->load([
            'detailHasilRekomendasi.mataKuliah',
            'detailHasilRekomendasi.user',
        ]);

        return response()->json([
            'success' => true,
            'data' => $hasilRekomendasi
        ]);
    }

    /**
     * POST /api/hasil-rekomendasis
     * Simpan hasil rekomendasi dari AI
     */
    public function storeAIRecommendation(Request $request)
    {
        $data = $request->validate([
            'semester' => 'required|string',
            'rekomendasi' => 'required|array|min:1',
            'rekomendasi.*.matakuliah_id' => 'required|exists:mata_kuliah,id',
            'rekomendasi.*.dosens' => 'required|array|min:1',
            'rekomendasi.*.dosens.*.user_id' => 'required|exists:users,id',
            'rekomendasi.*.dosens.*.skor' => 'required|numeric',
        ]);

        // Ambil tahun ajaran dari string semester
        $parts = explode(' ', $data['semester']);
        $tahunAjaran = end($parts) ?: null;

        DB::beginTransaction();

        try {
            // Simpan header hasil rekomendasi
            $hasil = HasilRekomendasi::create([
                'semester' => $data['semester'],
                'tahun_ajaran' => $tahunAjaran,
                'is_active' => true,
            ]);

            \Log::info('✅ Hasil rekomendasi created', ['id' => $hasil->id]);

            $totalMataKuliah = 0;
            $totalPenugasan = 0;

            // Track jumlah koordinator per user
            $koordinatorCount = []; // [user_id => jumlah koordinator]

            foreach ($data['rekomendasi'] as $item) {

                if (empty($item['dosens'])) {
                    throw new \Exception(
                        'Tidak ada dosen untuk matakuliah ID ' . $item['matakuliah_id']
                    );
                }

                // Urutkan dosen berdasarkan skor tertinggi
                $sortedDosens = collect($item['dosens'])
                    ->sortByDesc(fn ($d) => [
                        $d['skor'],
                        -$d['user_id'],
                    ])
                    ->values();

                $koordinatorDitugaskan = false;

                foreach ($sortedDosens as $index => $dosen) {
                    $userId = $dosen['user_id'];

                    if (!isset($koordinatorCount[$userId])) {
                        $koordinatorCount[$userId] = 0;
                    }

                    $role = 'pengampu';

                    // Tentukan koordinator jika belum ada koordinator untuk MK ini
                    // dan user belum mencapai batas 3x koordinator
                    if (!$koordinatorDitugaskan && $koordinatorCount[$userId] < 3) {
                        $role = 'koordinator';
                        $koordinatorCount[$userId]++;
                        $koordinatorDitugaskan = true;
                    }

                    DetailHasilRekomendasi::create([
                        'hasil_id' => $hasil->id,
                        'matakuliah_id' => $item['matakuliah_id'],
                        'user_id' => $userId,
                        'peran_penugasan' => $role,
                        'skor_dosen_di_mk' => $dosen['skor'],
                    ]);

                    $totalPenugasan++;
                }

                $totalMataKuliah++;

                \Log::info('✅ Detail rekomendasi saved', [
                    'matakuliah_id' => $item['matakuliah_id'],
                    'total_dosen' => count($sortedDosens)
                ]);
            }

            DB::commit();

            // KIRIM NOTIFIKASI KE SEMUA STRUKTURAL 
            NotificationService::sendToStruktural(
                'recommendation',
                'Rekomendasi Semester Baru Siap',
                "Rekomendasi untuk {$totalMataKuliah} mata kuliah dengan {$totalPenugasan} penugasan telah berhasil di-generate",
                route('hasil.rekomendasi'),
                'star'
            );

            return response()->json([
                'success' => true,
                'message' => 'Hasil rekomendasi AI berhasil disimpan.',
                'data' => $hasil->load(
                    'detailHasilRekomendasi.user',
                    'detailHasilRekomendasi.mataKuliah'
                )
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('❌ Error saving rekomendasi', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan hasil rekomendasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper: Dipanggil dari AIIntegrationController
     */
    public function saveFromAI(string $semester, array $rekomendasi)
    {
        $request = Request::create(
            '/api/hasil-rekomendasis',
            'POST',
            [
                'semester' => $semester,
                'rekomendasi' => $rekomendasi,
            ]
        );

        return $this->storeAIRecommendation($request);
    }

    /**
     * Generate rekomendasi (dipanggil dari route)
     */
    public function generate()
    {
        app(AIIntegrationController::class)->generateRecommendation();

        return redirect()
            ->route('hasil.rekomendasi')
            ->with('success', 'Rekomendasi berhasil digenerate');
    }

}