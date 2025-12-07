<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Kategori; // 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenelitianController extends Controller
{
    /**
     * Tampilkan halaman view penelitian
     */
    public function viewIndex()
    {
    $penelitians = Auth::user()->penelitians()->get();
    $kategori = Kategori::all(); // <-- ambil semua kategori
    return view('pages.penelitian', compact('penelitians', 'kategori'));
    }


    /**
     * Simpan penelitian baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_penelitian' => 'required|string|max:500',
            'tahun' => 'required|integer|digits:4|min:2000|max:' . date('Y'),
            'peran' => 'required|string|in:Ketua,Anggota',
            'link_publikasi' => 'nullable|url|max:500',
            'kategori_id' => 'required',
            'kategori_baru' => 'nullable|string|max:100',
        ], [
            'judul_penelitian.required' => 'Judul penelitian wajib diisi.',
            'judul_penelitian.max' => 'Judul penelitian maksimal 500 karakter.',
            'tahun.required' => 'Tahun publikasi wajib dipilih.',
            'tahun.digits' => 'Tahun harus 4 digit.',
            'tahun.min' => 'Tahun minimal adalah 2000.',
            'tahun.max' => 'Tahun tidak boleh melebihi tahun sekarang.',
            'peran.required' => 'Peran wajib dipilih.',
            'peran.in' => 'Peran harus salah satu dari: Ketua atau Anggota.',
            'link_publikasi.url' => 'Link publikasi harus berupa URL yang valid.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_baru.max' => 'Kategori baru maksimal 100 karakter.',
        ]);

        // Tentukan kategori_id final
        if ($request->kategori_id === 'other' && $request->kategori_baru) {
            $kategori = Kategori::create([
                'nama' => $request->kategori_baru
            ]);
            $kategori_id = $kategori->id;
        } else {
            $kategori_id = $request->kategori_id;
        }

        // Simpan penelitian
        $penelitian = Auth::user()->penelitians()->create([
            'judul_penelitian' => $validated['judul_penelitian'],
            'tahun_publikasi' => $validated['tahun'],
            'peran' => $validated['peran'],
            'link_publikasi' => $validated['link_publikasi'] ?? '',
            'kategori_id' => $kategori_id,
        ]);

        return redirect()->route('penelitian.index')
            ->with('success', 'Data penelitian berhasil ditambahkan!');
    }

    /**
     * Update penelitian
     */
    public function update(Request $request, Penelitian $penelitian)
    {
        if ($penelitian->user_id !== Auth::id()) {
            return redirect()->route('penelitian.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah penelitian ini!');
        }

        $validated = $request->validate([
            'judul_penelitian' => 'required|string|max:500',
            'tahun' => 'required|integer|digits:4|min:2000|max:' . date('Y'),
            'peran' => 'required|string|in:Ketua,Anggota,Kontributor',
            'link_publikasi' => 'nullable|url|max:500',
            'kategori_id' => 'required',
            'kategori_baru' => 'nullable|string|max:100',
        ], [
            'judul_penelitian.required' => 'Judul penelitian wajib diisi.',
            'judul_penelitian.max' => 'Judul penelitian maksimal 500 karakter.',
            'tahun.required' => 'Tahun publikasi wajib dipilih.',
            'tahun.digits' => 'Tahun harus 4 digit.',
            'tahun.min' => 'Tahun minimal adalah 2000.',
            'tahun.max' => 'Tahun tidak boleh melebihi tahun sekarang.',
            'peran.required' => 'Peran wajib dipilih.',
            'peran.in' => 'Peran harus salah satu dari: Ketua, Anggota, atau Kontributor.',
            'link_publikasi.url' => 'Link publikasi harus berupa URL yang valid.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_baru.max' => 'Kategori baru maksimal 100 karakter.',
        ]);

        // Tentukan kategori_id final
        if ($request->kategori_id === 'other' && $request->kategori_baru) {
            $kategori = Kategori::create([
                'nama' => $request->kategori_baru
            ]);
            $kategori_id = $kategori->id;
        } else {
            $kategori_id = $request->kategori_id;
        }

        // Update penelitian
        $penelitian->update([
            'judul_penelitian' => $validated['judul_penelitian'],
            'tahun_publikasi' => $validated['tahun'],
            'peran' => $validated['peran'],
            'link_publikasi' => $validated['link_publikasi'] ?? '',
            'kategori_id' => $kategori_id,
        ]);

        return redirect()->route('penelitian.index')
            ->with('success', 'Data penelitian berhasil diperbarui!');
    }

    /**
     * Hapus penelitian
     */
    public function destroy(Penelitian $penelitian)
    {
        if ($penelitian->user_id !== Auth::id()) {
            return redirect()->route('penelitian.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus penelitian ini!');
        }

        $penelitian->delete();

        return redirect()->route('penelitian.index')
            ->with('success', 'Data penelitian berhasil dihapus!');
    }
}
