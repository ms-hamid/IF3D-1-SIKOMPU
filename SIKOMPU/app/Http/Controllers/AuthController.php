<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\UserResource;

/**
 * Class AuthController
 * @package App\Http\Controllers
 *
 * Controller untuk menangani endpoint otentikasi (login, logout).
 */
class AuthController extends Controller
{
    /**
     * POST /api/login
     * Melakukan otentikasi pengguna dan menghasilkan token API.
     */
    public function login(Request $request)
    {
        // 1. Validasi input: Menggunakan 'nidn' sebagai username unik
        $request->validate([
            'nidn' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'nidn.required' => 'NIDN wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Coba otentikasi: Menggunakan 'ridn' sebagai kunci
        if (!Auth::attempt($request->only('nidn', 'password'))) {
            throw ValidationException::withMessages([
                'ridn' => ['NIDN atau Password tidak valid.'],
            ]);
        }

        // 3. Jika berhasil, ambil user dan buat token
        $user = Auth::user();

        // Hapus token lama untuk keamanan, lalu buat token baru
        $user->tokens()->where('name', 'auth_token')->delete();
        $token = $user->createToken('auth_token', ['*'])->plainTextToken;

        // 4. Kembalikan respons JSON
        return response()->json([
            'message' => 'Login berhasil.',
            'user' => new UserResource($user->load('roles')), // Menggunakan UserResource
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    /**
     * POST /api/logout
     * Menghapus token otentikasi pengguna saat ini.
     */
    public function logout(Request $request)
    {
        // Menghapus token otentikasi pengguna saat ini (token yang digunakan untuk request ini)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil. Token telah dicabut.'
        ]);
    }
}
