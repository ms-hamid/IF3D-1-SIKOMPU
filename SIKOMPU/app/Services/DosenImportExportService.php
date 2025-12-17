<?php

namespace App\Services;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk handle Import & Export Dosen
 * Pisahkan business logic dari controller
 */
class DosenImportExportService
{
    /**
     * Import data dosen dari file Excel
     * 
     * @param string $filePath - Path file Excel yang diupload
     * @return array ['status' => 'success/warning/error', 'message' => '...', 'errors' => [...]]
     */
    public function import($filePath)
    {
        $errors = [];
        $successCount = 0;
        $skipCount = 0;

        try {
            DB::beginTransaction();

            // Load file Excel
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row (row 1)
            $dataRows = array_slice($rows, 1);

            foreach ($dataRows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena index 0 dan header

                // Skip baris kosong
                if ($this->isEmptyRow($row)) {
                    continue;
                }

                // Import per baris
                $result = $this->importRow($row, $rowNumber);
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $skipCount++;
                    $errors[] = $result['error'];
                }
            }

            DB::commit();

            // Generate response
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
            $nama_lengkap = trim($row[0] ?? '');
            $nidn = trim($row[1] ?? '');
            $prodi = trim($row[2] ?? '');
            $jabatan = trim($row[3] ?? '');
            $password = trim($row[4] ?? '');

            // Validasi data wajib
            if (empty($nama_lengkap) || empty($nidn) || empty($prodi) || empty($jabatan)) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: Data tidak lengkap (Nama, NIDN, Prodi, Jabatan wajib diisi)"
                ];
            }

            // Validasi NIDN hanya angka
            if (!preg_match('/^[0-9]+$/', $nidn)) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: NIDN/NIP harus berupa angka ({$nidn})"
                ];
            }

            // Cek duplikasi NIDN
            if (User::where('nidn', $nidn)->exists()) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: NIDN/NIP {$nidn} sudah terdaftar"
                ];
            }

            // Validasi jabatan
            $validJabatan = ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi', 'Dosen', 'Laboran'];
            if (!in_array($jabatan, $validJabatan)) {
                return [
                    'success' => false,
                    'error' => "Baris {$rowNumber}: Jabatan '{$jabatan}' tidak valid"
                ];
            }

            // ✅ VALIDASI TAMBAHAN: Hanya 1 Kaprodi per prodi
            if ($jabatan === 'Kepala Program Studi') {
                $existingKaprodi = User::where('prodi', $prodi)
                    ->where('jabatan', 'Kepala Program Studi')
                    ->exists();
                
                if ($existingKaprodi) {
                    return [
                        'success' => false,
                        'error' => "Baris {$rowNumber}: Prodi {$prodi} sudah memiliki Kepala Program Studi"
                    ];
                }
            }

            // ✅ VALIDASI TAMBAHAN: Hanya 1 Kajur per prodi
            if ($jabatan === 'Kepala Jurusan') {
                $existingKajur = User::where('prodi', $prodi)
                    ->where('jabatan', 'Kepala Jurusan')
                    ->exists();
                
                if ($existingKajur) {
                    return [
                        'success' => false,
                        'error' => "Baris {$rowNumber}: Prodi {$prodi} sudah memiliki Kepala Jurusan"
                    ];
                }
            }

            // Set max_beban berdasarkan jabatan
            $max_beban = in_array($jabatan, ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi']) ? 12 : 16;

            // Set password: jika kosong pakai NIDN
            $finalPassword = !empty($password) ? $password : $nidn;

            // Insert data
            User::create([
                'nama_lengkap' => $nama_lengkap,
                'nidn' => $nidn,
                'prodi' => $prodi,
                'jabatan' => $jabatan,
                'password' => $finalPassword,
                'status' => 'Aktif',
                'beban_mengajar' => 0,
                'max_beban' => $max_beban
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
     * 
     * @return array ['path' => '...', 'filename' => '...']
     */
    public function generateTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $headers = ['Nama Lengkap', 'NIDN/NIP', 'Prodi', 'Jabatan', 'Password (Opsional)'];
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
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Set column width
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(20);

        // Contoh data
        $exampleData = [
            ['Dr. Ahmad Subhan, M.Kom', '0123456789', 'Teknik Informatika', 'Kepala Program Studi', ''],
            ['Dr. Siti Aminah, M.T', '0987654321', 'Animasi', 'Dosen', '']
        ];
        $sheet->fromArray($exampleData, null, 'A2');
        $sheet->getStyle('A2:E3')->getFont()->setItalic(true);
        $sheet->getStyle('A2:E3')->getFont()->getColor()->setRGB('666666');

        // Instruksi
        $instructions = [
            ['INSTRUKSI IMPORT DATA DOSEN:'],
            ['1. Isi data mulai dari baris 2 (atau hapus contoh dan mulai dari baris 2)'],
            ['2. Nama Lengkap, NIDN/NIP, Prodi, dan Jabatan WAJIB diisi'],
            ['3. NIDN/NIP harus berupa angka saja (contoh: 0123456789)'],
            ['4. Jabatan yang valid: Kepala Jurusan, Sekretaris Jurusan, Kepala Program Studi, Dosen, Laboran'],
            ['5. PENTING: Setiap prodi hanya boleh punya 1 Kepala Program Studi dan 1 Kepala Jurusan'],
            ['6. Password opsional (jika kosong akan otomatis pakai NIDN sebagai password)'],
            ['7. Hapus baris contoh dan instruksi ini sebelum import'],
            [''],
            ['CONTOH PRODI YANG VALID:'],
            ['- Teknik Informatika'],
            ['- Teknik Geomatika'],
            ['- Teknologi Rekayasa Multimedia'],
            ['- Animasi'],
            ['- Rekayasa Keamanan Siber'],
            ['- Teknologi Rekayasa Perangkat Lunak'],
            ['- Teknologi Permainan'],
            ['- S2 Magister Terapan Teknik Komputer']
        ];
        $sheet->fromArray($instructions, null, 'A5');
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->getStyle('A5:A22')->getFont()->setSize(9);

        // Save to temp file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_import_dosen_' . date('YmdHis') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return [
            'path' => $tempFile,
            'filename' => $fileName
        ];
    }

    /**
     * Export data dosen ke Excel
     * 
     * @param \Illuminate\Database\Eloquent\Collection $dosens
     * @return array ['path' => '...', 'filename' => '...']
     */
    public function exportToExcel($dosens)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['No', 'Nama Lengkap', 'NIDN/NIP', 'Prodi', 'Jabatan', 'Beban Mengajar', 'Max Beban', 'Status'];
        $sheet->fromArray([$headers], null, 'A1');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ]
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Data
        $row = 2;
        foreach ($dosens as $index => $dosen) {
            $data = [
                $index + 1,
                $dosen->nama_lengkap,
                $dosen->nidn,
                $dosen->prodi,
                $dosen->jabatan,
                $dosen->beban_mengajar ?? 0,
                $dosen->max_beban ?? 16,
                $dosen->status ?? 'Aktif'
            ];
            $sheet->fromArray([$data], null, 'A' . $row);
            $row++;
        }

        // Auto width
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Save to temp file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_dosen_' . date('YmdHis') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return [
            'path' => $tempFile,
            'filename' => $fileName
        ];
    }

    /**
     * Helper: Cek apakah baris kosong
     */
    protected function isEmptyRow($row)
    {
        foreach ($row as $cell) {
            if (!empty(trim($cell))) {
                return false;
            }
        }
        return true;
    }

    /**
     * Helper: Generate response untuk import
     */
    protected function generateImportResponse($successCount, $skipCount, $errors)
    {
        // Jika semua berhasil
        if ($successCount > 0 && empty($errors)) {
            return [
                'status' => 'success',
                'message' => "Import berhasil! {$successCount} data dosen berhasil ditambahkan.",
                'errors' => []
            ];
        }

        // Jika ada yang berhasil dan ada yang error
        if ($successCount > 0 && !empty($errors)) {
            return [
                'status' => 'warning',
                'message' => "Import selesai! {$successCount} data berhasil ditambahkan, {$skipCount} data dilewati.",
                'errors' => $errors
            ];
        }

        // Jika semua error
        return [
            'status' => 'error',
            'message' => 'Import gagal! Semua data memiliki kesalahan.',
            'errors' => $errors
        ];
    }
}