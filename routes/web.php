<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\SelfAssessmentController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\PenelitianController;
use App\Http\Controllers\HasilRekomendasiController;
use App\Http\Controllers\DetailRekomendasiController;

// Rute Publik (Tanpa Otentikasi)
Route::get('/', function () {
    return view('welcome');
});

// Rute Auth
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Rute yang Dilindungi (Memerlukan Otentikasi)
Route::middleware('auth')->group(function () {

    // Dashboard berdasarkan role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole(['admin', 'struktural'])) {
            return view('pages.dashboard_struktural');
        }
        return view('pages.dashboard_dosen');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil User
    Route::get('/profile', [UserController::class, 'currentUser'])->name('profile');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');

    // Manajemen Prodi (Admin only)
    Route::middleware('check.role:admin')->group(function () {
        Route::get('/manajemen-prodi', function () {
            return view('pages.manajemen_prodi');
        })->name('prodi.index');
        Route::get('/manajemen-prodi/create', function () {
            return view('components.prodi.create');
        })->name('prodi.create');
        Route::get('/manajemen-prodi/{prodi}/edit', function () {
            return view('components.prodi.edit');
        })->name('prodi.edit');
    });

    // Manajemen Mata Kuliah (Admin only)
    Route::middleware('check.role:admin')->group(function () {
        Route::get('/manajemen-matakuliah', function () {
            return view('pages.manajemen_matakuliah');
        })->name('matakuliah.index');
        Route::get('/manajemen-matakuliah/create', function () {
            return view('components.matakuliah.create');
        })->name('matakuliah.create');
        Route::get('/manajemen-matakuliah/{mataKuliah}/edit', function () {
            return view('components.matakuliah.edit');
        })->name('matakuliah.edit');
    });

    // Manajemen Dosen (Admin only)
    Route::middleware('check.role:admin')->group(function () {
        Route::get('/manajemen-dosen', function () {
            return view('pages.manajemen_dosen');
        })->name('dosen.index');
        Route::get('/manajemen-dosen/create', function () {
            return view('components.dosen.create');
        })->name('dosen.create');
        Route::get('/manajemen-dosen/{user}/edit', function () {
            return view('components.dosen.edit');
        })->name('dosen.edit');
    });

    // Input Data Dosen/Laboran
    Route::prefix('data')->name('data.')->group(function () {
        // Self Assessment
        Route::get('/self-assessment', function () {
            return view('components.self-assessment.index');
        })->name('self-assessment.index');
        Route::get('/self-assessment/create', function () {
            return view('components.self-assessment.create');
        })->name('self-assessment.create');

        // Sertifikat
        Route::get('/sertifikat', function () {
            return view('components.sertifikat.index');
        })->name('sertifikat.index');
        Route::get('/sertifikat/create', function () {
            return view('components.sertifikat.create');
        })->name('sertifikat.create');
        Route::get('/sertifikat/{sertifikat}/edit', function () {
            return view('components.sertifikat.edit');
        })->name('sertifikat.edit');

        // Penelitian
        Route::get('/penelitian', function () {
            return view('components.penelitian.index');
        })->name('penelitian.index');
        Route::get('/penelitian/create', function () {
            return view('components.penelitian.create');
        })->name('penelitian.create');
        Route::get('/penelitian/{penelitian}/edit', function () {
            return view('components.penelitian.edit');
        })->name('penelitian.edit');
    });

    // Generate Hasil Rekomendasi (Admin only)
    Route::middleware('check.role:admin')->group(function () {
        Route::get('/generate-hasil', function () {
            return view('pages.generate_hasil');
        })->name('generate.index');
    });

    // Laporan Hasil Rekomendasi
    Route::get('/laporan', function () {
        return view('pages.laporan');
    })->name('laporan.index');
    Route::get('/laporan/{hasilRekomendasi}', function () {
        return view('components.laporan.detail');
    })->name('laporan.detail');

    // API-like routes untuk AJAX (mengarah ke controllers)
    Route::prefix('api')->name('api.')->group(function () {
        // User management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::get('/roles', [UserController::class, 'rolesIndex'])->name('roles.index');

        // Prodi CRUD
        Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi.index');
        Route::post('/prodi', [ProdiController::class, 'store'])->name('prodi.store');
        Route::patch('/prodi/{prodi}', [ProdiController::class, 'update'])->name('prodi.update');
        Route::delete('/prodi/{prodi}', [ProdiController::class, 'destroy'])->name('prodi.destroy');

        // Mata Kuliah CRUD
        Route::get('/mata-kuliah', [MataKuliahController::class, 'index'])->name('matakuliah.index');
        Route::post('/mata-kuliah', [MataKuliahController::class, 'store'])->name('matakuliah.store');
        Route::get('/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'show'])->name('matakuliah.show');
        Route::patch('/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'update'])->name('matakuliah.update');
        Route::delete('/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'destroy'])->name('matakuliah.destroy');

        // Self Assessment CRUD
        Route::post('/self-assessments', [SelfAssessmentController::class, 'store'])->name('self-assessments.store');
        Route::get('/self-assessments', [SelfAssessmentController::class, 'index'])->name('self-assessments.index');
        Route::delete('/self-assessments/{selfAssessment}', [SelfAssessmentController::class, 'destroy'])->name('self-assessments.destroy');

        // Sertifikat CRUD
        Route::get('/sertifikats', [SertifikatController::class, 'index'])->name('sertifikats.index');
        Route::post('/sertifikats', [SertifikatController::class, 'store'])->name('sertifikats.store');
        Route::get('/sertifikats/{sertifikat}', [SertifikatController::class, 'show'])->name('sertifikats.show');
        Route::patch('/sertifikats/{sertifikat}', [SertifikatController::class, 'update'])->name('sertifikats.update');
        Route::delete('/sertifikats/{sertifikat}', [SertifikatController::class, 'destroy'])->name('sertifikats.destroy');

        // Penelitian CRUD
        Route::get('/penelitians', [PenelitianController::class, 'index'])->name('penelitians.index');
        Route::post('/penelitians', [PenelitianController::class, 'store'])->name('penelitians.store');
        Route::get('/penelitians/{penelitian}', [PenelitianController::class, 'show'])->name('penelitians.show');
        Route::patch('/penelitians/{penelitian}', [PenelitianController::class, 'update'])->name('penelitians.update');
        Route::delete('/penelitians/{penelitian}', [PenelitianController::class, 'destroy'])->name('penelitians.destroy');

        // Hasil Rekomendasi
        Route::get('/hasil-rekomendasis', [HasilRekomendasiController::class, 'index'])->name('hasil-rekomendasis.index');
        Route::get('/hasil-rekomendasis/{hasilRekomendasi}', [HasilRekomendasiController::class, 'show'])->name('hasil-rekomendasis.show');
        Route::patch('/hasil-rekomendasis/{hasilRekomendasi}/finalize', [HasilRekomendasiController::class, 'finalize'])->name('hasil-rekomendasis.finalize');
        Route::get('/hasil-rekomendasis/{hasilRekomendasi}/details', [DetailRekomendasiController::class, 'index'])->name('hasil-rekomendasis.details');
    });
});
