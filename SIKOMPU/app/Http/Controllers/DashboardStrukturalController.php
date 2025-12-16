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

        // ============================================
        // 1. STATISTIK UMUM
        // ============================================
        $totalPengguna = User::where('status', 'aktif')->count();

        $totalDosen = User::whereIn('jabatan', ['Dosen', 'Laboran'])
            ->where('status', 'aktif')
            ->count();

        $totalMataKuliah = MataKuliah::count();

        // ============================================
        // 2. SELF ASSESSMENT (DONUT CHART)
        // ============================================
        $dosenSudahIsi = SelfAssessment::distinct('user_id')->count('user_id');

        $totalDosenYangHarusIsi = User::whereIn('jabatan', ['Dosen', 'Laboran'])
            ->where('status', 'aktif')
            ->count();

        $persentaseSelfAssessment = $totalDosenYangHarusIsi > 0
            ? round(($dosenSudahIsi / $totalDosenYangHarusIsi) * 100)
            : 0;

        $dosenBelumIsi = $totalDosenYangHarusIsi - $dosenSudahIsi;

        // ============================================
        // 3. DISTRIBUSI SKS PER DOSEN (BAR CHART)
        // ============================================
        $distribusiSKS = User::whereIn('jabatan', ['Dosen', 'Laboran'])
            ->where('status', 'aktif')
            ->orderBy('beban_mengajar', 'desc')
            ->limit(10)
            ->get(['nama_lengkap', 'beban_mengajar'])
            ->map(function ($dosen) {
                return [
                    'nama' => $this->shortenName($dosen->nama_lengkap),
                    'sks'  => $dosen->beban_mengajar
                ];
            });

        // ============================================
        // 4. DOSEN OVERLOAD
        // ============================================
        $dosenOverload = User::whereIn('jabatan', ['Dosen', 'Laboran'])
            ->where('status', 'aktif')
            ->whereRaw('beban_mengajar > max_beban')
            ->count();

        // ============================================
        // 5. AKTIVITAS TERBARU
        // ============================================
        $aktivitasTerbaru = $this->getAktivitasTerbaru();

        // ============================================
        // 6. MODAL PROFIL (FITUR DARI VERSI A)
        // ============================================
        $showModal = false;

        // Password masih default
        if (password_verify('password123', $user->password)) {
            $showModal = true;
        }

        // Pendidikan terakhir belum diisi
        $pendidikanTerakhir = $user->pendidikans()->latest()->first();
        if (!$pendidikanTerakhir) {
            $showModal = true;
        }

        // ============================================
        // RETURN VIEW
        // ============================================
        return view('pages.dashboard_struktural', [
            'user' => $user,
            'totalPengguna' => $totalPengguna,
            'totalDosen' => $totalDosen,
            'totalMataKuliah' => $totalMataKuliah,
            'persentaseSelfAssessment' => $persentaseSelfAssessment,
            'dosenSudahIsi' => $dosenSudahIsi,
            'dosenBelumIsi' => $dosenBelumIsi,
            'distribusiSKS' => $distribusiSKS,
            'dosenOverload' => $dosenOverload,
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'showModal' => $showModal,
        ]);
    }

    // ======================================================
    // Helper: Perpendek nama dosen untuk chart
    // ======================================================
    private function shortenName($fullName)
    {
        $name = preg_replace('/,?\s*(S\.\w+|M\.\w+|Dr\.|Prof\.)/', '', $fullName);
        $name = trim($name);

        $parts = explode(' ', $name);
        if (count($parts) > 1) {
            return $parts[0] . ' ' . substr(end($parts), 0, 1) . '.';
        }

        return $name;
    }

    // ======================================================
    // Aktivitas Terbaru Dashboard
    // ======================================================
    private function getAktivitasTerbaru()
    {
        $aktivitas = collect();

        // Self assessment terbaru
        $selfAssessments = SelfAssessment::with('user', 'mataKuliah')
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($sa) {
                return [
                    'icon' => 'fa-circle-check',
                    'color' => 'green',
                    'text' => $sa->user->nama_lengkap .
                        ' mengupdate self-assessment untuk ' .
                        $sa->mataKuliah->nama_mk,
                    'time' => $sa->created_at,
                ];
            });

        $aktivitas = $aktivitas->merge($selfAssessments);

        // Dosen baru
        $newDosen = User::whereIn('jabatan', ['Dosen', 'Laboran'])
            ->latest()
            ->limit(2)
            ->get()
            ->map(function ($user) {
                return [
                    'icon' => 'fa-user-plus',
                    'color' => 'blue',
                    'text' => 'Dosen baru ditambahkan: ' . $user->nama_lengkap,
                    'time' => $user->created_at,
                ];
            });

        $aktivitas = $aktivitas->merge($newDosen);

        // Overload warning
        $overloadCount = User::whereIn('jabatan', ['Dosen', 'Laboran'])
            ->whereRaw('beban_mengajar > max_beban')
            ->count();

        if ($overloadCount > 0) {
            $aktivitas->push([
                'icon' => 'fa-triangle-exclamation',
                'color' => 'red',
                'text' => 'Terdapat ' . $overloadCount . ' dosen dengan beban mengajar berlebih',
                'time' => now(),
            ]);
        }

        return $aktivitas
            ->sortByDesc('time')
            ->take(7)
            ->values();
    }
}
