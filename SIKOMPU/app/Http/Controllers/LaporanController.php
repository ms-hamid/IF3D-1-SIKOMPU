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
        $recentReports = [
            [
                'icon_type' => 'pdf',
                'name' => 'Rekap Final Pengampu',
                'periode' => 'Ganjil 2024/2025',
                'dibuat' => '15 Des 2024, 14:30',
                'status' => 'Selesai'
            ],
        ];

        return view('pages.laporan-struktural', ['reports' => $recentReports]);
    }

    public function exportRekapPengampuExcel(Request $request)
    {
        $semester = $request->input('semester', 'Ganjil 2024/2025');
        
        try {
            $hasil = HasilRekomendasi::with([
                'detailHasilRekomendasi.mataKuliah.prodi',
                'detailHasilRekomendasi.user'
            ])
            ->where('semester', $semester)
            ->first();
            
            if (!$hasil) {
                return back()->with('error', 'Belum ada hasil rekomendasi untuk semester ' . $semester . '. Silakan generate rekomendasi terlebih dahulu di menu Hasil Rekomendasi.');
            }
            
            if ($hasil->detailHasilRekomendasi->isEmpty()) {
                return back()->with('error', 'Data detail pengampu kosong untuk semester ' . $semester . '. Pastikan proses generate rekomendasi sudah selesai.');
            }
            
            $dataPerMK = $hasil->detailHasilRekomendasi->groupBy('matakuliah_id');
            
            return $this->excelService->generateRekapPengampu($dataPerMK, $semester);
            
        } catch (\Exception $e) {
            \Log::error('Error Export Excel: ' . $e->getMessage());
            return back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    public function exportRekapPengampuPdf(Request $request)
    {
        $semester = $request->input('semester', 'Ganjil 2024/2025');
        
        try {
            $hasil = HasilRekomendasi::with([
                'detailHasilRekomendasi.mataKuliah.prodi',
                'detailHasilRekomendasi.user'
            ])
            ->where('semester', $semester)
            ->first();
            
            if (!$hasil) {
                return back()->with('error', 'Belum ada hasil rekomendasi untuk semester ' . $semester . '. Silakan generate rekomendasi terlebih dahulu di menu Hasil Rekomendasi.');
            }
            
            if ($hasil->detailHasilRekomendasi->isEmpty()) {
                return back()->with('error', 'Data detail pengampu kosong untuk semester ' . $semester . '. Pastikan proses generate rekomendasi sudah selesai.');
            }
            
            $dataPerMK = $hasil->detailHasilRekomendasi->groupBy('matakuliah_id');
            
            $fileName = 'Rekap_Pengampu_' . str_replace([' ', '/'], ['_', '_'], $semester) . '.pdf';
            
            $pdf = Pdf::loadView('components.rekap-pengampu-pdf', compact('semester', 'dataPerMK'))
                ->setPaper('a4', 'landscape')
                ->setOption('margin-top', 10)
                ->setOption('margin-bottom', 10);
                
            return $pdf->download($fileName);
            
        } catch (\Exception $e) {
            \Log::error('Error Export PDF: ' . $e->getMessage());
            return back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }
}