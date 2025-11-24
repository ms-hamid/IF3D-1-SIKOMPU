<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Class UserController
 * @package App\Http\Controllers
 *
 * Controller untuk menangani manajemen data pengguna (users).
 */
class UserController extends Controller
{
    /**
     * GET /api/user
     * Mendapatkan data profil pengguna yang sedang login.
     */
    public function currentUser(Request $request)
    {
        // Pengguna sudah terotentikasi oleh middleware sanctum
        return new UserResource($request->user()->load('roles'));
    }

    /**
     * GET /api/users
     * Mendapatkan daftar semua pengguna (Hanya untuk Admin/Manajer).
     */
    public function index()
    {
        // Tambahkan cek otorisasi di sini (misalnya: $this->authorize('viewAny', User::class);)
        $users = User::with('roles')->paginate(15);
        
        return UserResource::collection($users);
    }

    /**
     * PATCH /api/users/{id}
     * Memperbarui informasi profil pengguna tertentu.
     * Pengguna hanya boleh mengedit profilnya sendiri atau Admin bisa mengedit siapapun.
     */
    public function update(Request $request, User $user)
    {
        // 1. Otorisasi: Pastikan pengguna yang login adalah pemilik profil atau Admin
        if (Auth::id() !== $user->id && !$request->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized. You can only edit your own profile.'], 403);
        }

        // 2. Validasi input
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'jabatan' => 'sometimes|in:Dosen,Laboran',
            'nidn' => ['sometimes', 'nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|nullable|min:8|confirmed', // Jika ingin mengganti password
        ]);

        // 3. Update data
        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            unset($validated['password']); // Hapus dari array update agar tidak di-hash dua kali
        }
        
        $user->fill($validated)->save();

        // 4. Kembalikan data user yang sudah diupdate
        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => new UserResource($user->load('roles')),
        ]);
    }

    public function rolesIndex()
    {
        $roles = \App\Models\Role::all(['id', 'name_peran']); // Asumsi nama kolom di tabel roles adalah name_peran
        return response()->json($roles);
    }

    
}
