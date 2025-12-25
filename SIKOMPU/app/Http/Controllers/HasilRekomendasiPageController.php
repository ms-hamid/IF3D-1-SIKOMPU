<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilRekomendasi;
use App\Models\Prodi;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilRekomendasiPageController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data utama yang aktif
        $hasilAktif = HasilRekomendasi::with([
            'detailHasilRekomendasi' => function ($query) use ($request) {
                // Filter Pencarian (q)
                if ($request->filled('q')) {
                    $q = $request->q;
                    $query->where(function ($sub) use ($q) {
                        $sub->whereHas('mataKuliah', function ($mk) use ($q) {
                            $mk->where('nama_mk', 'like', "%{$q}%")
                               ->orWhere('kode_mk', 'like', "%{$q}%");
                        })
                        ->orWhereHas('user', function ($u) use ($q) {
                            $u->where('nama_lengkap', 'like', "%{$q}%");
                        });
                    });
                }

                // Filter Prodi (Gunakan ID)
                if ($request->filled('prodi')) {
                    $query->whereHas('mataKuliah', function ($mk) use ($request) {
                        $mk->where('prodi_id', $request->prodi);
                    });
                }

                // Filter Semester
                if ($request->filled('semester')) {
                    $query->whereHas('mataKuliah', function ($mk) use ($request) {
                        $mk->where('semester', $request->semester);
                    });
                }
            },
            'detailHasilRekomendasi.mataKuliah.prodi',
            'detailHasilRekomendasi.user'
        ])
        ->where('is_active', 1)
        ->first();

        // Ambil daftar prodi untuk dikirim ke view (variabel ini penting!)
        $listProdi = Prodi::orderBy('nama_prodi')->get();

        if (!$hasilAktif) {
            return view('pages.hasil-rekomendasi', [
                'hasilRekomendasi' => collect(),
                'totalMk'          => 0,
                'totalKoordinator' => 0,
                'totalPengampu'    => 0,
                'avgSkor'          => 0,
                'listProdi'        => $listProdi, // Di Blade kamu pakai $listProdi
            ]);
        }

        $detail = $hasilAktif->detailHasilRekomendasi;
        $totalMk = $detail->pluck('matakuliah_id')->unique()->count();
        $totalKoordinator = $detail->where('peran_penugasan', 'Koordinator')->count();
        $totalPengampu = $detail->where('peran_penugasan', 'Pengampu')->count();
        $avgSkor = round($detail->avg('skor_dosen_di_mk'), 2);

        return view('pages.hasil-rekomendasi', [
            'hasilRekomendasi' => collect([$hasilAktif]),
            'totalMk'          => $totalMk,
            'totalKoordinator' => $totalKoordinator,
            'totalPengampu'    => $totalPengampu,
            'avgSkor'          => $avgSkor,
            'listProdi'        => $listProdi, // Samakan namanya dengan di Blade
        ]);
    }

    public function exportExcel(Request $request)
    {
        $rekomendasi = HasilRekomendasi::with([
            'detailHasilRekomendasi.mataKuliah.prodi',
            'detailHasilRekomendasi.user'
        ])->where('is_active', 1)->first();

        if (!$rekomendasi) return back()->with('error', 'Data tidak ditemukan');

        $details = $rekomendasi->detailHasilRekomendasi;

        // Apply Filter Collection
        if ($request->filled('q')) {
            $q = strtolower($request->q);
            $details = $details->filter(fn($item) => 
                str_contains(strtolower($item->mataKuliah->nama_mk), $q) || 
                str_contains(strtolower($item->user->nama_lengkap ?? ''), $q)
            );
        }
        if ($request->filled('prodi')) {
            $details = $details->filter(fn($item) => $item->mataKuliah->prodi_id == $request->prodi);
        }
        if ($request->filled('semester')) {
            $details = $details->filter(fn($item) => $item->mataKuliah->semester == $request->semester);
        }

        $groupedData = $details->groupBy('matakuliah_id');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['No', 'Kode MK', 'Mata Kuliah', 'SKS', 'SEM', 'Koordinator', 'Pengampu', 'Skor'];
        $sheet->fromArray($headers, NULL, 'A1');
        
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $row = 2;
        foreach ($groupedData as $index => $items) {
            $mk = $items->first()->mataKuliah;
            $koor = $items->firstWhere('peran_penugasan', 'Koordinator');
            $pengampu = $items->where('peran_penugasan', 'Pengampu')->map(fn($p) => $p->user->nama_lengkap ?? '-')->implode(', ');

            $sheet->setCellValue('A' . $row, $row - 1);
            $sheet->setCellValue('B' . $row, $mk->kode_mk);
            $sheet->setCellValue('C' . $row, $mk->nama_mk);
            $sheet->setCellValue('D' . $row, $mk->sks);
            $sheet->setCellValue('E' . $row, $mk->semester);
            $sheet->setCellValue('F' . $row, $koor->user->nama_lengkap ?? 'Belum ditentukan');
            $sheet->setCellValue('G' . $row, $pengampu ?: '-');
            $sheet->setCellValue('H' . $row, $koor ? number_format($koor->skor_dosen_di_mk, 3) : '0');
            $row++;
        }

        foreach (range('A', 'H') as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

        $fileName = 'Hasil-Rekomendasi-' . now()->format('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    public function exportPdf(Request $request)
    {
        $rekomendasi = HasilRekomendasi::with([
            'detailHasilRekomendasi.mataKuliah.prodi',
            'detailHasilRekomendasi.user'
        ])->where('is_active', 1)->first();

        if (!$rekomendasi) return back()->with('error', 'Data tidak ditemukan');

        $details = $rekomendasi->detailHasilRekomendasi;

        // Apply Filter
        if ($request->filled('q')) {
            $q = strtolower($request->q);
            $details = $details->filter(fn($item) => 
                str_contains(strtolower($item->mataKuliah->nama_mk ?? ''), $q) || 
                str_contains(strtolower($item->user->nama_lengkap ?? ''), $q)
            );
        }
        if ($request->filled('prodi')) {
            $details = $details->filter(fn($item) => $item->mataKuliah->prodi_id == $request->prodi);
        }
        if ($request->filled('semester')) {
            $details = $details->filter(fn($item) => $item->mataKuliah->semester == $request->semester);
        }

        $dataFormatted = $details->groupBy('matakuliah_id')->map(function($group) {
            return [
                'matakuliah' => $group->first()->mataKuliah,
                'koordinator' => $group->filter(fn($d) => strtolower($d->peran_penugasan) == 'koordinator')->first(),
                'pengampu' => $group->filter(fn($d) => strtolower($d->peran_penugasan) == 'pengampu'),
                'skor' => $group->filter(fn($d) => strtolower($d->peran_penugasan) == 'koordinator')->first()?->skor_dosen_di_mk ?? 0
            ];
        })->values();

        $pdfData = [
            'semester' => 'Ganjil 2025/2026',
            'tanggal' => now()->translatedFormat('d F Y'),
            'data' => $dataFormatted,
            'filterInfo' => [
                'prodi' => $request->filled('prodi') ? Prodi::find($request->prodi)->nama_prodi : 'Semua',
                'semester' => $request->semester ?? 'Semua'
            ]
        ];

        return Pdf::loadView('pages.hasil-rekomendasi-pdf', $pdfData)
            ->setPaper('a4', 'landscape')
            ->stream('Hasil-Rekomendasi.pdf');
    }
}