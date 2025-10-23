<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use App\Http\Resources\MataKuliahResource;
use Illuminate\Support\Facades\Auth;

class MataKuliahController extends Controller
{
    /**
     * Middleware construct: Memastikan semua operasi CUD (store, update, destroy)
     * hanya dapat diakses oleh user dengan peran 'admin' atau 'superadmin'.
     */
    public function __construct()
    {
        // Peringatan: Anda harus mengimplementasikan logic 'hasRole' pada model User Anda.
        $this->middleware('check.role:admin')->except(['index', 'show']);
    }

    /**
     * GET /api/mata-kuliah
     * Mendapatkan daftar semua Mata Kuliah.
     * Dapat difilter berdasarkan prodi_id (opsional).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = MataKuliah::with('prodi');

        // Filter opsional berdasarkan prodi_id
        if ($request->has('prodi_id') && is_numeric($request->prodi_id)) {
            $query->where('prodi_id', $request->prodi_id);
        }

        $mataKuliah = $query->latest()->paginate(15);
        
        // Menggunakan Resource Collection untuk mengembalikan daftar Mata Kuliah
        return MataKuliahResource::collection($mataKuliah);
    }

    /**
     * POST /api/mata-kuliah
     * Menyimpan data Mata Kuliah baru. (Admin Only)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'prodi_id' => 'required|integer|exists:prodi,id',
        ], [
            'kode_mk.unique' => 'Kode Mata Kuliah sudah terdaftar.',
            'prodi_id.exists' => 'ID Program Studi tidak valid.',
        ]);

        $mk = MataKuliah::create($request->all());

        return response()->json([
            'message' => 'Mata Kuliah berhasil ditambahkan.',
            // Eager load prodi untuk resource
            'data' => new MataKuliahResource($mk->load('prodi')) 
        ], 201);
    }

    /**
     * GET /api/mata-kuliah/{mataKuliah}
     * Menampilkan detail Mata Kuliah tertentu.
     *
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(MataKuliah $mataKuliah)
    {
        return response()->json([
            'data' => new MataKuliahResource($mataKuliah->load('prodi'))
        ]);
    }

    /**
     * PATCH /api/mata-kuliah/{mataKuliah}
     * Memperbarui data Mata Kuliah tertentu. (Admin Only)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $request->validate([
            'kode_mk' => 'sometimes|string|max:20|unique:mata_kuliah,kode_mk,' . $mataKuliah->id,
            'nama_mk' => 'sometimes|string|max:255',
            'sks' => 'sometimes|integer|min:1|max:6',
            'prodi_id' => 'sometimes|integer|exists:prodi,id',
        ]);

        $mataKuliah->update($request->all());

        return response()->json([
            'message' => 'Mata Kuliah berhasil diperbarui.',
            'data' => new MataKuliahResource($mataKuliah->load('prodi'))
        ]);
    }

    /**
     * DELETE /api/mata-kuliah/{mataKuliah}
     * Menghapus Mata Kuliah. (Admin Only)
     *
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(MataKuliah $mataKuliah)
    {
        // Catatan: Pastikan database memiliki ON DELETE CASCADE di tabel yang berelasi
        // (misalnya self_assessments) sebelum menghapus data Mata Kuliah.
        $mataKuliah->delete();

        return response()->json(['message' => 'Mata Kuliah berhasil dihapus.'], 200);
    }
}
