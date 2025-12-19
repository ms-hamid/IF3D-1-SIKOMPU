<?php

namespace App\Services;

use App\Models\MataKuliah;
use App\Models\Prodi;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk handle Import & Export Mata Kuliah
 * Pattern yang sama dengan DosenImportExportService
 */
class MataKuliahImportExportService
{
    /**
     * Import data mata kuliah dari file Excel
     */
    public function import($filePath)
    {
        $errors = [];
        $successCount = 0;
        $skipCount = 0;

        try {
            DB::beginTransaction();

            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row
            $dataRows = array_slice($rows, 1);

            foreach ($dataRows as $index => $row) {
                $rowNumber = $index + 2;

                if ($this->isEmptyRow($row)) {
                    continue;
                }

                $result = $this->importRow($row, $rowNumber);
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $skipCount++;
                    $errors[] = $result['error'];
                }
            }

            DB::commit();

            return $this->generateImportResponse($successCount, $skipCount, $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Gagal mengimpor data: ' . $e->getMessage(),
                'errors' => []
            ];
        }
    }

    /**
     * Import satu baris data
     */
    protected function importRow($row, $rowNumber)
    {
        try {
            // Mapping kolom Excel
            $kode_mk = trim($row[0] ?? '');
            $nama_mk = trim($row[1] ?? '');
            $sks = trim($row[2] ?? '');
            $sesi = trim($row[3] ?? '');
            $semester = trim($row[4] ?? '');
            $kode_prodi = trim($row[5] ?? '');

            // Validasi data wajib
            if (empty($kode_mk) || empty($nama_mk) || empty($sks) || empty($sesi) || empty($semester) || empty($kode_prodi)) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: Data tidak lengkap (semua kolom wajib diisi)"
                ];
            }

            // Validasi panjang kode MK
            if (strlen($kode_mk) > 20) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: Kode MK terlalu panjang (maksimal 20 karakter)"
                ];
            }

            // Cek duplikasi kode MK
            if (MataKuliah::where('kode_mk', $kode_mk)->exists()) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: Kode MK '{$kode_mk}' sudah terdaftar"
                ];
            }

            // Validasi SKS
            if (!is_numeric($sks) || $sks < 1 || $sks > 6) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: SKS harus angka 1-6 ({$sks})"
                ];
            }

            // Validasi Sesi
            if (!is_numeric($sesi) || $sesi < 1 || $sesi > 20) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: Sesi harus angka 1-20 ({$sesi})"
                ];
            }

            // Validasi Semester (1-10, Ganjil, atau Genap)
            $validSemester = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Ganjil', 'Genap'];
            if (!in_array($semester, $validSemester)) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: Semester tidak valid (harus: 1-10, Ganjil, atau Genap)"
                ];
            }

            // Cari Prodi berdasarkan kode
            $prodi = Prodi::where('kode_prodi', $kode_prodi)->first();
            if (!$prodi) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: Kode Prodi '{$kode_prodi}' tidak ditemukan"
                ];
            }

            // Insert data
            MataKuliah::create([
                'kode_mk' => $kode_mk,
                'nama_mk' => $nama_mk,
                'sks' => (int)$sks,
                'sesi' => (int)$sesi,
                'semester' => $semester,
                'prodi_id' => $prodi->id
            ]);

            return ['success' => true];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => "Baris {$rowNumber}: " . $e->getMessage()
            ];
        }
    }

    /**
     * Generate template Excel untuk import
     */
    public function generateTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $headers = ['Kode MK', 'Nama Mata Kuliah', 'SKS', 'Sesi', 'Semester', 'Kode Prodi'];
        $sheet->fromArray([$headers], null, 'A1');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        // Set column width
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);

        // Contoh data
        $exampleData = ['TIF101', 'Pemrograman Dasar', '3', '4', '1', 'TI'];
        $sheet->fromArray([$exampleData], null, 'A2');
        $sheet->getStyle('A2:F2')->getFont()->setItalic(true);
        $sheet->getStyle('A2:F2')->getFont()->getColor()->setRGB('666666');

        // Instruksi
        $instructions = [
            ['INSTRUKSI:'],
            ['1. Isi data mulai dari baris 2'],
            ['2. Semua kolom WAJIB diisi'],
            ['3. Kode MK maksimal 20 karakter dan harus unik'],
            ['4. SKS: angka 1-6'],
            ['5. Sesi: angka 1-20'],
            ['6. Semester: 1-10, Ganjil, atau Genap'],
            ['7. Kode Prodi harus sesuai dengan data yang ada (contoh: TI, TG, TRPL, dll)'],
            ['8. Hapus baris contoh sebelum import']
        ];
        $sheet->fromArray($instructions, null, 'A4');
        $sheet->getStyle('A4')->getFont()->setBold(true);

        // Save to temp file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_import_matakuliah_' . date('YmdHis') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return [
            'path' => $tempFile,
            'filename' => $fileName
        ];
    }

    /**
     * Export data mata kuliah ke Excel
     */
    public function exportToExcel($mataKuliahs)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['No', 'Kode MK', 'Nama Mata Kuliah', 'SKS', 'Sesi', 'Semester', 'Prodi'];
        $sheet->fromArray([$headers], null, 'A1');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ]
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Data
        $row = 2;
        foreach ($mataKuliahs as $index => $mk) {
            $data = [
                $index + 1,
                $mk->kode_mk,
                $mk->nama_mk,
                $mk->sks,
                $mk->sesi,
                $mk->semester,
                $mk->prodi->nama_prodi ?? '-'
            ];
            $sheet->fromArray([$data], null, 'A' . $row);
            $row++;
        }

        // Auto width
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Save to temp file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_matakuliah_' . date('YmdHis') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return [
            'path' => $tempFile,
            'filename' => $fileName
        ];
    }

    protected function isEmptyRow($row)
    {
        foreach ($row as $cell) {
            if (!empty(trim($cell))) {
                return false;
            }
        }
        return true;
    }

    protected function generateImportResponse($successCount, $skipCount, $errors)
    {
        if ($successCount > 0 && empty($errors)) {
            return [
                'status' => 'success',
                'message' => "Import berhasil! {$successCount} data mata kuliah berhasil ditambahkan.",
                'errors' => []
            ];
        }

        if ($successCount > 0 && !empty($errors)) {
            return [
                'status' => 'warning',
                'message' => "Import selesai! {$successCount} data berhasil ditambahkan, {$skipCount} data dilewati.",
                'errors' => $errors
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Import gagal! Semua data memiliki kesalahan.',
            'errors' => $errors
        ];
    }
}