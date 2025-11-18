<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Http\Resources\ProdiResource;
use Illuminate\Support\Facades\Auth;

class ProdiController extends Controller
{
    /**
     * Middleware construct: Memastikan semua operasi CUD (store, update, destroy)
     * hanya dapat diakses oleh user dengan peran 'admin' atau 'superadmin'.
     */
    public function __construct()
    {
        // Peringatan: Anda harus mengimplementasikan logic 'hasRole' pada model User Anda.
        $this->middleware('check.role:admin')->except(['index']);
    }

    /**
     * GET /api/prodi
     * Mendapatkan daftar semua Program Studi (Lookup data).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $prodi = Prodi::all();
        // Menggunakan Resource Collection untuk mengembalikan daftar Prodi
        return ProdiResource::collection($prodi);
    }

    /**
     * POST /api/prodi
     * Menyimpan data Program Studi baru. (Admin Only)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:prodi,kode_prodi',
            'nama_prodi' => 'required|string|max:255',
        ], [
            'kode_prodi.unique' => 'Kode Prodi sudah terdaftar.',
        ]);

        $prodi = Prodi::create($request->all());

        return response()->json([
            'message' => 'Program Studi berhasil ditambahkan.',
            'data' => new ProdiResource($prodi)
        ], 201);
    }

    /**
     * PATCH /api/prodi/{prodi}
     * Memperbarui data Program Studi tertentu. (Admin Only)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prodi  $prodi
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Prodi $prodi)
    {
        $request->validate([
            'kode_prodi' => 'sometimes|string|max:10|unique:prodi,kode_prodi,' . $prodi->id,
            'nama_prodi' => 'sometimes|string|max:255',
        ]);

        $prodi->update($request->all());

        return response()->json([
            'message' => 'Program Studi berhasil diperbarui.',
            'data' => new ProdiResource($prodi)
        ]);
    }

    /**
     * DELETE /api/prodi/{prodi}
     * Menghapus Program Studi. (Admin Only)
     *
     * @param  \App\Models\Prodi  $prodi
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Prodi $prodi)
    {
        // Catatan: Pastikan database memiliki ON DELETE CASCADE di tabel yang berelasi
        // (misalnya mata_kuliah atau users) sebelum menghapus data prodi.
        $prodi->delete();

        return response()->json(['message' => 'Program Studi berhasil dihapus.'], 200);
    }
}
