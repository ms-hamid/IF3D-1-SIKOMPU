<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'NIDN harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        // Cari user berdasarkan NIDN
        $user = User::where('nidn', $request->username)->first();

        // Cek apakah user ditemukan
        if (!$user) {
            return back()->withErrors([
                'username' => 'NIDN tidak ditemukan dalam sistem.',
            ])->withInput($request->only('username'));
        }

        // Cek status aktif
        if ($user->status !== 'Aktif') {
            return back()->withErrors([
                'username' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ])->withInput($request->only('username'));
        }

        // Attempt login dengan NIDN sebagai username
        $credentials = [
            'nidn' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Log activity
            \Log::info('User logged in: ' . $user->nama_lengkap . ' (' . $user->nidn . ')');

            // Redirect berdasarkan role menggunakan helper method
            $redirectUrl = $user->getDashboardUrl();

            return redirect()->intended($redirectUrl)
                ->with('success', 'Selamat datang, ' . $user->nama_lengkap . '!');
        }

        // Login failed - password salah
        return back()->withErrors([
            'username' => 'NIDN atau password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $userName = Auth::user()->nama_lengkap ?? 'User';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout. Sampai jumpa!');
    }
}