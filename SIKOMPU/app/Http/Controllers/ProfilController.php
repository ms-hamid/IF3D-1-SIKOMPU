<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /**
     * Tampilkan halaman profil (gabungan profil + ganti password)
     */
    public function index()
    {
        $user = Auth::user();
        return view('pages.profil', compact('user'));
    }

    /**
     * Update profil user
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nidn' => 'required|string|unique:users,nidn,' . $user->id,
            'prodi' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Upload foto baru
            $fotoPath = $request->file('foto')->store('profil', 'public');
            $validated['foto'] = $fotoPath;
        }

        // Update data user
        $user->update($validated);

        return redirect()->route('profil.index')
            ->with('success', 'Profil berhasil diperbarui!');
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

        // Cek apakah password lama benar
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai'
            ])->withInput();
        }

        // Update password baru
        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return redirect()->route('profil.index')
            ->with('success', 'Password berhasil diubah!');
    }
}