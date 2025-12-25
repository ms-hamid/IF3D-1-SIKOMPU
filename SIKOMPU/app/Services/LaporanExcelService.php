<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanExcelService
{
    /**
     * Generate Rekap Pengampu Excel
     */
    public function generateRekapPengampu($dataPerMK, $semester)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Pengampu');

        // Header
        $this->setHeader($sheet, 'REKAP FINAL PENGAMPU MATA KULIAH', $semester);

        // Table Header
        $row = 6;
        $headers = ['No', 'Kode MK', 'Mata Kuliah', 'SKS', 'Sesi', 'Prodi', 'Koordinator', 'Pengampu', 'Total Dosen'];
        $this->setTableHeader($sheet, $row, $headers);

        // Data
        $row++;
        $no = 1;
        foreach ($dataPerMK as $mataKuliahId => $details) {
            $mk = $details->first()->mataKuliah;
            
            $koordinator = $details->first(fn($d) => strtolower($d->peran_penugasan) === 'koordinator');
            $pengampuList = $details->filter(fn($d) => strtolower($d->peran_penugasan) === 'pengampu');

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $mk->kode_mk);
            $sheet->setCellValue('C' . $row, $mk->nama_mk);
            $sheet->setCellValue('D' . $row, $mk->sks);
            $sheet->setCellValue('E' . $row, $mk->sesi);
            $sheet->setCellValue('F' . $row, $mk->prodi->nama_prodi ?? '-');
            $sheet->setCellValue('G' . $row, $koordinator ? $koordinator->user->nama_lengkap : '-');
            $sheet->setCellValue('H' . $row, $pengampuList->pluck('user.nama_lengkap')->implode(', ') ?: '-');
            $sheet->setCellValue('I' . $row, $details->count());

            $this->setBorders($sheet, 'A' . $row . ':I' . $row);
            $row++;
        }

        $this->autoSize($sheet, 'A', 'I');
        
        $fileName = 'Rekap_Pengampu_' . str_replace(['/', ' '], ['_', '_'], $semester) . '.xlsx';
        return $this->download($spreadsheet, $fileName);
    }

    /**
     * Generate Beban Mengajar Excel
     */
    public function generateBebanMengajar($dataPerDosen, $semester)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Beban Mengajar');

        $this->setHeader($sheet, 'LAPORAN BEBAN MENGAJAR DOSEN', $semester);

        $row = 6;
        $headers = ['No', 'NIDN', 'Nama Dosen', 'Jabatan', 'Jumlah MK', 'Total Sesi', 'Max Sesi', 'Status Beban', 'Peran'];
        $this->setTableHeader($sheet, $row, $headers);

        $row++;
        $no = 1;
        foreach ($dataPerDosen as $userId => $details) {
            $dosen = $details->first()->user;
            $jumlahMK = $details->groupBy('matakuliah_id')->count();
            
            $totalSesi = $details->sum(fn($d) => $d->mataKuliah->sesi ?? 0);
            $maxSesi = $this->getMaxSesi($dosen->jabatan);
            $statusBeban = $this->getStatusBeban($totalSesi, $maxSesi);

            $koordinatorCount = $details->filter(fn($d) => strtolower($d->peran_penugasan) === 'koordinator')->count();
            $pengampuCount = $details->filter(fn($d) => strtolower($d->peran_penugasan) === 'pengampu')->count();

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $dosen->nidn);
            $sheet->setCellValue('C' . $row, $dosen->nama_lengkap);
            $sheet->setCellValue('D' . $row, $dosen->jabatan);
            $sheet->setCellValue('E' . $row, $jumlahMK);
            $sheet->setCellValue('F' . $row, $totalSesi);
            $sheet->setCellValue('G' . $row, $maxSesi);
            $sheet->setCellValue('H' . $row, $statusBeban);
            $sheet->setCellValue('I' . $row, "{$koordinatorCount} Koordinator, {$pengampuCount} Pengampu");

            $this->setBorders($sheet, 'A' . $row . ':I' . $row);
            $this->setStatusColor($sheet, 'H' . $row, $statusBeban);
            $row++;
        }

        $this->autoSize($sheet, 'A', 'I');

        $fileName = 'Beban_Mengajar_' . str_replace(['/', ' '], ['_', '_'], $semester) . '.xlsx';
        return $this->download($spreadsheet, $fileName);
    }

    /**
     * Generate Statistik Koordinator Excel
     */
    public function generateStatistikKoordinator($dataPerDosen, $koordinatorList, $semester)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Statistik Koordinator');

        $this->setHeader($sheet, 'STATISTIK KOORDINATOR MATA KULIAH', $semester);

        $row = 6;
        $headers = ['No', 'NIDN', 'Nama Dosen', 'Jabatan', 'Jumlah MK Dikoordinasi', 'Daftar Mata Kuliah'];
        $this->setTableHeader($sheet, $row, $headers);

        $row++;
        $no = 1;
        foreach ($dataPerDosen as $userId => $details) {
            $dosen = $details->first()->user;
            
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $dosen->nidn);
            $sheet->setCellValue('C' . $row, $dosen->nama_lengkap);
            $sheet->setCellValue('D' . $row, $dosen->jabatan);
            $sheet->setCellValue('E' . $row, $details->count());
            $sheet->setCellValue('F' . $row, $details->pluck('mataKuliah.nama_mk')->implode(', '));

            $this->setBorders($sheet, 'A' . $row . ':F' . $row);
            $row++;
        }

        // Summary
        $row += 2;
        $sheet->setCellValue('A' . $row, 'RINGKASAN STATISTIK');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;

        $totalKoordinator = $dataPerDosen->count();
        $totalMK = $koordinatorList->groupBy('matakuliah_id')->count();
        $avgMK = $totalKoordinator > 0 ? round($totalMK / $totalKoordinator, 2) : 0;

        $sheet->setCellValue('A' . $row, 'Total Koordinator:');
        $sheet->setCellValue('B' . $row, $totalKoordinator);
        $row++;
        $sheet->setCellValue('A' . $row, 'Total MK Dikoordinasi:');
        $sheet->setCellValue('B' . $row, $totalMK);
        $row++;
        $sheet->setCellValue('A' . $row, 'Rata-rata MK per Koordinator:');
        $sheet->setCellValue('B' . $row, $avgMK);

        $this->autoSize($sheet, 'A', 'F');

        $fileName = 'Statistik_Koordinator_' . str_replace(['/', ' '], ['_', '_'], $semester) . '.xlsx';
        return $this->download($spreadsheet, $fileName);
    }

    // ===================================================================
    // HELPER METHODS
    // ===================================================================

    private function setHeader($sheet, $title, $subtitle)
    {
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Semester: ' . $subtitle);
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->getFont()->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Tanggal Cetak: ' . now()->format('d F Y, H:i'));
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function setTableHeader($sheet, $row, $headers)
    {
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('4B5563');
            $sheet->getStyle($col . $row)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $col++;
        }
    }

    private function setBorders($sheet, $range)
    {
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    }

    private function setStatusColor($sheet, $cell, $status)
    {
        $color = match($status) {
            'Overload' => 'FCA5A5',
            'Normal' => '86EFAC',
            default => 'FDE68A',
        };

        $sheet->getStyle($cell)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB($color);
    }

    private function autoSize($sheet, $startCol, $endCol)
    {
        foreach (range($startCol, $endCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function getMaxSesi($jabatan)
    {
        return in_array($jabatan, ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi']) ? 12 : 20;
    }

    private function getStatusBeban($totalSesi, $maxSesi)
    {
        if ($totalSesi > $maxSesi) return 'Overload';
        if ($totalSesi >= $maxSesi * 0.8) return 'Normal';
        return 'Underload';
    }

    private function download($spreadsheet, $fileName)
{
    try {
        $writer = new Xlsx($spreadsheet);
        
        // Simpan ke temporary file
        $temp_file = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($temp_file);
        
        // Return sebagai download response Laravel
        return response()->download($temp_file, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
        
    } catch (\Exception $e) {
        \Log::error('Error generating Excel: ' . $e->getMessage());
        throw $e;
    }
}
}