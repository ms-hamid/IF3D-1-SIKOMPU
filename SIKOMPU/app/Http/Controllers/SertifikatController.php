<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SertifikatResource;
use Illuminate\Validation\Rule;

class SertifikatController extends Controller
{
    /**
     * Tampilkan daftar semua sertifikat milik pengguna yang sedang login.
     * Endpoint: GET /api/sertifikats
     */
    public function index()
    {
        // Ambil semua sertifikat yang dimiliki oleh user yang sedang login
        $sertifikats = Auth::user()->sertifikats()->get();
        
        return SertifikatResource::collection($sertifikats);
    }

    /**
     * Simpan sertifikat baru ke database.
     * Endpoint: POST /api/sertifikats
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sertifikat' => 'required|string|max:255',
            'tahun_perolehan' => 'required|integer|digits:4|max:' . date('Y'),
            'penyelenggara' => 'required|string|max:255',
            'level' => ['required', 'string', Rule::in(['Nasional', 'Internasional', 'Institusi'])], // Sesuaikan opsi Level
        ]);

        // Pastikan sertifikat ini terkait dengan user yang sedang login
        $sertifikat = Auth::user()->sertifikats()->create($validated);

        return response()->json([
            'message' => 'Sertifikat berhasil ditambahkan.',
            'data' => new SertifikatResource($sertifikat)
        ], 201);
    }

    /**
     * Tampilkan detail sertifikat tertentu.
     * Endpoint: GET /api/sertifikats/{sertifikat}
     */
    public function show(Sertifikat $sertifikat)
    {
        // Policy Check: Pastikan user yang login adalah pemilik sertifikat
        if ($sertifikat->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke sertifikat ini.'], 403);
        }

        return new SertifikatResource($sertifikat);
    }

    /**
     * Perbarui data sertifikat.
     * Endpoint: PATCH /api/sertifikats/{sertifikat}
     */
    public function update(Request $request, Sertifikat $sertifikat)
    {
        // Policy Check: Pastikan user yang login adalah pemilik sertifikat
        if ($sertifikat->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk mengubah sertifikat ini.'], 403);
        }

        $validated = $request->validate([
            'nama_sertifikat' => 'sometimes|string|max:255',
            'tahun_perolehan' => 'sometimes|integer|digits:4|max:' . date('Y'),
            'penyelenggara' => 'sometimes|string|max:255',
            'level' => ['sometimes', 'string', Rule::in(['Nasional', 'Internasional', 'Institusi'])],
        ]);
        
        $sertifikat->update($validated);

        return response()->json([
            'message' => 'Sertifikat berhasil diperbarui.',
            'data' => new SertifikatResource($sertifikat)
        ]);
    }

    /**
     * Hapus sertifikat dari database.
     * Endpoint: DELETE /api/sertifikats/{sertifikat}
     */
    public function destroy(Sertifikat $sertifikat)
    {
        // Policy Check: Pastikan user yang login adalah pemilik sertifikat
        if ($sertifikat->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk menghapus sertifikat ini.'], 403);
        }

        $sertifikat->delete();

        return response()->json(['message' => 'Sertifikat berhasil dihapus.'], 204);
    }
}
