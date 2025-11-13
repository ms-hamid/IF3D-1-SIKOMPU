<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Jabatan yang termasuk struktural
     */
    protected $jabatanStruktural = [
        'Kepala Jurusan',
        'Sekretaris Jurusan',
        'Kepala Program Studi'
    ];

    /**
     * Handle proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'NIDN harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        // Cari user berdasarkan NIDN (username)
        $user = User::where('nidn', $credentials['username'])->first();

        // Cek apakah user ditemukan dan password cocok
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Login user
            Auth::login($user);

            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Tentukan redirect berdasarkan jabatan
            $redirectUrl = $this->getRedirectUrl($user);

            // Redirect dengan pesan sukses
            return redirect($redirectUrl)->with('success', 'Selamat datang, ' . $user->nama_lengkap);
        }

        // Jika gagal, kembalikan dengan error
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Tentukan URL redirect berdasarkan jabatan user
     */
    protected function getRedirectUrl($user)
    {
        // Cek apakah jabatan termasuk struktural
        if (in_array($user->jabatan, $this->jabatanStruktural)) {
            return route('dashboard.struktural');
        }

        // Selain itu (Dosen & Laboran) ke dashboard dosen
        return route('dashboard.dosen');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout');
    }
}