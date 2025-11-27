<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rute Publik (Tanpa Otentikasi)
// --------------------------------------------------------------------------
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);


// Rute yang Dilindungi (Memerlukan Otentikasi Sanctum)
// --------------------------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    
    // 1. Rute Auth & User
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/user', [App\Http\Controllers\UserController::class, 'getAuthenticatedUser']);
    Route::patch('/user', [App\Http\Controllers\UserController::class, 'updateProfile']);
    
    // 2. Resource Rute: Input Data Dosen/Laboran (Akses Penuh CRUD)
    Route::resource('self-assessments', App\Http\Controllers\SelfAssessmentController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('sertifikats', App\Http\Controllers\SertifikatController::class);
    Route::resource('penelitians', App\Http\Controllers\PenelitianController::class);

    // 3. Resource Rute: Data Master (Dibatasi hanya untuk Admin/Dosen Struktural)
    Route::resource('prodis', App\Http\Controllers\ProdiController::class)->middleware('check.role:admin');
    Route::resource('mata-kuliahs', App\Http\Controllers\MataKuliahController::class)->middleware('check.role:admin');
    
    // 4. Rute HASIL REKOMENDASI & Penetapan (Viewable oleh semua, Finalize oleh Admin)
    
    // Melihat daftar dan detail hasil rekomendasi
    Route::resource('hasil-rekomendasis', App\Http\Controllers\HasilRekomendasiController::class)->only(['index', 'show']);
    
    // Aksi Penetapan Rekomendasi Koordinator/Pengampu (Hanya Admin)
    Route::patch('hasil-rekomendasis/{hasilRekomendasi}/finalize', [App\Http\Controllers\HasilRekomendasiController::class, 'finalize'])->middleware('check.role:admin');
    
    // Rute untuk melihat Detail Skor Rekomendasi
    Route::get('hasil-rekomendasis/{hasilRekomendasi}/details', [App\Http\Controllers\DetailRekomendasiController::class, 'index']);

});
