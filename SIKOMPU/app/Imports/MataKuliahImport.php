<?php

namespace App\Imports;

use App\Models\MataKuliah;
use App\Models\Prodi;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MataKuliahImport
{
    public function import($filePath)
    {
        // Load file Excel
        $spreadsheet = IOFactory::load($filePath);
        
        // Ambil semua prodi dari database
        $prodis = Prodi::pluck('id', 'kode_prodi')->toArray();
        
        // Loop tiap sheet
        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            // Cek apakah nama sheet cocok dengan kode prodi
            if (!isset($prodis[$sheetName])) {
                continue; // Skip sheet yang tidak ada prodi-nya
            }
            
            $prodi_id = $prodis[$sheetName];
            $worksheet = $spreadsheet->getSheetByName($sheetName);
            
            // Mulai dari baris 29 (row 29 di Excel = index 29)
            $highestRow = $worksheet->getHighestRow();
            
            for ($row = 29; $row <= $highestRow; $row++) {
                $no = $worksheet->getCell('A' . $row)->getValue();
                $aspek = $worksheet->getCell('B' . $row)->getValue();
                
                // Skip jika baris kosong atau bukan data matakuliah
                if (empty($aspek) || !is_numeric($no)) {
                    continue;
                }
                
                // Parse kode dan nama matakuliah
                // Format: "IF101 - Pengantar Proyek Perangkat Lunak"
                if (preg_match('/^([A-Z0-9]+)\s*-\s*(.+)$/i', $aspek, $matches)) {
                    $kode_mk = trim($matches[1]);
                    $nama_mk = trim($matches[2]);
                    
                    // Cek apakah matakuliah sudah ada
                    $exists = MataKuliah::where('kode_mk', $kode_mk)
                                        ->where('prodi_id', $prodi_id)
                                        ->exists();
                    
                    // Insert hanya jika belum ada
                    if (!$exists) {
                        MataKuliah::create([
                            'prodi_id' => $prodi_id,
                            'kode_mk' => $kode_mk,
                            'nama_mk' => $nama_mk,
                            'sks' => null,           
                            'sesi' => null,          
                            'semester' => null,  
                        ]);
                    }
                }
            }
        }
    }
}