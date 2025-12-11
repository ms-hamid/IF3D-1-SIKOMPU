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

    // Authenticated routes
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/user', [App\Http\Controllers\UserController::class, 'getAuthenticatedUser']);
    Route::patch('/user', [App\Http\Controllers\UserController::class, 'updateProfile']);

    Route::resource('self-assessments', App\Http\Controllers\SelfAssessmentController::class);
    Route::resource('sertifikats', App\Http\Controllers\SertifikatController::class);
    Route::resource('penelitians', App\Http\Controllers\PenelitianController::class);

    Route::resource('prodis', App\Http\Controllers\ProdiController::class)->middleware('check.role:admin');
    Route::resource('mata-kuliahs', App\Http\Controllers\MataKuliahController::class)->middleware('check.role:admin');

    Route::resource('hasil-rekomendasis', App\Http\Controllers\HasilRekomendasiController::class)->only(['index','show']);
    Route::patch('hasil-rekomendasis/{hasilRekomendasi}/finalize', 
        [App\Http\Controllers\HasilRekomendasiController::class, 'finalize']
    )->middleware('check.role:admin');

    Route::get('hasil-rekomendasis/{hasilRekomendasi}/details', 
        [App\Http\Controllers\DetailRekomendasiController::class, 'index']
    );
});
