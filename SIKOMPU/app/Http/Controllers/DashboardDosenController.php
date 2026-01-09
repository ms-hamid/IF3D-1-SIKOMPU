<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\SelfAssessment;
use App\Models\MataKuliah;
use App\Models\Sertifikat;
use App\Models\Penelitian;
use Carbon\Carbon;

class DashboardDosenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // ✅ HITUNG PROGRESS SELF-ASSESSMENT YANG BENAR
        $totalMataKuliah = MataKuliah::count();
        
        $sudahIsi = SelfAssessment::where('user_id', $user->id)
                                   ->where('nilai', '>', 0)
                                   ->count();
        
        $belumIsi = max(0, $totalMataKuliah - $sudahIsi);
        
        $persentaseSelfAssessment = $totalMataKuliah > 0 
            ? round(($sudahIsi / $totalMataKuliah) * 100) 
            : 0;

        // ✅ AMBIL AKTIVITAS TERBARU
        $aktivitasTerbaru = $this->getAktivitasTerbaru($user);

        // Modal Profil Pemberitahuan
        $showModal = false;
        
        if (password_verify('password123', $user->password)) {
            $showModal = true;
        }

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
            'showModal' => $showModal,
        ]);
    }
    
    /**
     * ✅ Ambil aktivitas terbaru dengan AUTO-DETECT timestamps
     */
    private function getAktivitasTerbaru($user)
    {
        $aktivitas = collect();
        
        // ✅ Self Assessment - Cek apakah ada updated_at
        $hasSelfAssessmentTimestamps = Schema::hasColumn('self_assessments', 'updated_at');
        
        $selfAssessments = SelfAssessment::where('user_id', $user->id)
            ->with('mataKuliah')
            ->latest($hasSelfAssessmentTimestamps ? 'updated_at' : 'id')
            ->take(2)
            ->get()
            ->map(function($sa) use ($hasSelfAssessmentTimestamps) {
                $timestamp = $hasSelfAssessmentTimestamps 
                    ? ($sa->updated_at ?? $sa->created_at ?? now()) 
                    : now()->subHours(rand(1, 5));
                
                return [
                    'type' => 'self-assessment',
                    'title' => 'Perbarui Self-Assessment',
                    'description' => 'Mata kuliah ' . ($sa->mataKuliah->nama_mk ?? 'N/A'),
                    'status' => 'Selesai',
                    'status_color' => 'blue',
                    'border_color' => 'blue-500',
                    'bg_color' => 'blue-50/60',
                    'created_at' => $timestamp,
                ];
            });
        
        // ✅ Sertifikat - Cek apakah ada created_at
        $hasSertifikatTimestamps = Schema::hasColumn('sertifikat', 'created_at');
        
        $sertifikats = Sertifikat::where('user_id', $user->id)
            ->latest($hasSertifikatTimestamps ? 'created_at' : 'id')
            ->take(2)
            ->get()
            ->map(function($cert) use ($hasSertifikatTimestamps) {
                $timestamp = $hasSertifikatTimestamps 
                    ? ($cert->created_at ?? now()) 
                    : now()->subHours(rand(3, 8));
                
                return [
                    'type' => 'sertifikat',
                    'title' => 'Unggah Sertifikat Baru',
                    'description' => '"' . $cert->nama_sertifikat . '"',
                    'status' => 'Tersimpan',
                    'status_color' => 'green',
                    'border_color' => 'green-500',
                    'bg_color' => 'green-50/60',
                    'created_at' => $timestamp,
                ];
            });
        
        // ✅ Penelitian - Cek apakah ada created_at
        $hasPenelitianTimestamps = Schema::hasColumn('penelitians', 'created_at');
        
        $penelitians = Penelitian::where('user_id', $user->id)
            ->latest($hasPenelitianTimestamps ? 'created_at' : 'id')
            ->take(2)
            ->get()
            ->map(function($penelitian) use ($hasPenelitianTimestamps) {
                $timestamp = $hasPenelitianTimestamps 
                    ? ($penelitian->created_at ?? now()) 
                    : now()->subDay(rand(1, 3));
                
                return [
                    'type' => 'penelitian',
                    'title' => 'Tambah Penelitian',
                    'description' => '"' . $penelitian->judul_penelitian . '"',
                    'status' => 'Tersimpan',
                    'status_color' => 'purple',
                    'border_color' => 'purple-500',
                    'bg_color' => 'purple-50/60',
                    'created_at' => $timestamp,
                ];
            });
        
        // Gabungkan dan urutkan
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