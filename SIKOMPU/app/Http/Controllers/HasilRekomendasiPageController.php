<?php

namespace App\Http\Controllers;

use App\Models\HasilRekomendasi;
use App\Models\DetailHasilRekomendasi;

class HasilRekomendasiPageController extends Controller
{
    public function index()
    {
        /* ===============================
           DATA UTAMA
        =============================== */

        // Ambil seluruh hasil rekomendasi + relasi
        $hasilRekomendasi = HasilRekomendasi::with([
            'detailHasilRekomendasi.mataKuliah',
            'detailHasilRekomendasi.user'
        ])->get();

        /* ===============================
           RINGKASAN DATA (CARDS)
        =============================== */

        // Total Mata Kuliah (unik)
        $totalMk = DetailHasilRekomendasi::distinct('matakuliah_id')
            ->count('matakuliah_id');

        // Total Koordinator (ingat: lowercase)
        $totalKoordinator = DetailHasilRekomendasi::where(
            'peran_penugasan',
            'koordinator'
        )->count();

        // Total Pengampu
        $totalPengampu = DetailHasilRekomendasi::where(
            'peran_penugasan',
            'pengampu'
        )->count();

        // Skor rata-rata dosen
        $avgSkor = DetailHasilRekomendasi::avg('skor_dosen_di_mk');

        /* ===============================
           KIRIM KE VIEW
        =============================== */

        return view('pages.hasil-rekomendasi', [
            'hasilRekomendasi' => $hasilRekomendasi,
            'totalMk'          => $totalMk,
            'totalKoordinator' => $totalKoordinator,
            'totalPengampu'    => $totalPengampu,
            'avgSkor'          => $avgSkor,
        ]);
    }

    
}
