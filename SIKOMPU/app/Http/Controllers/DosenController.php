<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Services\DosenImportExportService;

class DosenController extends Controller
{
    // Inject service via constructor (Dependency Injection)
    protected $importExportService;

    public function __construct(DosenImportExportService $importExportService)
    {
        $this->importExportService = $importExportService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('nama_lengkap', 'asc');
        $dosens = $query->paginate(10)->withQueryString();

        return view('pages.manajemen-dosen', compact('dosens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nidn' => [
                'required',
                'string',
                'unique:users,nidn',
                'regex:/^[0-9]+$/'
            ],
            'prodi' => 'required|string',
            'jabatan' => 'required|in:Kepala Jurusan,Sekretaris Jurusan,Kepala Program Studi,Dosen,Laboran',
            'password' => 'required|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nidn.required' => 'NIDN/NIP wajib diisi',
            'nidn.unique' => 'NIDN/NIP sudah terdaftar dalam sistem',
            'nidn.regex' => 'NIDN/NIP harus berupa angka',
            'prodi.required' => 'Program studi wajib dipilih',
            'jabatan.required' => 'Jabatan wajib dipilih',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPEG, PNG, atau JPG',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        // ✅ VALIDASI TAMBAHAN: Hanya 1 Kaprodi per prodi
        if ($validated['jabatan'] === 'Kepala Program Studi') {
            $existingKaprodi = User::where('prodi', $validated['prodi'])
                ->where('jabatan', 'Kepala Program Studi')
                ->exists();
            
            if ($existingKaprodi) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jabatan' => 'Prodi ' . $validated['prodi'] . ' sudah memiliki Kepala Program Studi!']);
            }
        }

        // ✅ VALIDASI TAMBAHAN: Hanya 1 Kajur per prodi (opsional, tergantung kebutuhan)
        if ($validated['jabatan'] === 'Kepala Jurusan') {
            $existingKajur = User::where('prodi', $validated['prodi'])
                ->where('jabatan', 'Kepala Jurusan')
                ->exists();
            
            if ($existingKajur) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jabatan' => 'Prodi ' . $validated['prodi'] . ' sudah memiliki Kepala Jurusan!']);
            }
        }

        try {
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('dosen-photos', 'public');
            }

            $validated['status'] = 'Aktif';
            $validated['beban_mengajar'] = 0;
            
            if (in_array($validated['jabatan'], ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'])) {
                $validated['max_beban'] = 12;
            } else {
                $validated['max_beban'] = 16;
            }

            User::create($validated);

            return redirect()->route('dosen.index')
                ->with('success', 'Data dosen ' . $validated['nama_lengkap'] . ' berhasil ditambahkan!');

        } catch (\Exception $e) {
            if (isset($validated['foto'])) {
                Storage::disk('public')->delete($validated['foto']);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan data dosen: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $dosen)
    {
        return view('pages.detail-dosen', compact('dosen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $dosen)
    {
        return view('pages.edit_dosen', compact('dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $dosen)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nidn' => [
                'required',
                'string',
                Rule::unique('users', 'nidn')->ignore($dosen->id),
                'regex:/^[0-9]+$/'
            ],
            'prodi' => 'required|string',
            'jabatan' => 'required|in:Kepala Jurusan,Sekretaris Jurusan,Kepala Program Studi,Dosen,Laboran',
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nidn.required' => 'NIDN/NIP wajib diisi',
            'nidn.unique' => 'NIDN/NIP sudah terdaftar dalam sistem',
            'nidn.regex' => 'NIDN/NIP harus berupa angka',
            'password.min' => 'Password minimal 6 karakter',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        // ✅ VALIDASI TAMBAHAN: Hanya 1 Kaprodi per prodi (kecuali yang sedang diedit)
        if ($validated['jabatan'] === 'Kepala Program Studi') {
            $existingKaprodi = User::where('prodi', $validated['prodi'])
                ->where('jabatan', 'Kepala Program Studi')
                ->where('id', '!=', $dosen->id)
                ->exists();
            
            if ($existingKaprodi) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jabatan' => 'Prodi ' . $validated['prodi'] . ' sudah memiliki Kepala Program Studi!']);
            }
        }

        // ✅ VALIDASI TAMBAHAN: Hanya 1 Kajur per prodi (kecuali yang sedang diedit)
        if ($validated['jabatan'] === 'Kepala Jurusan') {
            $existingKajur = User::where('prodi', $validated['prodi'])
                ->where('jabatan', 'Kepala Jurusan')
                ->where('id', '!=', $dosen->id)
                ->exists();
            
            if ($existingKajur) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jabatan' => 'Prodi ' . $validated['prodi'] . ' sudah memiliki Kepala Jurusan!']);
            }
        }

        try {
            if (empty($request->password)) {
                unset($validated['password']);
            }

            if ($request->hasFile('foto')) {
                if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
                    Storage::disk('public')->delete($dosen->foto);
                }
                $validated['foto'] = $request->file('foto')->store('dosen-photos', 'public');
            }

            if (in_array($validated['jabatan'], ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'])) {
                $validated['max_beban'] = 12;
            } else {
                $validated['max_beban'] = 16;
            }

            if ($dosen->beban_mengajar > $validated['max_beban']) {
                $validated['beban_mengajar'] = $validated['max_beban'];
            }

            $dosen->update($validated);

            return redirect()->route('dosen.index')
                ->with('success', 'Data dosen ' . $validated['nama_lengkap'] . ' berhasil diperbarui!');

        } catch (\Exception $e) {
            if (isset($validated['foto']) && $validated['foto'] != $dosen->foto) {
                Storage::disk('public')->delete($validated['foto']);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui data dosen: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $dosen)
    {
        try {
            $nama = $dosen->nama_lengkap;
            
            if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
                Storage::disk('public')->delete($dosen->foto);
            }

            $dosen->delete();

            return redirect()->route('dosen.index')
                ->with('success', "Data dosen {$nama} berhasil dihapus!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menghapus data dosen: ' . $e->getMessage()]);
        }
    }

    /**
     * Reset password dosen
     */
    public function resetPassword(Request $request, User $dosen)
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:6|confirmed'
        ], [
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        try {
            $dosen->update([
                'password' => $validated['new_password']
            ]);

            return redirect()->back()
                ->with('success', 'Password dosen ' . $dosen->nama_lengkap . ' berhasil direset!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Gagal mereset password: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle status aktif/tidak aktif
     */
    public function toggleStatus(User $dosen)
    {
        try {
            $newStatus = $dosen->status == 'Aktif' ? 'Tidak Aktif' : 'Aktif';
            $dosen->update(['status' => $newStatus]);

            return redirect()->back()
                ->with('success', 'Status dosen ' . $dosen->nama_lengkap . ' berhasil diubah menjadi ' . $newStatus);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Gagal mengubah status: ' . $e->getMessage()]);
        }
    }

    // ============================
    // IMPORT & EXPORT METHODS
    // ============================

    /**
     * Import data dosen dari Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file_import' => 'required|file|mimes:xlsx,xls|max:2048'
        ], [
            'file_import.required' => 'File Excel wajib diupload',
            'file_import.mimes' => 'File harus berformat .xlsx atau .xls',
            'file_import.max' => 'Ukuran file maksimal 2MB'
        ]);

        try {
            $file = $request->file('file_import');
            
            // PANGGIL SERVICE - Logic ada di service!
            $result = $this->importExportService->import($file->getRealPath());

            // Handle response berdasarkan status
            if ($result['status'] === 'success') {
                return redirect()->route('dosen.index')
                    ->with('success', $result['message']);
            }

            if ($result['status'] === 'warning') {
                return redirect()->route('dosen.index')
                    ->with('warning', $result['message'])
                    ->with('import_errors', $result['errors']);
            }

            return redirect()->route('dosen.index')
                ->withErrors(['import' => $result['message']])
                ->with('import_errors', $result['errors']);

        } catch (\Exception $e) {
            return redirect()->route('dosen.index')
                ->withErrors(['import' => 'Gagal mengimpor data: ' . $e->getMessage()]);
        }
    }

    /**
     * Download template Excel kosong
     */
    public function downloadTemplate()
    {
        // PANGGIL SERVICE - Logic ada di service!
        $template = $this->importExportService->generateTemplate();
        
        return response()->download($template['path'], $template['filename'])->deleteFileAfterSend(true);
    }

    /**
     * Export data dosen ke Excel
     */
    public function exportExcel(Request $request)
    {
        $query = User::query();

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dosens = $query->orderBy('nama_lengkap', 'asc')->get();

        // PANGGIL SERVICE - Logic ada di service!
        $export = $this->importExportService->exportToExcel($dosens);
        
        return response()->download($export['path'], $export['filename'])->deleteFileAfterSend(true);
    }
}