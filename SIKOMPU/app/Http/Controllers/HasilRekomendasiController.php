<?php

namespace App\Http\Controllers;

use App\Models\HasilRekomendasi;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HasilRekomendasiResource;
use App\Models\DetailHasilRekomendasi;


class HasilRekomendasiController extends Controller
{
    /**
     * GET /api/hasil-rekomendasis
     * Menampilkan list hasil rekomendasi
     */
    public function index()
    {
        // Tampilkan hasil_rekomendasi + jumlah detail
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
            'detailHasilRekomendasi.user'
            // 'mataKuliah', 'koordinatorRekomendasi', 'pengampuRekomendasi', 'details'
        ]);


        return response()->json([
            'success' => true,
            'data' => $hasilRekomendasi
        ]);
    }

    /**
     * PATCH /api/hasil-rekomendasis/{id}/finalize
     * Penetapan hasil (opsional)
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

    public function storeAIRecommendation(Request $request)
{
    $data = $request->validate([
        'semester' => 'required|string',
        'rekomendasi' => 'required|array',
    ]);

    // 1. Simpan header hasil rekomendasi
$parts = explode(" ", $data['semester']);

$tahunAjaran = $parts[count($parts) - 1] ?? null; 


$hasil = HasilRekomendasi::create([
    'semester' => $data['semester'],
    'tahun_ajaran' => $tahunAjaran,
    'status' => 'Pending',
]);

    // 2. Simpan detail per matakuliah
    foreach ($data['rekomendasi'] as $item) {
        // Koordinator
        DetailHasilRekomendasi::create([
            'hasil_id' => $hasil->id,
            'matakuliah_id' => $item['matakuliah_id'],
            'user_id' => $item['koordinator_id'],
            'peran_penugasan' => 'koordinator',
            'skor_dosen_di_mk' => $item['skor']
        ]);

        // Pengampu
        foreach ($item['pengampu_ids'] as $pengampuId) {
            DetailHasilRekomendasi::create([
                'hasil_id' => $hasil->id,
                'matakuliah_id' => $item['matakuliah_id'],
                'user_id' => $pengampuId,
                'peran_penugasan' => 'pengampu',
                'skor_dosen_di_mk' => $item['skor'] // bisa pakai skor sama atau berbeda
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Hasil rekomendasi AI berhasil disimpan',
        'data' => $hasil->load('detailHasilRekomendasi.user','detailHasilRekomendasi.mataKuliah')
    ]);
}
}



