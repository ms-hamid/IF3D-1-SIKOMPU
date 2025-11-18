<?php

namespace App\Http\Controllers;

use App\Models\HasilRekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HasilRekomendasiResource;

class HasilRekomendasiController extends Controller
{
    /**
     * Tampilkan semua hasil rekomendasi (untuk Admin).
     * Endpoint: GET /api/hasil-rekomendasis
     */
    public function index()
    {
        // Admin bisa melihat semua, Dosen bisa melihat yang terkait dengan Prodi/dirinya.
        $hasilRekomendasis = HasilRekomendasi::with(['mataKuliah', 'koordinatorRekomendasi', 'pengampuRekomendasi'])->get();
        
        return HasilRekomendasiResource::collection($hasilRekomendasis);
    }

    /**
     * Tampilkan detail hasil rekomendasi.
     * Endpoint: GET /api/hasil-rekomendasis/{hasilRekomendasi}
     */
    public function show(HasilRekomendasi $hasilRekomendasi)
    {
        // Muat semua relasi penting
        $hasilRekomendasi->load(['mataKuliah', 'koordinatorRekomendasi', 'pengampuRekomendasi', 'details']);

        return new HasilRekomendasiResource($hasilRekomendasi);
    }

    /**
     * Penetapan Rekomendasi (Hanya Admin).
     * Endpoint: PATCH /api/hasil-rekomendasis/{hasilRekomendasi}/finalize
     */
    public function finalize(Request $request, HasilRekomendasi $hasilRekomendasi)
    {
        // Otorisasi sudah diproteksi oleh middleware check.role:admin pada rute.
        
        $validated = $request->validate([
            'status' => 'required|in:Finalized,Rejected',
            // Di sini Anda bisa menambahkan validasi untuk user_id koordinator/pengampu final
        ]);

        $hasilRekomendasi->update([
            'status' => $validated['status'],
            'ditetapkan_oleh' => Auth::id(),
            'tanggal_penetapan' => now(),
        ]);

        return response()->json([
            'message' => 'Hasil rekomendasi berhasil ditetapkan.',
            'data' => new HasilRekomendasiResource($hasilRekomendasi->load(['koordinatorRekomendasi', 'pengampuRekomendasi']))
        ]);
    }

    // Metode untuk CREATE (store) dan DELETE (destroy) dihilangkan
    // karena hasil rekomendasi biasanya dibuat/dihapus oleh sistem, bukan oleh API CRUD.
}
