<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardDosenController;
use App\Http\Controllers\DashboardStrukturalController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\PenelitianController;
use App\Http\Controllers\AIIntegrationController;
use App\Http\Controllers\ProfilController;  

Route::get('/cek-ai', [AIIntegrationController::class, 'checkConnection']);
Route::get('/generate-hasil', [AIIntegrationController::class, 'generateRecommendation']);

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
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
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
    
    // ⭐ PROFIL & GANTI PASSWORD (SEMUA USER BISA AKSES)
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/ganti-password', [ProfilController::class, 'gantiPassword'])->name('ganti_password.update');
    
    // ============================
    // DASHBOARD & FITUR DOSEN/LABORAN
    // ============================
    Route::middleware('role:Dosen,Laboran')->group(function () {
        
        Route::get('/dashboard-dosen', [DashboardDosenController::class, 'index'])
            ->name('dashboard.dosen');
        
        Route::get('/self-assessment', function () {
            return view('pages.self-assessment');
        })->name('self-assessment.index');
        
        // Sertifikasi - CRUD lengkap
        Route::get('sertifikasi', [SertifikatController::class, 'index'])->name('sertifikasi.index');
        Route::post('sertifikasi', [SertifikatController::class, 'store'])->name('sertifikasi.store');
        Route::get('sertifikasi/{id}', [SertifikatController::class, 'show'])->name('sertifikasi.show');
        Route::put('sertifikasi/{id}', [SertifikatController::class, 'update'])->name('sertifikasi.update');
        Route::delete('sertifikasi/{id}', [SertifikatController::class, 'destroy'])->name('sertifikasi.destroy');
        Route::get('sertifikasi/{id}/download', [SertifikatController::class, 'download'])
            ->name('sertifikasi.download');
        
        // Penelitian - CRUD lengkap
        Route::get('/penelitian', [PenelitianController::class, 'viewIndex'])->name('penelitian.index');
        Route::post('/penelitian', [PenelitianController::class, 'store'])->name('penelitian.store');
        Route::patch('/penelitian/{penelitian}', [PenelitianController::class, 'update'])->name('penelitian.update');
        Route::delete('/penelitian/{penelitian}', [PenelitianController::class, 'destroy'])->name('penelitian.destroy');
        
        Route::get('/laporan', function () {
            return view('pages.laporan');
        })->name('laporan.index');
    });
    
    // ============================
    // DASHBOARD & FITUR STRUKTURAL
    // ============================
    Route::middleware('role:Kepala Jurusan,Sekretaris Jurusan,Kepala Program Studi')->group(function () {
        
        Route::get('/dashboard-struktural', [DashboardStrukturalController::class, 'index'])
            ->name('dashboard.struktural');
        
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

        //peforma Ai
         Route::get('/peforma-ai', function () {
            return view('pages.admin.peforma-ai'); // buat file ini nanti
        })->name('peforma.ai');
        Route::get('/performa.ai', [LaporanController::class, 'index'])->name('laporan.index')
            
        // Laporan Struktural
        Route::get('/laporan-struktural', function () {
            return view('pages.laporan-struktural');
        })->name('laporan.struktural');

        
    });
});
