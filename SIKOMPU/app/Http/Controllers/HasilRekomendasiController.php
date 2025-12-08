<?php

namespace App\Http\Controllers;

use App\Models\HasilRekomendasi;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HasilRekomendasiResource;

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
}



