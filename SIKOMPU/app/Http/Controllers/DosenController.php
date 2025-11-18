<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter pencarian nama atau NIDN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%");
            });
        }

        // Filter prodi
        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Order by nama
        $query->orderBy('nama_lengkap', 'asc');

        // Paginate dengan append parameter
        $dosens = $query->paginate(10)->withQueryString();

        return view('pages.manajemen-dosen', compact('dosens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
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

        try {
            // Handle foto upload
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('dosen-photos', 'public');
            }

            // Set default values
            $validated['status'] = 'Aktif';
            $validated['beban_mengajar'] = 0;
            
            // Set max_beban berdasarkan jabatan
            if (in_array($validated['jabatan'], ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'])) {
                $validated['max_beban'] = 12;
            } else {
                $validated['max_beban'] = 16;
            }

            // Password akan otomatis di-hash oleh cast di model
            User::create($validated);

            return redirect()->route('dosen.index')
                ->with('success', 'Data dosen ' . $validated['nama_lengkap'] . ' berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Hapus foto jika ada error
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
        // Validasi input
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

        try {
            // Handle password - hanya update jika diisi
            if (empty($request->password)) {
                unset($validated['password']);
            }

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
                    Storage::disk('public')->delete($dosen->foto);
                }
                $validated['foto'] = $request->file('foto')->store('dosen-photos', 'public');
            }

            // Set max_beban berdasarkan jabatan
            if (in_array($validated['jabatan'], ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'])) {
                $validated['max_beban'] = 12;
            } else {
                $validated['max_beban'] = 16;
            }

            // Jika beban mengajar > max_beban baru, sesuaikan
            if ($dosen->beban_mengajar > $validated['max_beban']) {
                $validated['beban_mengajar'] = $validated['max_beban'];
            }

            $dosen->update($validated);

            return redirect()->route('dosen.index')
                ->with('success', 'Data dosen ' . $validated['nama_lengkap'] . ' berhasil diperbarui!');

        } catch (\Exception $e) {
            // Hapus foto baru jika ada error
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
            
            // Hapus foto jika ada
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
}