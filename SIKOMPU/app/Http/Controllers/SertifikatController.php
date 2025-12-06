<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sertifikats = Sertifikat::where('user_id', Auth::id())
            ->orderBy('tahun_diperoleh', 'desc')
            ->get();
        
        // ✅ Detect role & return view yang sesuai
        if (auth()->user()->hasRole(['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'])) {
            return view('pages.sertifikasi-struktural', compact('sertifikats'));
        }
    
        return view('pages.sertifikasi', compact('sertifikats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
            'nama' => 'required|string|max:255',
            'institusi' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:' . date('Y'),
            'klasifikasi' => 'required|string|max:100',
        ], [
            'file.required' => 'File sertifikat harus diunggah',
            'file.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG',
            'file.max' => 'Ukuran file maksimal 5MB',
            'nama.required' => 'Nama sertifikat harus diisi',
            'institusi.required' => 'Institusi pemberi harus diisi',
            'tahun.required' => 'Tahun diperoleh harus dipilih',
            'tahun.min' => 'Tahun minimal adalah 2000',
            'tahun.max' => 'Tahun tidak boleh melebihi tahun sekarang',
            'klasifikasi.required' => 'Klasifikasi bidang kompetensi harus dipilih',
        ]);

        try {
            // Upload file
            $file = $request->file('file');
            $fileName = time() . '_' . Auth::id() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('sertifikat', $fileName, 'public');

            // Simpan data ke database
            Sertifikat::create([
                'user_id' => Auth::id(),
                'nama_sertifikat' => $validated['nama'],
                'institusi_pemberi' => $validated['institusi'],
                'tahun_diperoleh' => $validated['tahun'],
                'file_path' => $filePath,
                'status_verifikasi' => 'Menunggu', // default status sesuai enum
            ]);

            return redirect()->route('sertifikasi.index')
                ->with('success', 'Sertifikat berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan sertifikat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sertifikat = Sertifikat::where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('pages.sertifikasi-detail', compact('sertifikat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sertifikat = Sertifikat::where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('pages.sertifikasi-edit', compact('sertifikat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sertifikat = Sertifikat::where('user_id', Auth::id())
            ->findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nama' => 'required|string|max:255',
            'institusi' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:' . date('Y'),
            'klasifikasi' => 'required|string|max:100',
        ]);

        try {
            // Jika ada file baru, hapus file lama dan upload yang baru
            if ($request->hasFile('file')) {
                // Hapus file lama
                if ($sertifikat->file_path && Storage::disk('public')->exists($sertifikat->file_path)) {
                    Storage::disk('public')->delete($sertifikat->file_path);
                }

                // Upload file baru
                $file = $request->file('file');
                $fileName = time() . '_' . Auth::id() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('sertifikat', $fileName, 'public');
                
                $sertifikat->file_path = $filePath;
            }

            // Update data
            $sertifikat->nama_sertifikat = $validated['nama'];
            $sertifikat->institusi_pemberi = $validated['institusi'];
            $sertifikat->tahun_diperoleh = $validated['tahun'];
            $sertifikat->save();

            return redirect()->route('sertifikasi.index')
                ->with('success', 'Sertifikat berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui sertifikat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sertifikat = Sertifikat::where('user_id', Auth::id())
            ->findOrFail($id);

        try {
            // Hapus file dari storage
            if ($sertifikat->file_path && Storage::disk('public')->exists($sertifikat->file_path)) {
                Storage::disk('public')->delete($sertifikat->file_path);
            }

            // Hapus data dari database (soft delete otomatis karena ada softDeletes)
            $sertifikat->delete();

            return redirect()->route('sertifikasi.index')
                ->with('success', 'Sertifikat berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus sertifikat: ' . $e->getMessage());
        }
    }

    /**
     * Download file sertifikat
     */
    public function download($id)
    {
        $sertifikat = Sertifikat::where('user_id', Auth::id())
            ->findOrFail($id);

        if (!$sertifikat->file_path || !Storage::disk('public')->exists($sertifikat->file_path)) {
            return redirect()->back()
                ->with('error', 'File sertifikat tidak ditemukan!');
        }

        return Storage::disk('public')->download($sertifikat->file_path);
    }
}