<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\GenerateController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| File ini berisi semua route utama untuk sistem SiKomPu.
| Fokus untuk dashboard dosen/laboran dan manajemen data.
|--------------------------------------------------------------------------
*/

// ============================
// HALAMAN UTAMA (Welcome)
// ============================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ============================
// DASHBOARD DOSEN / LABORAN
// ============================
Route::get('/dashboard_dosen', function () {
    return view('pages.dashboard_dosen');
})->name('dashboard.dosen');

// Alias agar route('dashboard') tidak error di sidebar
Route::get('/dashboard', function () {
    return redirect()->route('dashboard.dosen');
})->name('dashboard');

// ============================
// GENERATE HASIL 
// ============================
Route::get('/self-assessment', function () {
    return view('pages.self-assessment');
})->name('self-assessment.index');

// ============================
// Sertifikasi
// ============================
Route::get('/sertifikasi', function () {
    return view('pages.sertifikasi');
})->name('sertifikasi.index');

// ============================
// Penelitian
// ============================
Route::get('/penelitian', function () {
    return view('pages.penelitian');
})->name('penelitian.index');

// Route::prefix('matakuliah')->name('matakuliah.')->group(function () {
//     Route::get('/', [MatakuliahController::class, 'index'])->name('index');
//     Route::get('/create', [MatakuliahController::class, 'create'])->name('create');
//     Route::post('/', [MatakuliahController::class, 'store'])->name('store');
// });

// ============================
// MANAJEMEN PROGRAM STUDI
// ============================
Route::prefix('prodi')->name('prodi.')->group(function () {
    Route::get('/', [ProdiController::class, 'index'])->name('index');
    Route::get('/create', [ProdiController::class, 'create'])->name('create');
    Route::post('/', [ProdiController::class, 'store'])->name('store');
});

// ============================
// STRUKTURAL JURUSAN (Placeholder)
// ============================
Route::get('/struktural', function () {
    return view('pages.struktural.index'); // buat file ini nanti
})->name('struktural.index');

// ============================
// MANAJEMEN LAPORAN
// ============================
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

// ============================
// LOGOUT
// ============================
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); // arahkan ke halaman utama setelah logout
})->name('logout');
