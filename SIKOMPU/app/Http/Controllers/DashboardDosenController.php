<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SelfAssessment;
use App\Models\Sertifikat;
use App\Models\Penelitian;
use Carbon\Carbon;

class DashboardDosenController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        
        // Hitung persentase self-assessment
        $totalSelfAssessment = $user->selfAssessments()->count();
        $sudahIsi = $totalSelfAssessment;
        $belumIsi = 0;
        $persentaseSelfAssessment = $totalSelfAssessment > 0 ? 80 : 0;

        // Ambil aktivitas terbaru
        $aktivitasTerbaru = $this->getAktivitasTerbaru($user);

        // -----------------------------
        // Modal Profil Pemberitahuan
        // -----------------------------
        $showModal = false; // default

        // Cek password default
        // Ganti 'password123' dengan password default aplikasi Anda
        if (password_verify('password123', $user->password)) {
            $showModal = true;
        }

        // Cek pendidikan terakhir
        $pendidikanTerakhir = $user->pendidikans()->latest()->first();
        if (!$pendidikanTerakhir) {
            $showModal = true;
        }

        return view('pages.dashboard_dosen', [
            'user' => $user,
            'persentaseSelfAssessment' => $persentaseSelfAssessment,
            'sudahIsi' => $sudahIsi,
            'belumIsi' => $belumIsi,
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'showModal' => $showModal, // <-- pastikan selalu ada
        ]);
    }


    // public function index()
    // {
    //     $user = Auth::user();
        
    //     // Hitung persentase self-assessment yang sudah diisi
    //     // Ambil total mata kuliah yang tersedia untuk prodi user (opsional, bisa disesuaikan)
    //     $totalSelfAssessment = $user->selfAssessments()->count();
    //     $sudahIsi = $totalSelfAssessment; // Karena self_assessment tidak ada status, anggap semua yang ada sudah diisi
    //     $belumIsi = 0; // Bisa dihitung jika ada logika total mata kuliah
        
    //     // Hitung persentase (sementara pakai logika sederhana)
    //     $persentaseSelfAssessment = $totalSelfAssessment > 0 ? 80 : 0; // Dummy 80% jika ada data
        
    //     // Ambil aktivitas terbaru (gabungan dari berbagai tabel)
    //     $aktivitasTerbaru = $this->getAktivitasTerbaru($user);
        
    //     // Data untuk view
    //     $data = [
    //         'user' => $user,
    //         'persentaseSelfAssessment' => $persentaseSelfAssessment,
    //         'sudahIsi' => $sudahIsi,
    //         'belumIsi' => $belumIsi,
    //         'aktivitasTerbaru' => $aktivitasTerbaru,
    //     ];
        
    //     return view('pages.dashboard_dosen', $data);
    // }
    
    /**
     * Ambil aktivitas terbaru dari berbagai sumber
     */
    private function getAktivitasTerbaru($user)
    {
        $aktivitas = collect();
        
        // Self Assessment terbaru (karena tidak ada timestamps, kita ambil by ID terbaru)
        $selfAssessments = $user->selfAssessments()
            ->with('mataKuliah')
            ->latest('id')
            ->take(2)
            ->get()
            ->map(function($sa) {
                return [
                    'type' => 'self-assessment',
                    'title' => 'Perbarui Self-Assessment',
                    'description' => 'Mata kuliah ' . ($sa->mataKuliah->nama_mk ?? 'N/A'),
                    'status' => 'Selesai',
                    'status_color' => 'blue',
                    'border_color' => 'blue-500',
                    'bg_color' => 'blue-50/60',
                    'created_at' => now()->subHours(2), // Dummy timestamp
                ];
            });
        
        // Sertifikat terbaru (karena tidak ada timestamps, kita ambil by ID terbaru)
        $sertifikats = $user->sertifikat()
            ->latest('id')
            ->take(2)
            ->get()
            ->map(function($cert) {
                return [
                    'type' => 'sertifikat',
                    'title' => 'Unggah Sertifikat Baru',
                    'description' => '"' . $cert->nama_sertifikat . '"',
                    'status' => 'Tersimpan',
                    'status_color' => 'green',
                    'border_color' => 'green-500',
                    'bg_color' => 'green-50/60',
                    'created_at' => now()->subHours(5), // Dummy timestamp
                ];
            });
        
        // Penelitian terbaru (karena tidak ada timestamps, kita ambil by ID terbaru)
        $penelitians = $user->penelitians()
            ->latest('id')
            ->take(2)
            ->get()
            ->map(function($penelitian) {
                return [
                    'type' => 'penelitian',
                    'title' => 'Tambah Penelitian',
                    'description' => '"' . $penelitian->judul_penelitian . '"',
                    'status' => 'Tersimpan',
                    'status_color' => 'purple',
                    'border_color' => 'purple-500',
                    'bg_color' => 'purple-50/60',
                    'created_at' => now()->subDay(), // Dummy timestamp
                ];
            });
        
        // Gabungkan semua aktivitas
        $aktivitas = $aktivitas
            ->concat($selfAssessments)
            ->concat($sertifikats)
            ->concat($penelitians)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();
        
        return $aktivitas;
    }
}