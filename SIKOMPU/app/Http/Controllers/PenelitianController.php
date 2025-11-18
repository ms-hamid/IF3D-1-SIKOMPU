<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PenelitianResource;
use Illuminate\Validation\Rule;

class PenelitianController extends Controller
{
    /**
     * Tampilkan daftar semua penelitian milik pengguna yang sedang login.
     * Endpoint: GET /api/penelitians
     */
    public function index()
    {
        // Ambil semua penelitian yang dimiliki oleh user yang sedang login
        $penelitians = Auth::user()->penelitians()->get();
        
        return PenelitianResource::collection($penelitians);
    }

    /**
     * Simpan penelitian baru ke database.
     * Endpoint: POST /api/penelitians
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'tahun' => 'required|integer|digits:4|max:' . date('Y'),
            'bidang' => 'required|string|max:255',
            // Contoh validasi untuk jenis luaran (sesuaikan dengan kebutuhan Anda)
            'jenis_luaran' => ['required', 'string', Rule::in(['Jurnal', 'Prosiding', 'Buku', 'Paten', 'Lainnya'])], 
        ]);

        // Pastikan penelitian ini terkait dengan user yang sedang login
        $penelitian = Auth::user()->penelitians()->create($validated);

        return response()->json([
            'message' => 'Data penelitian berhasil ditambahkan.',
            'data' => new PenelitianResource($penelitian)
        ], 201);
    }

    /**
     * Tampilkan detail penelitian tertentu.
     * Endpoint: GET /api/penelitians/{penelitian}
     */
    public function show(Penelitian $penelitian)
    {
        // Policy Check: Pastikan user yang login adalah pemilik penelitian
        if ($penelitian->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke data penelitian ini.'], 403);
        }

        return new PenelitianResource($penelitian);
    }

    /**
     * Perbarui data penelitian.
     * Endpoint: PATCH /api/penelitians/{penelitian}
     */
    public function update(Request $request, Penelitian $penelitian)
    {
        // Policy Check: Pastikan user yang login adalah pemilik penelitian
        if ($penelitian->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk mengubah data penelitian ini.'], 403);
        }

        $validated = $request->validate([
            'judul' => 'sometimes|string|max:500',
            'tahun' => 'sometimes|integer|digits:4|max:' . date('Y'),
            'bidang' => 'sometimes|string|max:255',
            'jenis_luaran' => ['sometimes', 'string', Rule::in(['Jurnal', 'Prosiding', 'Buku', 'Paten', 'Lainnya'])],
        ]);
        
        $penelitian->update($validated);

        return response()->json([
            'message' => 'Data penelitian berhasil diperbarui.',
            'data' => new PenelitianResource($penelitian)
        ]);
    }

    /**
     * Hapus penelitian dari database.
     * Endpoint: DELETE /api/penelitians/{penelitian}
     */
    public function destroy(Penelitian $penelitian)
    {
        // Policy Check: Pastikan user yang login adalah pemilik penelitian
        if ($penelitian->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk menghapus data penelitian ini.'], 403);
        }

        $penelitian->delete();

        return response()->json(['message' => 'Data penelitian berhasil dihapus.'], 204);
    }
}
