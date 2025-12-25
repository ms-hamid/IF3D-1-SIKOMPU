<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Pendidikan;

class ProfilController extends Controller
{
    /**
     * Tampilkan halaman profil (gabungan profil + ganti password)
     */
    public function index()
    {
        $user = Auth::user();
        $pendidikanTerakhir = $user->pendidikans()->latest()->first(); // ambil pendidikan terakhir
        return view('pages.profil', compact('user', 'pendidikanTerakhir'));
    }

    /**
     * Update profil user dan pendidikan terakhir
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // -----------------------------
        // VALIDASI PROFIL
        // -----------------------------
        $validatedUser = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nidn' => 'required|string|unique:users,nidn,' . $user->id,
            'prodi' => 'nullable|string|max:255',
        ]);

        // Update data user
        $user->update($validatedUser);

        // -----------------------------
        // VALIDASI PENDIDIKAN
        // -----------------------------
        if ($request->filled(['jenjang', 'jurusan', 'universitas', 'tahun_lulus'])) {
            $validatedPendidikan = $request->validate([
                'jenjang' => 'required|string|max:50',
                'jurusan' => 'required|string|max:255',
                'universitas' => 'required|string|max:255',
                'tahun_lulus' => 'required|integer|min:1900|max:' . date('Y'),
            ]);

            // Ambil pendidikan terakhir
            $pendidikanTerakhir = $user->pendidikans()->latest()->first();

            if ($pendidikanTerakhir) {
                // Update pendidikan terakhir
                $pendidikanTerakhir->update($validatedPendidikan);
            } else {
                // Buat record baru jika belum ada
                $user->pendidikans()->create($validatedPendidikan);
            }
        }

        return redirect()->route('profil.index')
            ->with('success', 'Profil dan pendidikan berhasil diperbarui!');
    }

    /**
     * Proses ganti password
     */
    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password_lama.required' => 'Password lama harus diisi',
            'password_baru.required' => 'Password baru harus diisi',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok',
            'password_baru.min' => 'Password minimal 8 karakter',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai'
            ])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return redirect()->route('profil.index')
            ->with('success', 'Password berhasil diubah!');
    }
}
