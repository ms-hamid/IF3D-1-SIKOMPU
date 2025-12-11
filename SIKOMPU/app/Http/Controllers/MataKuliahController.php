<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MataKuliahController extends Controller
{
    /**
     * GET /matakuliah
     * Menampilkan daftar mata kuliah
     */
    public function index(Request $request)
    {
        $query = MataKuliah::with(['prodi', 'kategori']);

        // Filter opsional berdasarkan prodi_id
        if ($request->has('prodi_id') && $request->prodi_id != '') {
            $query->where('prodi_id', $request->prodi_id);
        }

        // Filter opsional berdasarkan semester
        if ($request->has('semester') && is_numeric($request->semester)) {
            $query->where('semester', $request->semester);
        }

        // Sorting berdasarkan semester, lalu nama_mk
        $mataKuliah = $query->orderBy('semester', 'asc')
                            ->orderBy('nama_mk', 'asc')
                            ->paginate(15); // Pagination 15 per halaman
        
        // Group by semester untuk view
        $mataKuliahBySemester = $mataKuliah->groupBy('semester');
        
        // Hitung statistik dari semua data (tanpa pagination)
        $allMataKuliah = MataKuliah::query();
        if ($request->has('prodi_id') && $request->prodi_id != '') {
            $allMataKuliah->where('prodi_id', $request->prodi_id);
        }
        $allData = $allMataKuliah->get();
        
        $totalMataKuliah = $allData->count();
        $totalSKS = $allData->sum('sks');
        $totalSemester = $allData->pluck('semester')->unique()->count();
        $totalKategori = Kategori::count();

        $prodiList = \App\Models\Prodi::orderBy('nama_prodi', 'asc')->get();

        return view('pages.manajemen-matkul', compact(
            'mataKuliahBySemester',
            'mataKuliah', // Untuk pagination links
            'totalMataKuliah',
            'totalSKS',
            'totalSemester',
            'totalKategori',
            'prodiList' 
        ));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk',
                'nama_mk' => 'required|string|max:255',
                'sks' => 'required|integer|min:1|max:6',
                'sesi' => 'required|integer|min:1|max:20',
                'semester' => 'required|integer|min:1|max:8',
                'prodi_id' => 'required|integer|exists:prodi,id',
            ], [
                'kode_mk.required' => 'Kode Mata Kuliah wajib diisi.',
                'kode_mk.unique' => 'Kode Mata Kuliah sudah terdaftar.',
                'nama_mk.required' => 'Nama Mata Kuliah wajib diisi.',
                'sks.required' => 'Jumlah SKS wajib diisi.',
                'sks.min' => 'Jumlah SKS minimal 1.',
                'sks.max' => 'Jumlah SKS maksimal 6.',
                'sesi.required' => 'Jumlah Sesi wajib diisi.',
                'sesi.min' => 'Jumlah Sesi minimal 1.',
                'sesi.max' => 'Jumlah Sesi maksimal 20.',
                'semester.required' => 'Semester wajib diisi.',
                'semester.min' => 'Semester minimal 1.',
                'semester.max' => 'Semester maksimal 8.',
                'prodi_id.required' => 'Program Studi wajib dipilih.',
                'prodi_id.exists' => 'Program Studi tidak valid.',
            ]);

            $mk = MataKuliah::create($validated);

            return redirect()
                ->route('matakuliah.index')
                ->with('success', 'Mata Kuliah "' . $mk->nama_mk . '" berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
    
        try {
            $validated = $request->validate([
                'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk,' . $mataKuliah->id,
                'nama_mk' => 'required|string|max:255',
                'sks' => 'required|integer|min:1|max:6',
                'sesi' => 'required|integer|min:1|max:20',
                'semester' => 'required|integer|min:1|max:8',
                'prodi_id' => 'required|integer|exists:prodi,id',
            ], [
                'kode_mk.required' => 'Kode Mata Kuliah wajib diisi.',
                'kode_mk.unique' => 'Kode Mata Kuliah sudah terdaftar.',
                'nama_mk.required' => 'Nama Mata Kuliah wajib diisi.',
                'sks.required' => 'Jumlah SKS wajib diisi.',
                'sks.min' => 'Jumlah SKS minimal 1.',
                'sks.max' => 'Jumlah SKS maksimal 6.',
                'sesi.required' => 'Jumlah Sesi wajib diisi.',
                'sesi.min' => 'Jumlah Sesi minimal 1.',
                'sesi.max' => 'Jumlah Sesi maksimal 20.',
                'semester.required' => 'Semester wajib diisi.',
                'semester.min' => 'Semester minimal 1.',
                'semester.max' => 'Semester maksimal 8.',
                'prodi_id.required' => 'Program Studi wajib dipilih.',
                'prodi_id.exists' => 'Program Studi tidak valid.',
            ]);

            $mataKuliah->update($validated);

            return redirect()
                ->route('matakuliah.index')
                ->with('success', 'Mata Kuliah "' . $mataKuliah->nama_mk . '" berhasil diperbarui.');

        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $mataKuliah = MataKuliah::findOrFail($id);
        
            // Cek apakah mata kuliah sedang digunakan
            $jumlahSelfAssessment = $mataKuliah->selfAssessments()->count();
            $jumlahRekomendasi = $mataKuliah->detailHasilRekomendasi()->count();
        
            if ($jumlahSelfAssessment > 0 || $jumlahRekomendasi > 0) {           
                return redirect()                
                ->back()                
                ->with('error', 'Mata Kuliah "' . $mataKuliah->nama_mk . '" tidak dapat dihapus karena masih digunakan dalam Self Assessment atau Rekomendasi.');       
            }

            $namaMK = $mataKuliah->nama_mk;
            $mataKuliah->delete();

            return redirect()
                ->route('matakuliah.index')
                ->with('success', 'Mata Kuliah "' . $namaMK . '" berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}