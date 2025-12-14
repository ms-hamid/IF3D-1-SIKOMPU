<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HasilRekomendasiController;
use App\Http\Controllers\AIIntegrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SelfAssessmentController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\PenelitianController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\DetailRekomendasiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Public Routes (Tidak Memerlukan Auth)
|--------------------------------------------------------------------------
*/


// AI endpoint untuk menyimpan hasil rekomendasi
Route::post('/ai/hasil-rekomendasi', [AIIntegrationController::class, 'generateRecommendation']);

// Login (public)
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Memerlukan Auth Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Logout & user
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
    Route::patch('/user', [UserController::class, 'updateProfile']);

    // Self Assessment, Sertifikat, Penelitian
    Route::resource('self-assessments', SelfAssessmentController::class);
    Route::resource('sertifikats', SertifikatController::class);
    Route::resource('penelitians', PenelitianController::class);

    // Prodi & Mata Kuliah (admin only)
    Route::resource('prodis', ProdiController::class)->middleware('check.role:admin');
    Route::resource('mata-kuliahs', MataKuliahController::class)->middleware('check.role:admin');

    // Hasil rekomendasi untuk admin (lihat & finalize)
    Route::resource('hasil-rekomendasis', HasilRekomendasiController::class)->only(['index','show']);
    Route::patch(
        'hasil-rekomendasis/{hasilRekomendasi}/finalize',
        [HasilRekomendasiController::class, 'finalize']
    )->middleware('check.role:admin');

    // Detail rekomendasi
    Route::get(
        'hasil-rekomendasis/{hasilRekomendasi}/details',
        [DetailRekomendasiController::class, 'index']
    );
});
