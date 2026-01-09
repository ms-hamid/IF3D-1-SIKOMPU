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
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Laporan2Controller;
use App\Http\Controllers\AiMetricsController;
use App\Http\Controllers\NotificationController;

// ============================
// TESTING AI
// ============================
Route::get('/cek-ai', [AIIntegrationController::class, 'checkConnection']);
Route::get('/generate-hasil', [AIIntegrationController::class, 'generateRecommendation']);

// ============================
// PUBLIC ROUTES (GUEST)
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

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return redirect(auth()->user()->getDashboardUrl());
    })->name('dashboard');

    // ============================
    // PROFIL (SEMUA ROLE)
    // ============================
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/ganti-password', [ProfilController::class, 'gantiPassword'])->name('ganti_password.update');

    // ============================
    // NOTIFIKASI (SEMUA ROLE) 
    // ============================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // Halaman semua notifikasi
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        // AJAX: Get notifications untuk dropdown
        Route::get('/get', [NotificationController::class, 'getNotifications']);
        // Mark as read
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        // Mark all as read
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
        // Delete notification
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
    });

    // ============================
    // FITUR BERSAMA (DOSEN & STRUKTURAL)
    // ============================
    Route::middleware('role:Dosen,Laboran,Kepala Jurusan,Sekretaris Jurusan,Kepala Program Studi')->group(function () {

        // Sertifikasi
        Route::resource('sertifikasi', SertifikatController::class);
        Route::get('sertifikasi/{id}/download', [SertifikatController::class, 'download'])->name('sertifikasi.download');

        // Penelitian
        Route::resource('penelitian', PenelitianController::class)->except(['create', 'edit']);

        // Self Assessment (Isi)
        Route::get('/self-assessment', [SelfAssessmentController::class, 'index'])->name('self-assessment.index');
        Route::post('/self-assessment', [SelfAssessmentController::class, 'store'])->name('self-assessment.store');
    });

    // ============================
    // DOSEN & LABORAN
    // ============================
    Route::middleware('role:Dosen,Laboran')->group(function () {
        Route::get('/dashboard-dosen', [DashboardDosenController::class, 'index'])->name('dashboard.dosen');
        Route::get('/laporan-dosen', [Laporan2Controller::class, 'index'])
            ->name('laporan.dosen');

    });

    // ============================
    // STRUKTURAL
    // ============================
    Route::middleware('role:Kepala Jurusan,Sekretaris Jurusan,Kepala Program Studi')->group(function () {

        Route::get('/dashboard-struktural', [DashboardStrukturalController::class, 'index'])->name('dashboard.struktural');

        Route::resource('dosen', DosenController::class);
        Route::post('dosen/{dosen}/reset-password', [DosenController::class, 'resetPassword'])->name('dosen.reset-password');
        
        // ROUTE IMPORT & EXPORT DOSEN
        Route::post('dosen-import', [DosenController::class, 'import'])->name('dosen.import');
        Route::get('dosen-template', [DosenController::class, 'downloadTemplate'])->name('dosen.template');
        Route::get('dosen-export-excel', [DosenController::class, 'exportExcel'])->name('dosen.export.excel');
        Route::get('dosen-export-pdf', [DosenController::class, 'exportPdf'])->name('dosen.export.pdf');

        Route::resource('prodi', ProdiController::class);
        
        // Route matakuliah
        Route::resource('matakuliah', MataKuliahController::class);
        Route::post('matakuliah-import', [MataKuliahController::class, 'import'])->name('matakuliah.import');
        Route::get('matakuliah-template', [MataKuliahController::class, 'downloadTemplate'])->name('matakuliah.template');
        Route::get('matakuliah-export-excel', [MataKuliahController::class, 'exportExcel'])->name('matakuliah.export.excel');

        // Rekomendasi AI
        Route::get('/hasil-rekomendasi', [HasilRekomendasiPageController::class, 'index'])->name('hasil.rekomendasi');
        Route::post('/hasil-rekomendasi/generate', [HasilRekomendasiController::class, 'generate'])->name('hasil-rekomendasi.generate');
        Route::get('/hasil-rekomendasi/{id}/detail/{kode_mk}', [HasilRekomendasiPageController::class, 'detailMk'])
            ->name('hasil-rekomendasi.detail');
        Route::get('/hasil-rekomendasi/export/excel', [HasilRekomendasiPageController::class, 'exportExcel'])
            ->name('hasil-rekomendasi.export.excel');
        Route::get('/hasil-rekomendasi/export/pdf', [HasilRekomendasiPageController::class, 'exportPdf'])
            ->name('hasil-rekomendasi.export.pdf'); 

        // Import Self Assessment
        Route::get('/self-assessment/import', [SelfAssessmentController::class, 'importForm'])->name('self-assessment.import.form');
        Route::post('/self-assessment/import', [SelfAssessmentController::class, 'import'])->name('self-assessment.import');

        // Laporan Struktural
        Route::get('/laporan-struktural', [LaporanController::class, 'index'])->name('laporan.struktural');

        // ===== REKAP FINAL PENGAMPU =====
        Route::get('/laporan/rekap-pengampu/excel', [LaporanController::class, 'exportRekapPengampuExcel'])
            ->name('laporan.rekap-pengampu.excel');
        Route::get('/laporan/rekap-pengampu/pdf', [LaporanController::class, 'exportRekapPengampuPdf'])
            ->name('laporan.rekap-pengampu.pdf');
    });
});