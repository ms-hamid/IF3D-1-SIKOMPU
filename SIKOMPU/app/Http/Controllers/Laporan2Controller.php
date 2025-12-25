<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\HasilRekomendasi;
use App\Models\DetailHasilRekomendasi;

class Laporan2Controller extends Controller
{
    /**
     * Halaman laporan dosen (berdasarkan hasil AI aktif)
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil hasil rekomendasi AI yang aktif
        $hasilAktif = HasilRekomendasi::active()
            ->latest()
            ->first();

        // Jika belum ada AI yang dijalankan
        if (!$hasilAktif) {
            return view('pages.laporan-dosen', [
                'statusLulus' => false,
                'skorAkhir' => 0,
                'periode' => null,
                'penugasan' => null,
                'matakuliah' => null,
                'breakdownSkor' => null,
                'dosenSeMatakuliah' => collect(),
                'pesanKosong' => 'Belum ada hasil rekomendasi AI yang aktif.'
            ]);
        }

        // 2. Ambil penugasan dosen dari hasil AI
        $penugasan = DetailHasilRekomendasi::where('hasil_id', $hasilAktif->id)
            ->where('user_id', $user->id)
            ->with(['mataKuliah.prodi', 'user'])
            ->first();

        // 3. Jika dosen tidak mendapat penugasan
        if (!$penugasan) {
            return view('pages.laporan-dosen', [
                'statusLulus' => false,
                'skorAkhir' => 0,
                'periode' => $hasilAktif->semester . ' ' . $hasilAktif->tahun_ajaran,
                'penugasan' => null,
                'matakuliah' => null,
                'breakdownSkor' => null,
                'dosenSeMatakuliah' => collect(),
                'pesanKosong' =>
                    'Anda belum mendapatkan penugasan pada periode ini berdasarkan hasil perhitungan AI.'
            ]);
        }

        // 4. Skor & status lulus (MURNI dari AI)
        $skorAkhir = round($penugasan->skor_dosen_di_mk);
        $statusLulus = $skorAkhir >= 70;

        // 5. Breakdown skor (JUGA MURNI dari AI)
        $breakdownSkor = [
            'pendidikan'      => $penugasan->skor_pendidikan,
            'self_assessment' => $penugasan->skor_self_assessment,
            'penelitian'      => $penugasan->skor_penelitian,
            'sertifikat'      => $penugasan->skor_sertifikat,
        ];

        // 6. Dosen lain di mata kuliah yang sama
        $dosenSeMatakuliah = DetailHasilRekomendasi::where('hasil_id', $hasilAktif->id)
            ->where('matakuliah_id', $penugasan->matakuliah_id)
            ->where('user_id', '!=', $user->id)
            ->with(['user'])
            ->orderByRaw("CASE WHEN peran_penugasan = 'koordinator' THEN 0 ELSE 1 END")
            ->orderByDesc('skor_dosen_di_mk')
            ->get();

        return view('pages.laporan-dosen', [
            'statusLulus' => $statusLulus,
            'skorAkhir' => $skorAkhir,
            'periode' => $hasilAktif->semester . ' ' . $hasilAktif->tahun_ajaran,
            'penugasan' => $penugasan,
            'matakuliah' => $penugasan->mataKuliah,
            'breakdownSkor' => $breakdownSkor,
            'dosenSeMatakuliah' => $dosenSeMatakuliah,
            'pesanKosong' => null
        ]);
    }
}
