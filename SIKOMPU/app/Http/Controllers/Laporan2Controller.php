<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\HasilRekomendasi;
use App\Models\DetailHasilRekomendasi;
use App\Models\User;
use App\Models\Matakuliah;

class Laporan2Controller extends Controller
{
    /**
     * Tampilkan laporan dosen yang sedang login
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil hasil rekomendasi yang aktif
        $hasilAktif = HasilRekomendasi::where('is_active', true)
            ->latest()
            ->first();

        if (!$hasilAktif) {
            return view('pages.laporan-dosen', [
                'statusLulus' => false,
                'skorAkhir' => 0,
                'periode' => null,
                'penugasan' => null,
                'matakuliah' => null,
                'breakdownSkor' => null,
                'dosenSeMatakuliah' => [],
                'pesanKosong' => 'Belum ada hasil rekomendasi yang aktif untuk periode ini.'
            ]);
        }

        // Ambil penugasan dosen yang login
        $penugasanDosen = DetailHasilRekomendasi::where('hasil_id', $hasilAktif->id)
            ->where('user_id', $user->id)
            ->with('mataKuliah.prodi')
            ->first();

        // Jika tidak ada penugasan
        if (!$penugasanDosen) {
            return view('pages.laporan-dosen', [
                'statusLulus' => false,
                'skorAkhir' => 0,
                'periode' => $hasilAktif->semester . ' ' . $hasilAktif->tahun_ajaran,
                'penugasan' => null,
                'matakuliah' => null,
                'breakdownSkor' => null,
                'dosenSeMatakuliah' => [],
                'pesanKosong' => 'Anda belum mendapat penugasan pada periode ini.'
            ]);
        }

        // Hitung skor akhir dan status lulus
        $skorAkhir = round($penugasanDosen->skor_dosen_di_mk);
        $statusLulus = $skorAkhir >= 70; // Threshold lulus = 70

        // Hitung breakdown skor (dari data dosen)
        $breakdownSkor = $this->hitungBreakdownSkor($user, $penugasanDosen->matakuliah_id);

        // Ambil semua dosen yang ditugaskan di mata kuliah yang sama
        $dosenSeMatakuliah = DetailHasilRekomendasi::where('hasil_id', $hasilAktif->id)
            ->where('matakuliah_id', $penugasanDosen->matakuliah_id)
            ->where('user_id', '!=', $user->id) // Exclude dosen yang login
            ->with(['user', 'mataKuliah'])
            ->orderBy('peran_penugasan', 'asc') // Koordinator dulu
            ->orderByDesc('skor_dosen_di_mk') // Skor tertinggi dulu
            ->get();

        return view('pages.laporan-dosen', [
            'statusLulus' => $statusLulus,
            'skorAkhir' => $skorAkhir,
            'periode' => $hasilAktif->semester . ' ' . $hasilAktif->tahun_ajaran,
            'penugasan' => $penugasanDosen,
            'matakuliah' => $penugasanDosen->mataKuliah,
            'breakdownSkor' => $breakdownSkor,
            'dosenSeMatakuliah' => $dosenSeMatakuliah,
            'pesanKosong' => null
        ]);
    }

    /**
     * Hitung breakdown skor dari berbagai kriteria
     */
    private function hitungBreakdownSkor($user, $matakuliahId)
    {
        // 1. SKOR PENDIDIKAN (skala 0-100)
        $pendidikanTerakhir = $user->pendidikans()->latest()->first();
        $skorPendidikan = 0;
        
        if ($pendidikanTerakhir) {
            $skorPendidikan = match($pendidikanTerakhir->jenjang) {
                'S3' => 100,
                'S2' => 75,
                'S1' => 50,
                default => 0
            };
        }

        // 2. SKOR SELF ASSESSMENT (langsung dari nilai)
        $selfAssessment = $user->selfAssessments()
            ->where('matakuliah_id', $matakuliahId)
            ->latest()
            ->first();
        
        $skorSelfAssessment = $selfAssessment ? $selfAssessment->nilai : 0;

        // 3. SKOR PENELITIAN (hitung total bobot dari mk_kategori)
        $mkKategori = DB::table('mk_kategori')
            ->where('mata_kuliah_id', $matakuliahId)
            ->pluck('bobot', 'kategori_id');

        $skorPenelitian = 0;
        foreach ($user->penelitians as $penelitian) {
            if (isset($mkKategori[$penelitian->kategori_id])) {
                $skorPenelitian += $mkKategori[$penelitian->kategori_id];
            }
        }
        // Normalisasi ke skala 0-100 (asumsi max = 50 bobot)
        $skorPenelitian = min(100, ($skorPenelitian / 50) * 100);

        // 4. SKOR SERTIFIKAT (hitung total bobot dari mk_kategori)
        $skorSertifikat = 0;
        foreach ($user->sertifikat as $sertif) {
            if (isset($mkKategori[$sertif->kategori_id])) {
                $skorSertifikat += $mkKategori[$sertif->kategori_id];
            }
        }
        // Normalisasi ke skala 0-100 (asumsi max = 50 bobot)
        $skorSertifikat = min(100, ($skorSertifikat / 50) * 100);

        return [
            'pendidikan' => round($skorPendidikan),
            'self_assessment' => round($skorSelfAssessment),
            'penelitian' => round($skorPenelitian),
            'sertifikat' => round($skorSertifikat),
        ];
    }

    /**
     * Export laporan dosen ke PDF (opsional)
     */
    public function exportPdf()
    {
        // TODO: Implementasi export PDF jika diperlukan
        return response()->json(['message' => 'Fitur export PDF sedang dalam pengembangan']);
    }

    /**
     * Export laporan dosen ke Excel (opsional)
     */
    public function exportExcel()
    {
        // TODO: Implementasi export Excel jika diperlukan
        return response()->json(['message' => 'Fitur export Excel sedang dalam pengembangan']);
    }
}