<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardDosenController;
use App\Http\Controllers\DashboardStrukturalController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\PenelitianController;
use App\Http\Controllers\AIIntegrationController;
use App\Http\Controllers\ProfilController;  
use App\Http\Controllers\SelfAssessmentController;
use App\Http\Controllers\HasilRekomendasiController;
use App\Http\Controllers\HasilRekomendasiPageController;

// Testing AI
Route::get('/cek-ai', [AIIntegrationController::class, 'checkConnection']);
Route::get('/generate-hasil', [AIIntegrationController::class, 'generateRecommendation']);

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
    
    // ============================
    // PROFIL & GANTI PASSWORD (SEMUA USER)
    // ============================
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/ganti-password', [ProfilController::class, 'gantiPassword'])->name('ganti_password.update');
    
    // ============================
    // FITUR DOSEN & STRUKTURAL (BERSAMA)
    // ============================
    Route::middleware('role:Dosen,Laboran,Kepala Jurusan,Sekretaris Jurusan,Kepala Program Studi')->group(function () {
        
        // Sertifikasi
        Route::get('sertifikasi', [SertifikatController::class, 'index'])->name('sertifikasi.index');
        Route::post('sertifikasi', [SertifikatController::class, 'store'])->name('sertifikasi.store');
        Route::get('sertifikasi/{id}', [SertifikatController::class, 'show'])->name('sertifikasi.show');
        Route::put('sertifikasi/{id}', [SertifikatController::class, 'update'])->name('sertifikasi.update');
        Route::delete('sertifikasi/{id}', [SertifikatController::class, 'destroy'])->name('sertifikasi.destroy');
        Route::get('sertifikasi/{id}/download', [SertifikatController::class, 'download'])->name('sertifikasi.download');
        
        // Penelitian
        Route::get('/penelitian', [PenelitianController::class, 'viewIndex'])->name('penelitian.index');
        Route::post('/penelitian', [PenelitianController::class, 'store'])->name('penelitian.store');
        Route::patch('/penelitian/{penelitian}', [PenelitianController::class, 'update'])->name('penelitian.update');
        Route::delete('/penelitian/{penelitian}', [PenelitianController::class, 'destroy'])->name('penelitian.destroy');

        // Self-Assessment (Isi Penilaian)
        Route::get('/self-assessment', [SelfAssessmentController::class, 'index'])->name('self-assessment.index');
        Route::post('/self-assessment', [SelfAssessmentController::class, 'store'])->name('self-assessment.store');
    });
    
    // ============================
    // DASHBOARD & FITUR DOSEN/LABORAN
    // ============================
    Route::middleware('role:Dosen,Laboran')->group(function () {
        Route::get('/dashboard-dosen', [DashboardDosenController::class, 'index'])->name('dashboard.dosen');
        Route::get('/laporan', function () {
            return view('pages.laporan');
        })->name('laporan.index');
    });

    // ============================
    // DASHBOARD & FITUR STRUKTURAL
    // ============================
    Route::middleware('role:Kepala Jurusan,Sekretaris Jurusan,Kepala Program Studi')->group(function () {
        
        // Dashboard
        Route::get('/dashboard-struktural', [DashboardStrukturalController::class, 'index'])->name('dashboard.struktural');
        
        // Manajemen Dosen (CRUD lengkap)
        Route::get('/manajemen/dosen', [DosenController::class, 'index'])->name('manajemen.dosen');
        Route::resource('dosen', DosenController::class);
        Route::post('dosen/{dosen}/reset-password', [DosenController::class, 'resetPassword'])->name('dosen.reset-password');
        
        // Manajemen Program Studi
        Route::resource('prodi', ProdiController::class);
        
        // Manajemen Matakuliah
        Route::resource('matakuliah', MataKuliahController::class);
        
        // Hasil Rekomendasi
        Route::get('/hasil-rekomendasi', [HasilRekomendasiPageController::class, 'index'])->name('hasil.rekomendasi');
        // Route::post('/rekomendasi', [HasilRekomendasiController::class, 'generate'])->name('rekomendasi.generate');
        Route::get('/rekomendasi/{id}', [HasilRekomendasiController::class, 'viewDetail'])->name('rekomendasi.detail');

        // Peforma AI
        Route::get('/peforma-ai', function () {
            return view('pages.peforma-ai'); 
        })->name('peforma.ai');
        
        // Self Assessment (Import Data)
        Route::get('/self-assessment/import', [SelfAssessmentController::class, 'importForm'])->name('self-assessment.import.form');
        Route::post('/self-assessment/import', [SelfAssessmentController::class, 'import'])->name('self-assessment.import');

        // Laporan Struktural
        Route::get('/laporan-struktural', function () {
            return view('pages.laporan-struktural');
        })->name('laporan.struktural');
    });
});