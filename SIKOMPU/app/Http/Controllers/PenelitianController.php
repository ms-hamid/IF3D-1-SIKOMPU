<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenelitianController extends Controller
{
    /**
     * Tampilkan halaman view penelitian
     */
    public function viewIndex()
    {
        // Pastikan nama variabel sama dengan yang di compact()
        $penelitians = Auth::user()->penelitians()->get();
        
        return view('pages.penelitian', compact('penelitians'));
    }

    /**
     * Simpan penelitian baru ke database.
     * Method: POST /penelitian
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_penelitian' => 'required|string|max:500',
            'tahun' => 'required|integer|digits:4|min:2000|max:' . date('Y'),
            'peran' => 'required|string|in:Ketua,Anggota', // Hapus Kontributor
            'link_publikasi' => 'nullable|url|max:500',
        ], [
            'judul_penelitian.required' => 'Judul penelitian wajib diisi.',
            'judul_penelitian.max' => 'Judul penelitian maksimal 500 karakter.',
            'tahun.required' => 'Tahun publikasi wajib dipilih.',
            'tahun.digits' => 'Tahun harus 4 digit.',
            'tahun.min' => 'Tahun minimal adalah 2000.',
            'tahun.max' => 'Tahun tidak boleh melebihi tahun sekarang.',
            'peran.required' => 'Peran wajib dipilih.',
            'peran.in' => 'Peran harus salah satu dari: Ketua atau Anggota.', // Update pesan
            'link_publikasi.url' => 'Link publikasi harus berupa URL yang valid.',
        ]);

        // Buat penelitian baru dengan user_id dari user yang login
        $penelitian = Auth::user()->penelitians()->create([
            'judul_penelitian' => $validated['judul_penelitian'],
            'tahun_publikasi' => $validated['tahun'],
            'peran' => $validated['peran'],
            'link_publikasi' => $validated['link_publikasi'] ?? '', // Ubah null jadi ''
        ]);

        return redirect()->route('penelitian.index')
            ->with('success', 'Data penelitian berhasil ditambahkan!');
    }

    /**
     * Update penelitian
     * Method: PATCH /penelitian/{id}
     */
    public function update(Request $request, Penelitian $penelitian)
    {
        // Cek apakah penelitian ini milik user yang login
        if ($penelitian->user_id !== Auth::id()) {
            return redirect()->route('penelitian.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah penelitian ini!');
        }

        $validated = $request->validate([
            'judul_penelitian' => 'required|string|max:500',
            'tahun' => 'required|integer|digits:4|min:2000|max:' . date('Y'),
            'peran' => 'required|string|in:Ketua,Anggota,Kontributor',
            'link_publikasi' => 'nullable|url|max:500',
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
        ]);

        $penelitian->update([
            'judul_penelitian' => $validated['judul_penelitian'],
            'tahun_publikasi' => $validated['tahun'],
            'peran' => $validated['peran'],
            'link_publikasi' => $validated['link_publikasi'] ?? '',
        ]);

        return redirect()->route('penelitian.index')
            ->with('success', 'Data penelitian berhasil diperbarui!');
    }

    /**
     * Hapus penelitian
     * Method: DELETE /penelitian/{id}
     */
    public function destroy(Penelitian $penelitian)
    {
        // Cek apakah penelitian ini milik user yang login
        if ($penelitian->user_id !== Auth::id()) {
            return redirect()->route('penelitian.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus penelitian ini!');
        }

        $penelitian->delete();

        return redirect()->route('penelitian.index')
            ->with('success', 'Data penelitian berhasil dihapus!');
    }
}