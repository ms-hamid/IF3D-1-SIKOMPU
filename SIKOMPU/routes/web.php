<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes - SiKomPu
|--------------------------------------------------------------------------
| Login menggunakan NIDN, redirect otomatis berdasarkan jabatan
*/

// ============================
// PUBLIC ROUTES (Guest Only)
// ============================
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ============================
// AUTHENTICATED ROUTES
// ============================
Route::middleware('auth')->group(function () {
    
    // Logout untuk semua user
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Auto redirect ke dashboard yang sesuai
    Route::get('/dashboard', function () {
        return redirect(auth()->user()->getDashboardUrl());
    })->name('dashboard');
    
    // Ganti Password (Semua User)
    Route::get('/ganti-password', function () {
        return view('pages.ganti_password');
    })->name('ganti_password');
    
    // ============================
    // DASHBOARD & FITUR DOSEN/LABORAN
    // ============================
    Route::middleware('role:Dosen,Laboran')->group(function () {
        
        Route::get('/dashboard-dosen', function () {
            return view('pages.dashboard_dosen');
        })->name('dashboard.dosen');
        
        Route::get('/self-assessment', function () {
            return view('pages.self-assessment');
        })->name('self-assessment.index');
        
        Route::get('/sertifikasi', function () {
            return view('pages.sertifikasi');
        })->name('sertifikasi.index');
        
        Route::get('/penelitian', function () {
            return view('pages.penelitian');
        })->name('penelitian.index');
        
        Route::get('/laporan', function () {
            return view('pages.laporan');
        })->name('laporan.index');
    });
    
    // ============================
    // DASHBOARD & FITUR STRUKTURAL
    // ============================
    Route::middleware('role:Kepala Jurusan,Sekretaris Jurusan,Kepala Program Studi')->group(function () {
        
        Route::get('/dashboard-struktural', function () {
            return view('pages.dashboard_struktural');
        })->name('dashboard.struktural');
        
        // Manajemen Dosen (CRUD lengkap)
        Route::resource('dosen', DosenController::class);
        Route::post('dosen/{dosen}/reset-password', [DosenController::class, 'resetPassword'])
            ->name('dosen.reset-password');
        
        // Manajemen Program Studi
        Route::resource('prodi', ProdiController::class);
        
        // Manajemen Matakuliah
        Route::resource('matakuliah', MataKuliahController::class);
        
        // Hasil Rekomendasi
        Route::get('/hasil-rekomendasi', function () {
            return view('pages.hasil-rekomendasi');
        })->name('hasil.rekomendasi');
        
        // Laporan Struktural
        Route::get('/laporan-struktural', function () {
            return view('pages.laporan-struktural');
        })->name('laporan.struktural');
    });
});