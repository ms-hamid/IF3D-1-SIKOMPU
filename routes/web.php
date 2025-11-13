<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\GenerateController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardStrukturalController;
use App\Http\Controllers\ManajemenDosenController;
use App\Http\Controllers\ManajemenMatakuliahController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| File ini berisi semua route utama untuk sistem SiKomPu.
| Fokus untuk dashboard dosen/laboran dan manajemen data.
|--------------------------------------------------------------------------
*/

// ============================
// AUTHENTICATION ROUTES
// ============================
Route::middleware('guest')->group(function () {
    // Halaman login
    Route::get('/', function () {
        return view('auth.login');
    })->name('home');
    
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    // Proses login
    Route::post('/login', [LoginController::class, 'login']);
});

// ============================
// AUTHENTICATED ROUTES
// ============================
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // ============================
    // DASHBOARD DOSEN / LABORAN
    // ============================
    Route::get('/dashboard_dosen', function () {
        // Cek role
        if (!in_array(auth()->user()->jabatan, ['Dosen', 'Laboran'])) {
            abort(403, 'Akses ditolak. Halaman ini khusus Dosen/Laboran.');
        }
        return view('pages.dashboard_dosen');
    })->name('dashboard.dosen');
    
    // Redirect otomatis berdasarkan role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $struktural = ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'];
        
        if (in_array($user->jabatan, $struktural)) {
            return redirect()->route('dashboard.struktural');
        }
        
        return redirect()->route('dashboard.dosen');
    })->name('dashboard');
    
    // Halaman Ganti Password
    Route::get('/ganti_password', function () {
        return view('pages.ganti_password');
    })->name('ganti_password');
    
    // ============================
    // SELF-ASSESSMENT (Dosen/Laboran)
    // ============================
    Route::get('/self-assessment', function () {
        if (!in_array(auth()->user()->jabatan, ['Dosen', 'Laboran'])) {
            abort(403, 'Akses ditolak.');
        }
        return view('pages.self-assessment');
    })->name('self-assessment.index');
    
    // ============================
    // Sertifikasi (Dosen/Laboran)
    // ============================
    Route::get('/sertifikasi', function () {
        if (!in_array(auth()->user()->jabatan, ['Dosen', 'Laboran'])) {
            abort(403, 'Akses ditolak.');
        }
        return view('pages.sertifikasi');
    })->name('sertifikasi.index');
    
    // ============================
    // Penelitian (Dosen/Laboran)
    // ============================
    Route::get('/penelitian', function () {
        if (!in_array(auth()->user()->jabatan, ['Dosen', 'Laboran'])) {
            abort(403, 'Akses ditolak.');
        }
        return view('pages.penelitian');
    })->name('penelitian.index');
    
    // ============================
    // Laporan (Dosen/Laboran)
    // ============================
    Route::get('/laporan', function () {
        if (!in_array(auth()->user()->jabatan, ['Dosen', 'Laboran'])) {
            abort(403, 'Akses ditolak.');
        }
        return view('pages.laporan');
    })->name('laporan.index');
    
    // ============================
    // DASHBOARD STRUKTURAL
    // ============================
    Route::get('/dashboard-struktural', function () {
        // Cek role
        $struktural = ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'];
        if (!in_array(auth()->user()->jabatan, $struktural)) {
            abort(403, 'Akses ditolak. Halaman ini khusus Struktural.');
        }
        return view('pages.dashboard_struktural');
    })->name('dashboard.struktural');
    
    // ============================
    // MANAJEMEN DOSEN (Struktural)
    // ============================
    Route::get('/manajemen-dosen', function () {
        $struktural = ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'];
        if (!in_array(auth()->user()->jabatan, $struktural)) {
            abort(403, 'Akses ditolak.');
        }
        return view('pages.manajemen-dosen');
    })->name('manajemen.dosen');
    
    // ============================
    // MANAJEMEN PROGRAM STUDI (Struktural)
    // ============================
    Route::get('/manajemen-prodi', function () {
        $struktural = ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'];
        if (!in_array(auth()->user()->jabatan, $struktural)) {
            abort(403, 'Akses ditolak.');
        }
        return view('pages.manajemen-prodi');
    })->name('manajemen.prodi');
    
    Route::resource('prodi', ProdiController::class);
    Route::get('/prodi/create', [ProdiController::class, 'create'])->name('prodi.create');
    
    // ============================
    // MANAJEMEN MATAKULIAH (Struktural)
    // ============================
    Route::get('/manajemen-matkul', function () {
        $struktural = ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'];
        if (!in_array(auth()->user()->jabatan, $struktural)) {
            abort(403, 'Akses ditolak.');
        }
        return view('pages.manajemen-matkul');
    })->name('manajemen.matkul');
    
    Route::resource('matakuliah', MatakuliahController::class);
    Route::get('/matakuliah/create', [MatakuliahController::class, 'create'])->name('matakuliah.create');
    
    // ============================
    // HASIL REKOMENDASI (Struktural)
    // ============================
    Route::get('/hasil-rekomendasi', function () {
        $struktural = ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'];
        if (!in_array(auth()->user()->jabatan, $struktural)) {
            abort(403, 'Akses ditolak.');
        }
        return view('pages.hasil-rekomendasi');
    })->name('hasil.rekomendasi');
    
    // ============================
    // LAPORAN STRUKTURAL
    // ============================
    Route::get('/laporan-struktural', function () {
        $struktural = ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'];
        if (!in_array(auth()->user()->jabatan, $struktural)) {
            abort(403, 'Akses ditolak.');
        }
        return view('pages.laporan-struktural');
    })->name('laporan.struktural');
});

// ============================
// TEST ROUTES
// ============================
Route::get('/tes-sidebar', function () {
    return view('components.sidebaradmin');
});