<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\GenerateController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardStrukturalController;
use App\Http\Controllers\ManajemenDosenController;
use App\Http\Controllers\ManajemenMatakuliahController;
use App\Models\Post;

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
    return view('auth.login');
})->name('login');

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

// Halaman Ganti Password (frontend dummy)
Route::get('/ganti_password', function () {
    return view('pages.ganti_password');
})->name('ganti_password');

// ============================
// SELF ASSESMENT
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
// MANAJEMEN LAPORAN
// ============================
Route::get('/laporan-dosen', function () {
    return view('pages.laporan-dosen'); // buat file ini nanti
})->name('laporan-dosen');
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');



// ============================
// MANAJEMEN PROGRAM STUDI
// ============================
Route::get('/manajemen-prodi', function () {
    return view('pages.admin.manajemen-prodi'); // buat file ini nanti
})->name('manajemen.prodi');
Route::resource('prodi', ProdiController::class);
Route::get('/prodi/create', function () {
    return view('components.prodi-create'); // ganti dengan nama file blade kamu
})->name('prodi.create');

// ============================
// MANAJEMEN DOSEN
// ============================
Route::get('/manajemen-dosen', function () {
    return view('pages.admin.manajemen-dosen'); // buat file ini nanti
})->name('manajemen.dosen');

// ============================
// MANAJEMEN MATAKULIAH
// ============================
Route::get('/manajemen-matkul', function () {
    return view('pages.admin.manajemen-matkul'); // buat file ini nanti
})->name('manajemen.matkul');
Route::get('tambah_matkul', function () {
    return view('components.tambah_matkul'); // ganti dengan nama file blade kamu
})->name('tambah.matkul');

// ============================
// SELF ASSESEMENT ADMIN
// ============================
Route::get('/self-Assesment', function () {
    return view('pages.admin.self-Assesment'); // buat file ini nanti
})->name('self.Assesment');
// ============================
// SELF ASSESEMENT ADMIN
// ============================
Route::get('/penelitian2', function () {
    return view('pages.admin.penelitian2'); // buat file ini nanti
})->name('penelitian2');
// ============================
// SELF ASSESEMENT ADMIN
// ============================
Route::get('/sertifikat', function () {
    return view('pages.admin.sertifikat'); // buat file ini nanti
})->name('sertifikat');

// ============================
// HASIL REKOMENDASI
// ============================
Route::get('/hasil-rekomendasi', function () {
    return view('pages.admin.hasil-rekomendasi'); // buat file ini nanti
})->name('hasil.rekomendasi');
Route::resource('matakuliah', MatakuliahController::class);
Route::get('/matakuliah/create', [MatakuliahController::class, 'create'])->name('matakuliah.create');

// ============================
// STRUKTURAL JURUSAN (Placeholder)
// ============================
Route::get('/dashboard_struktural', function () {
    return view('pages.admin.dashboard_struktural'); // buat file ini nanti
})->name('dashboard.struktural');

// ============================
// LAPORAN PEFORMA AI
// ============================
Route::get('/peforma-ai', function () {
    return view('pages.admin.peforma-ai'); // buat file ini nanti
})->name('peforma.ai');
Route::get('/performa.ai', [LaporanController::class, 'index'])->name('laporan.index');

// ============================
// LAPORAN DOSEN
// ============================
Route::get('/laporan-dosen', function () {
    return view('pages.laporan-dosen'); // buat file ini nanti
})->name('laporan.dosen');


// ============================
// LAPORAN Struktural
// ============================
Route::get('/laporan-struktural', function () {
    return view('pages.admin.laporan-struktural'); // buat file ini nanti
})->name('laporan.struktural');
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

// ============================
// LOGOUT
// ============================
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); // arahkan ke halaman utama setelah logout
})->name('logout');
