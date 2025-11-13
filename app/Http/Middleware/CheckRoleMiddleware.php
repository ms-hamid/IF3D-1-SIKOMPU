<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckRoleMiddleware
 * @package App\Http\Middleware
 * Middleware kustom untuk mengecek apakah user yang terautentikasi memiliki 
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
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        $user = Auth::user();
        
        // Split roles jika ada lebih dari satu (e.g., 'Dosen,Laboran')
        $requiredRoles = explode(',', $role);

        // 2. Cek peran menggunakan method hasRole() pada model User
        if (!$user->hasRole($requiredRoles)) {
            // Redirect ke dashboard sesuai role mereka
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}