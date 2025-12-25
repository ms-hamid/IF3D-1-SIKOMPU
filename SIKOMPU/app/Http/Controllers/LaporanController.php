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
        // Ambil AI aktif
        $hasilAktif = HasilRekomendasi::active()
            ->withCount('detailHasilRekomendasi')
            ->latest()
            ->first();

        return view('pages.laporan-struktural', [
            'hasilAktif' => $hasilAktif
        ]);
    }

    public function exportRekapPengampuExcel(Request $request)
    {
        $hasil = HasilRekomendasi::active()
            ->with([
                'detailHasilRekomendasi.mataKuliah.prodi',
                'detailHasilRekomendasi.user'
            ])
            ->latest()
            ->first();

        if (!$hasil || $hasil->detailHasilRekomendasi->isEmpty()) {
            return back()->with(
                'error',
                'Belum ada hasil rekomendasi AI aktif yang dapat diekspor.'
            );
        }

        $dataPerMK = $hasil->detailHasilRekomendasi->groupBy('matakuliah_id');

        return $this->excelService->generateRekapPengampu(
            $dataPerMK,
            $hasil->semester . ' ' . $hasil->tahun_ajaran
        );
    }

    public function exportRekapPengampuPdf()
    {
        $hasil = HasilRekomendasi::active()
            ->with([
                'detailHasilRekomendasi.mataKuliah.prodi',
                'detailHasilRekomendasi.user'
            ])
            ->latest()
            ->first();

        if (!$hasil || $hasil->detailHasilRekomendasi->isEmpty()) {
            return back()->with(
                'error',
                'Belum ada hasil rekomendasi AI aktif yang dapat diekspor.'
            );
        }

        $dataPerMK = $hasil->detailHasilRekomendasi->groupBy('matakuliah_id');

        $semesterLabel = $hasil->semester . ' ' . $hasil->tahun_ajaran;
        $fileName = 'Rekap_Pengampu_' . str_replace([' ', '/'], '_', $semesterLabel) . '.pdf';

        $pdf = Pdf::loadView(
            'components.rekap-pengampu-pdf',
            compact('semesterLabel', 'dataPerMK')
        )->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }
}
