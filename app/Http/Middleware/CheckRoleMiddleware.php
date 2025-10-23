<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckRoleMiddleware
 * @package App\Http\Middleware
 * * Middleware kustom untuk mengecek apakah user yang terautentikasi memiliki 
 * peran yang dibutuhkan untuk mengakses rute tertentu.
 */
class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            // Jika belum login, kembalikan 401 Unauthorized
            return response()->json(['message' => 'Unauthorized. Harap login terlebih dahulu.'], 401);
        }

        $user = Auth::user();
        
        // Split roles jika ada lebih dari satu (e.g., 'admin,superadmin')
        $requiredRoles = explode(',', $role);

        // 2. Cek peran menggunakan method hasRole() pada model User
        if (!$user->hasRole($requiredRoles)) {
            // Jika user tidak memiliki peran yang dibutuhkan, kembalikan 403 Forbidden
            return response()->json(['message' => 'Forbidden. Anda tidak memiliki akses sebagai ' . implode(' atau ', $requiredRoles)], 403);
        }

        return $next($request);
    }
}
