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
            /**
             * 1. Simpan header hasil rekomendasi
             * ⚠️ PENTING: TIDAK ADA KOLOM 'status' LAGI!
             */
            $hasil = HasilRekomendasi::create([
                'semester' => $data['semester'],
                'tahun_ajaran' => $tahunAjaran,
                'is_active' => true, // ← Langsung aktif saat dibuat
            ]);

            \Log::info('✅ Hasil rekomendasi created', ['id' => $hasil->id]);

            $totalMataKuliah = 0;
            $totalPenugasan = 0;

            /**
             * 2. Simpan detail rekomendasi per matakuliah
             */
            foreach ($data['rekomendasi'] as $item) {

                if (empty($item['dosens'])) {
                    throw new \Exception(
                        'Tidak ada dosen untuk matakuliah ID ' . $item['matakuliah_id']
                    );
                }

                // Urutkan dosen berdasarkan skor tertinggi
                // Jika skor sama → user_id terkecil jadi koordinator (deterministik)
                $sortedDosens = collect($item['dosens'])
                    ->sortByDesc(fn ($d) => [
                        $d['skor'],
                        -$d['user_id'],
                    ])
                    ->values();

                foreach ($sortedDosens as $index => $dosen) {
                    DetailHasilRekomendasi::create([
                        'hasil_id' => $hasil->id,
                        'matakuliah_id' => $item['matakuliah_id'],
                        'user_id' => $dosen['user_id'],
                        'peran_penugasan' => $index === 0 ? 'koordinator' : 'pengampu',
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

            // ✨ KIRIM NOTIFIKASI KE SEMUA STRUKTURAL ✨
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
        \Log::info('🚀 Generate rekomendasi dimulai');

        try {
            $response = app(AIIntegrationController::class)->generateRecommendation();

            // Cek apakah response adalah JsonResponse
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);

                if (isset($data['status']) && $data['status'] === 'success') {
                    // ✨ Notifikasi sudah dikirim di storeAIRecommendation() ✨
                    // Jadi tidak perlu kirim lagi di sini
                    
                    return redirect()
                        ->route('hasil.rekomendasi')
                        ->with('success', 'Rekomendasi berhasil digenerate! Total: ' . ($data['summary']['total_penugasan'] ?? 0) . ' penugasan');
                } else {
                    return redirect()
                        ->route('hasil.rekomendasi')
                        ->with('error', $data['error'] ?? 'Gagal generate rekomendasi');
                }
            }

            return redirect()
                ->route('hasil.rekomendasi')
                ->with('success', 'Rekomendasi berhasil digenerate');

        } catch (\Exception $e) {
            \Log::error('💥 Generate error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('hasil.rekomendasi')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}