<?php

namespace App\Http\Controllers;

use App\Models\HasilRekomendasi;
use App\Models\DetailHasilRekomendasi;
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
     * PATCH /api/hasil-rekomendasis/{id}/finalize
     * Penetapan hasil (Finalized / Rejected)
     */
    public function finalize(Request $request, HasilRekomendasi $hasilRekomendasi)
    {
        $validated = $request->validate([
            'status' => 'required|in:Finalized,Rejected',
        ]);

        $hasilRekomendasi->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hasil rekomendasi berhasil ditetapkan.',
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
             */
            $hasil = HasilRekomendasi::create([
                'semester' => $data['semester'],
                'tahun_ajaran' => $tahunAjaran,
                'status' => 'Pending',
            ]);

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
                }
            }

            DB::commit();

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

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan hasil rekomendasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

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

    
    public function generate()
    {
        app(AIIntegrationController::class)->generateRecommendation();

        return redirect()
            ->route('hasil.rekomendasi')
            ->with('success', 'Rekomendasi berhasil digenerate');
    }

}
