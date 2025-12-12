<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\SelfAssessment;

class DashboardStrukturalController extends Controller
{
    public function index()
{
    $user = Auth::user();
    
    // Hitung statistik
    $totalPengguna = User::count();
    $totalDosen = User::whereIn('jabatan', ['Dosen', 'Laboran'])->count();
    $totalMataKuliah = MataKuliah::count();
    
    // Hitung persentase self-assessment
    $totalSelfAssessment = SelfAssessment::count();
    $totalDosenYangHarusIsi = User::whereIn('jabatan', ['Dosen', 'Laboran'])->count();
    $persentaseSelfAssessment = $totalDosenYangHarusIsi > 0 
        ? round(($totalSelfAssessment / $totalDosenYangHarusIsi) * 100) 
        : 0;

    // -----------------------------
    // Cek apakah modal profil perlu ditampilkan
    // -----------------------------
    $showModal = false;

    // Cek jika password masih default (sesuaikan default password di sistemmu)
    if (password_verify('password123', $user->password)) {
        $showModal = true;
    }

    // Cek apakah pendidikan terakhir kosong
    $pendidikanTerakhir = $user->pendidikans()->latest()->first();
    if (!$pendidikanTerakhir) {
        $showModal = true;
    }

    return view('pages.dashboard_struktural', [
        'user' => $user,
        'totalPengguna' => $totalPengguna,
        'totalDosen' => $totalDosen,
        'totalMataKuliah' => $totalMataKuliah,
        'persentaseSelfAssessment' => $persentaseSelfAssessment,
        'showModal' => $showModal, // wajib
    ]);
}
}