<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilRekomendasi;

class HasilRekomendasiPageController extends Controller
{
    public function index(Request $request)
    {
        /* ===============================
           AMBIL 1 DATA AKTIF
        =============================== */
        $hasilAktif = HasilRekomendasi::with([
            'detailHasilRekomendasi' => function ($query) use ($request) {

                // 🔍 FILTER TEXT (MK / DOSEN)
                if ($request->filled('q')) {
                    $q = $request->q;

                    $query->where(function ($sub) use ($q) {
                        $sub->whereHas('mataKuliah', function ($mk) use ($q) {
                            $mk->where('nama_mk', 'like', "%{$q}%")
                               ->orWhere('kode_mk', 'like', "%{$q}%");
                        })
                        ->orWhereHas('user', function ($u) use ($q) {
                            $u->where('name', 'like', "%{$q}%");
                        });
                    });
                }

                // 🏫 FILTER PRODI
                if ($request->filled('prodi')) {
                    $query->whereHas('mataKuliah', function ($mk) use ($request) {
                        $mk->where('kode_mk', 'like', $request->prodi . '%');
                    });
                }

                // 📆 FILTER SEMESTER
                if ($request->filled('semester')) {
                    $query->whereHas('mataKuliah', function ($mk) use ($request) {
                        $mk->where('semester', $request->semester);
                    });
                }
            },
            'detailHasilRekomendasi.mataKuliah',
            'detailHasilRekomendasi.user'
        ])
        ->where('is_active', 1)
        ->first();

        // ⛔ Kalau tidak ada hasil aktif
        if (!$hasilAktif) {
            return view('pages.hasil-rekomendasi', [
                'hasilRekomendasi' => collect(),
                'totalMk'          => 0,
                'totalKoordinator' => 0,
                'totalPengampu'    => 0,
                'avgSkor'          => 0,
            ]);
        }

        /* ===============================
           RINGKASAN DATA (SETELAH FILTER)
        =============================== */
        $detail = $hasilAktif->detailHasilRekomendasi;

        $totalMk = $detail->pluck('matakuliah_id')->unique()->count();

        $totalKoordinator = $detail
            ->where('peran_penugasan', 'Koordinator')
            ->count();

        $totalPengampu = $detail
            ->where('peran_penugasan', 'Pengampu')
            ->count();

        $avgSkor = round($detail->avg('skor_dosen_di_mk'), 2);

        return view('pages.hasil-rekomendasi', [
            'hasilRekomendasi' => collect([$hasilAktif]),
            'totalMk'          => $totalMk,
            'totalKoordinator' => $totalKoordinator,
            'totalPengampu'    => $totalPengampu,
            'avgSkor'          => $avgSkor,
        ]);
    }

   public function detailMk($id, $kode_mk)
    {
        $hasil = HasilRekomendasi::with('detailHasilRekomendasi.mataKuliah', 'detailHasilRekomendasi.user')
                    ->findOrFail($id);

        $detail = $hasil->detailHasilRekomendasi->filter(function($item) use ($kode_mk) {
            return $item->mataKuliah->kode_mk === $kode_mk;
        });

        return view('pages.hasil-rekomendasi-detail', compact('hasil', 'detail'));
    }

}
