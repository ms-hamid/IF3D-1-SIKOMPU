<?php

namespace App\Http\Controllers;

use App\Models\HasilRekomendasi;
use App\Services\LaporanExcelService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    protected $excelService;

    public function __construct(LaporanExcelService $excelService)
    {
        $this->excelService = $excelService;
    }

    public function index()
    {
        $hasilAktif = HasilRekomendasi::active()
            ->withCount('detailHasilRekomendasi')
            ->latest()
            ->first();

        return view('pages.laporan-struktural', [
            'hasilAktif' => $hasilAktif
        ]);
    }

    public function exportRekapPengampuPdf(Request $request)
    {
        // Ambil input semester dari dropdown
        $semesterInput = $request->query('semester');

        $hasil = HasilRekomendasi::active()
            ->with([
                'detailHasilRekomendasi.mataKuliah.prodi',
                'detailHasilRekomendasi.user'
            ])
            ->latest()
            ->first();

        if (!$hasil || $hasil->detailHasilRekomendasi->isEmpty()) {
            return back()->with('error', 'Belum ada hasil rekomendasi yang aktif.');
        }

        $dataPerMK = $hasil->detailHasilRekomendasi->groupBy('matakuliah_id');

        // Menentukan label semester untuk judul PDF
        $semesterLabel = $semesterInput ?: ($hasil->semester . ' ' . $hasil->tahun_ajaran);

        $pdf = Pdf::loadView('components.rekap-pengampu-pdf', [
            'semester' => $semesterLabel,
            'dataPerMK' => $dataPerMK
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Rekap_Final_Pengampu.pdf');
    }

    public function exportRekapPengampuExcel(Request $request)
    {
        $hasil = HasilRekomendasi::active()
            ->with(['detailHasilRekomendasi.mataKuliah.prodi', 'detailHasilRekomendasi.user'])
            ->latest()
            ->first();

        if (!$hasil) return back()->with('error', 'Data tidak ditemukan');

        $dataPerMK = $hasil->detailHasilRekomendasi->groupBy('matakuliah_id');

        return $this->excelService->generateRekapPengampu(
            $dataPerMK,
            $hasil->semester . ' ' . $hasil->tahun_ajaran
        );
    }
}