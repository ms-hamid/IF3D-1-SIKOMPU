<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Kategori;

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

        // Ambil semua kategori untuk form
        $kategori = Kategori::all();

        if (auth()->user()->hasRole(['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'])) {
            return view('pages.sertifikasi-struktural', compact('sertifikats', 'kategori'));
        }

        return view('pages.sertifikasi', compact('sertifikats', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dasar
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
            'nama' => 'required|string|max:255',
            'institusi' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:' . date('Y'),
            'kategori_id' => 'nullable', // dicek manual di bawah (boleh 'custom' string)
            'kategori_baru' => 'nullable|string|max:100',
        ], [
            'file.required' => 'File sertifikat harus diunggah',
            'file.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG',
            'file.max' => 'Ukuran file maksimal 5MB',
            'nama.required' => 'Nama sertifikat harus diisi',
            'institusi.required' => 'Institusi pemberi harus diisi',
            'tahun.required' => 'Tahun diperoleh harus dipilih',
            'tahun.min' => 'Tahun minimal adalah 2000',
            'tahun.max' => 'Tahun tidak boleh melebihi tahun sekarang',
            'kategori_baru.max' => 'Kategori baru maksimal 100 karakter.',
        ]);

        // Tentukan kategori_id final
        $kategori_id = null;

        // Prioritas: jika user mengisi kategori_baru, pakai itu (buat atau pakai existing)
        $kategoriBaru = trim($request->input('kategori_baru', ''));
        $kategoriIdInput = $request->input('kategori_id', null);

        DB::beginTransaction();
        try {
            if ($kategoriBaru !== '') {
                // Hindari duplikat: firstOrCreate berdasarkan nama (case-sensitive default).
                // Jika ingin case-insensitive, ubah query.
                $kategoriModel = Kategori::firstOrCreate([
                    'nama' => $kategoriBaru
                ]);
                $kategori_id = $kategoriModel->id;
            } elseif ($kategoriIdInput && $kategoriIdInput !== 'custom' && $kategoriIdInput !== 'other') {
                // Jika user memilih kategori dari dropdown, pastikan ada di DB
                $kategoriModel = Kategori::find($kategoriIdInput);
                if (!$kategoriModel) {
                    throw ValidationException::withMessages(['kategori_id' => 'The selected kategori id is invalid.']);
                }
                $kategori_id = $kategoriModel->id;
            } else {
                // Tidak ada kategori valid => beri pesan validasi
                throw ValidationException::withMessages(['kategori_id' => 'Kategori wajib dipilih atau isi kategori baru.']);
            }

            // Upload file
            $file = $request->file('file');
            $fileName = time() . '_' . Auth::id() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('sertifikat', $fileName, 'public');

            // Simpan data sertifikat
            Sertifikat::create([
                'user_id' => Auth::id(),
                'nama_sertifikat' => $validated['nama'],
                'institusi_pemberi' => $validated['institusi'],
                'tahun_diperoleh' => $validated['tahun'],
                'file_path' => $filePath,
                'kategori_id' => $kategori_id,
            ]);

            DB::commit();

            return redirect()->route('sertifikasi.index')
                ->with('success', 'Sertifikat berhasil ditambahkan!');
        } catch (ValidationException $ve) {
            DB::rollBack();
            throw $ve; // biarkan Laravel menangani redirect withErrors
        } catch (\Exception $e) {
            DB::rollBack();
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
        
        $kategori = Kategori::all();

        return view('pages.sertifikasi-edit', compact('sertifikat', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sertifikat = Sertifikat::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nama' => 'required|string|max:255',
            'institusi' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:' . date('Y'),
            'kategori_id' => 'nullable',
            'kategori_baru' => 'nullable|string|max:100',
        ]);

        $kategori_id = null;
        $kategoriBaru = trim($request->input('kategori_baru', ''));
        $kategoriIdInput = $request->input('kategori_id', null);

        DB::beginTransaction();
        try {
            if ($kategoriBaru !== '') {
                $kategoriModel = Kategori::firstOrCreate([
                    'nama' => $kategoriBaru
                ]);
                $kategori_id = $kategoriModel->id;
            } elseif ($kategoriIdInput && $kategoriIdInput !== 'custom' && $kategoriIdInput !== 'other') {
                $kategoriModel = Kategori::find($kategoriIdInput);
                if (!$kategoriModel) {
                    throw ValidationException::withMessages(['kategori_id' => 'The selected kategori id is invalid.']);
                }
                $kategori_id = $kategoriModel->id;
            } else {
                throw ValidationException::withMessages(['kategori_id' => 'Kategori wajib dipilih atau isi kategori baru.']);
            }

            // Handle file upload (jika ada)
            if ($request->hasFile('file')) {
                // hapus file lama jika ada
                if ($sertifikat->file_path && Storage::disk('public')->exists($sertifikat->file_path)) {
                    Storage::disk('public')->delete($sertifikat->file_path);
                }
                $file = $request->file('file');
                $fileName = time() . '_' . Auth::id() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('sertifikat', $fileName, 'public');
                $sertifikat->file_path = $filePath;
            }

            // Update fields
            $sertifikat->nama_sertifikat = $validated['nama'];
            $sertifikat->institusi_pemberi = $validated['institusi'];
            $sertifikat->tahun_diperoleh = $validated['tahun'];
            $sertifikat->kategori_id = $kategori_id;
            $sertifikat->save();

            DB::commit();

            return redirect()->route('sertifikasi.index')
                ->with('success', 'Sertifikat berhasil diperbarui!');
        } catch (ValidationException $ve) {
            DB::rollBack();
            throw $ve;
        } catch (\Exception $e) {
            DB::rollBack();
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
            if ($sertifikat->file_path && Storage::disk('public')->exists($sertifikat->file_path)) {
                Storage::disk('public')->delete($sertifikat->file_path);
            }

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
