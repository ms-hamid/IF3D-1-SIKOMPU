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
        // 1. Ambil semua prodi untuk dropdown filter
        $listProdi = Prodi::orderBy('nama_prodi', 'asc')->get();

        // 2. Ambil hasil rekomendasi yang sedang aktif
        $rekomendasiUtama = HasilRekomendasi::with([
            'detailHasilRekomendasi.mataKuliah.prodi',
            'detailHasilRekomendasi.user'
        ])->where('is_active', 1)->first();

        if (!$rekomendasiUtama) {
            return view('pages.hasil-rekomendasi', [
                'hasilRekomendasi' => collect([]),
                'listProdi' => $listProdi,
                'totalMk' => 0,
                'totalKoordinator' => 0,
                'totalPengampu' => 0,
                'avgSkor' => 0
            ]);
        }

        // 3. Filter Detail Rekomendasi berdasarkan input user
        $details = $rekomendasiUtama->detailHasilRekomendasi;

        // Filter Search (Nama MK atau Nama Dosen)
        if ($request->filled('q')) {
            $q = strtolower($request->q);
            $details = $details->filter(function($item) use ($q) {
                return str_contains(strtolower($item->mataKuliah->nama_mk ?? ''), $q) ||
                       str_contains(strtolower($item->user->nama_lengkap ?? ''), $q) ||
                       str_contains(strtolower($item->mataKuliah->kode_mk ?? ''), $q);
            });
        }

        // Filter Prodi (Berdasarkan database)
        if ($request->filled('prodi')) {
            $details = $details->filter(function($item) use ($request) {
                return $item->mataKuliah->prodi_id == $request->prodi;
            });
        }

        // Filter Semester
        if ($request->filled('semester')) {
            $isGanjil = $request->semester == 'Ganjil';
            $details = $details->filter(function($item) use ($isGanjil) {
                // Semester Ganjil adalah 1, 3, 5, 7. Genap adalah 2, 4, 6, 8
                $semesterNum = (int)$item->mataKuliah->semester;
                return $isGanjil ? ($semesterNum % 2 !== 0) : ($semesterNum % 2 === 0);
            });
        }

        // 4. Hitung Stats untuk Dashboard
        $totalMk = $details->pluck('matakuliah_id')->unique()->count();
        $totalKoordinator = $details->filter(function ($d) {
            return strtolower(trim($d->peran_penugasan, "'")) === 'koordinator';
        })->count();
        $totalPengampu = $details->filter(function ($d) {
            return strtolower(trim($d->peran_penugasan, "'")) === 'pengampu';
        })->count();
        $avgSkor = round($details->avg('skor_dosen_di_mk'), 2);


        // 5. Wrap dalam collection agar Blade tetap bisa memakai @forelse ($hasilRekomendasi as $hasil)
        // Kita modifikasi dikit biar groupedMk di blade dapet data yang sudah difilter
        $rekomendasiUtama->setRelation('detailHasilRekomendasi', $details);
        $hasilRekomendasi = collect([$rekomendasiUtama]);

        return view('pages.hasil-rekomendasi', compact(
            'hasilRekomendasi', 
            'listProdi', 
            'totalMk', 
            'totalKoordinator', 
            'totalPengampu', 
            'avgSkor'
        ));
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