<?php

namespace App\Http\Controllers;

use App\Models\SelfAssessment;
use Illuminate\Http\Request;
use App\Http\Resources\SelfAssessmentResource;
use Illuminate\Support\Facades\Auth;

class SelfAssessmentController extends Controller
{
    /**
     * POST /api/self-assessments
     * Menerima input data self-assessment baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            // mata_kuliah_id harus ada, harus integer, dan harus ada di tabel mata_kuliah
            'mata_kuliah_id' => 'required|integer|exists:mata_kuliah,id',
            // nilai adalah teks (asumsi kualitatif atau deskriptif)
            'nilai' => 'required|string|max:1000',
        ], [
            'mata_kuliah_id.required' => 'Mata Kuliah wajib dipilih.',
            'mata_kuliah_id.exists' => 'Mata Kuliah tidak terdaftar.',
            'nilai.required' => 'Nilai atau deskripsi assessment wajib diisi.',
        ]);

        // 2. Buat entri Self Assessment
        $assessment = SelfAssessment::create([
            'user_id' => Auth::id(), // Otomatis dihubungkan ke user yang sedang login
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nilai' => $request->nilai,
        ]);

        // 3. Kembalikan respons sukses
        return response()->json([
            'message' => 'Self Assessment berhasil disimpan.',
            // Menggunakan Resource untuk menampilkan data yang baru dibuat
            'data' => new SelfAssessmentResource($assessment->load('mataKuliah')) 
        ], 201); // 201 Created
    }

    /**
     * GET /api/self-assessments
     * Mendapatkan riwayat self-assessment yang pernah diisi oleh pengguna saat ini.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // 1. Ambil data assessment hanya untuk user yang sedang login
        $assessments = SelfAssessment::where('user_id', Auth::id())
                        ->with('mataKuliah') // Eager load relasi mata_kuliah untuk Resource
                        ->latest() // Urutkan dari yang terbaru
                        ->paginate(10);

        // 2. Kembalikan koleksi Resource
        return SelfAssessmentResource::collection($assessments);
    }
    
    /**
     * DELETE /api/self-assessments/{id}
     * Menghapus entri self-assessment tertentu.
     * Hanya boleh dilakukan oleh pemilik data.
     *
     * @param  \App\Models\SelfAssessment  $selfAssessment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SelfAssessment $selfAssessment)
    {
        // Otorisasi: Pastikan user yang login adalah pemilik data
        if ($selfAssessment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized. Data ini bukan milik Anda.'], 403);
        }
        
        $selfAssessment->delete();
        
        return response()->json(['message' => 'Self Assessment berhasil dihapus.'], 200);
    }
}
