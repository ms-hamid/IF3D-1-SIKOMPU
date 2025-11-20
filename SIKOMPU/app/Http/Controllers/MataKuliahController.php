<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use App\Http\Resources\MataKuliahResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MataKuliahController extends Controller
{

    public function index(Request $request)
    {
        $query = MataKuliah::with('prodi');

        // Filter opsional berdasarkan prodi_id
        if ($request->has('prodi_id') && is_numeric($request->prodi_id)) {
            $query->where('prodi_id', $request->prodi_id);
        }

        // Filter opsional berdasarkan semester
        if ($request->has('semester') && is_numeric($request->semester)) {
            $query->where('semester', $request->semester);
        }

        // Sorting berdasarkan semester, lalu nama_mk
        $mataKuliah = $query->orderBy('semester', 'asc')
                            ->orderBy('nama_mk', 'asc')
                            ->get();
        
        // Group by semester untuk view
        $mataKuliahBySemester = $mataKuliah->groupBy('semester');
        
        // Hitung statistik
        $totalMataKuliah = $mataKuliah->count();
        $totalSKS = $mataKuliah->sum('sks');
        $totalSemester = $mataKuliah->pluck('semester')->unique()->count();

        $prodiList = \App\Models\Prodi::orderBy('nama_prodi', 'asc')->get();

        return view('pages.manajemen-matkul', compact(
            'mataKuliahBySemester',
            'totalMataKuliah',
            'totalSKS',
            'totalSemester',
            'prodiList' 
        ));
    }

    public function create()
    {
        $prodiList = \App\Models\Prodi::orderBy('nama_prodi', 'asc')->get();
        
        return view('pages.manajemen-matkul', compact('prodiList'));
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

    public function show(MataKuliah $mataKuliah)
    {
        $mataKuliah->load('prodi');
        
        // Hitung jumlah self assessment dan detail hasil rekomendasi
        $jumlahSelfAssessment = $mataKuliah->selfAssessments()->count();
        $jumlahRekomendasi = $mataKuliah->detailHasilRekomendasi()->count();
        
        return view('pages.manajemen-matkul', compact(
            'mataKuliah',
            'jumlahSelfAssessment',
            'jumlahRekomendasi'
        ));
    }

    public function edit(MataKuliah $mataKuliah)
    {
        $prodiList = \App\Models\Prodi::orderBy('nama_prodi', 'asc')->get();
        
        return view('pages.edit-matkul', compact('mataKuliah', 'prodiList'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
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

    public function destroy(MataKuliah $mataKuliah)
    {
        try {
            // Cek apakah mata kuliah sedang digunakan
            $jumlahSelfAssessment = $mataKuliah->selfAssessments()->count();
            $jumlahRekomendasi = $mataKuliah->detailHasilRekomendasi()->count();
            
            if ($jumlahSelfAssessment > 0 || $jumlahRekomendasi > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Mata Kuliah tidak dapat dihapus karena masih digunakan dalam Self Assessment atau Rekomendasi.');
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

    public function filter(Request $request)
    {
        $query = MataKuliah::with('prodi');

        if ($request->has('prodi_id') && $request->prodi_id != '') {
            $query->where('prodi_id', $request->prodi_id);
        }

        if ($request->has('semester') && $request->semester != '') {
            $query->where('semester', $request->semester);
        }

        $mataKuliah = $query->orderBy('semester', 'asc')
                            ->orderBy('nama_mk', 'asc')
                            ->get();

        return response()->json([
            'success' => true,
            'data' => MataKuliahResource::collection($mataKuliah)
        ]);
    }
}