<?php

namespace App\Exports;

use App\Models\HasilRekomendasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class HasilRekomendasiExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithTitle,
    WithColumnWidths
{
    protected $q;
    protected $prodi;
    protected $semester;
    protected $rowNumber = 0;

    public function __construct($q = null, $prodi = null, $semester = null)
    {
        $this->q = $q;
        $this->prodi = $prodi;
        $this->semester = $semester;
    }

    public function collection()
    {
        $query = HasilRekomendasi::with([
            'detailHasilRekomendasi' => function ($query) {
                if ($this->q) {
                    $query->where(function ($sub) {
                        $sub->whereHas('mataKuliah', function ($mk) {
                            $mk->where('nama_mk', 'like', "%{$this->q}%")
                               ->orWhere('kode_mk', 'like', "%{$this->q}%");
                        })
                        ->orWhereHas('user', function ($u) {
                            // PERBAIKAN: Gunakan nama_lengkap
                            $u->where('nama_lengkap', 'like', "%{$this->q}%");
                        });
                    });
                }

                if ($this->prodi) {
                    $query->whereHas('mataKuliah', function ($mk) {
                        $mk->where('prodi_id', $this->prodi);
                    });
                }

                if ($this->semester) {
                    $query->whereHas('mataKuliah', function ($mk) {
                        $mk->where('semester', $this->semester);
                    });
                }
            },
            'detailHasilRekomendasi.mataKuliah.prodi',
            'detailHasilRekomendasi.user'
        ])->where('is_active', 1)->first();

        if (!$query) {
            return collect([]);
        }

        return $query->detailHasilRekomendasi->groupBy('matakuliah_id')->map(function($details) {
            $mk = $details->first()->mataKuliah;
            // Gunakan strtolower untuk keamanan pengecekan peran
            $koordinator = $details->filter(fn($d) => strtolower($d->peran_penugasan) == 'koordinator')->first();
            $pengampu = $details->filter(fn($d) => strtolower($d->peran_penugasan) == 'pengampu');

            return [
                'matakuliah' => $mk,
                'koordinator' => $koordinator,
                'pengampu' => $pengampu,
                'skor' => $koordinator?->skor_dosen_di_mk ?? 0
            ];
        })->values();
    }

    public function headings(): array
    {
        return [
            'NO',
            'KODE MK',
            'MATA KULIAH',
            'SKS',
            'SEMESTER',
            'PRODI',
            'KOORDINATOR',
            'NIDN KOORDINATOR',
            'SKOR',
            'PENGAMPU',
            'JUMLAH PENGAMPU'
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        // PERBAIKAN: Gunakan nama_lengkap
        $pengampuNames = $row['pengampu']->map(function($p) {
            return $p->user ? $p->user->nama_lengkap : '-';
        })->join(', ');

        return [
            $this->rowNumber,
            $row['matakuliah']->kode_mk ?? '-',
            $row['matakuliah']->nama_mk ?? '-',
            $row['matakuliah']->sks ?? 0,
            $row['matakuliah']->semester ?? '-',
            $row['matakuliah']->prodi->nama_prodi ?? '-',
            // PERBAIKAN: Gunakan nama_lengkap
            $row['koordinator']?->user?->nama_lengkap ?? 'Belum ditentukan',
            $row['koordinator']?->user?->nidn ?? '-',
            number_format($row['skor'], 3),
            $pengampuNames ?: 'Belum ada pengampu',
            $row['pengampu']->count()
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 12,
            'C' => 35,
            'D' => 6,
            'E' => 10,
            'F' => 25,
            'G' => 30,
            'H' => 18,
            'I' => 10,
            'J' => 40,
            'K' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1e40af']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        $highestRow = $sheet->getHighestRow();
        if ($highestRow > 1) {
            $sheet->getStyle("A1:K{$highestRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ]
                ]
            ]);
            
            // Perataan tengah untuk kolom angka/kode
            $sheet->getStyle("A2:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D2:E{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I2:I{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K2:K{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Wrap text agar nama pengampu yang banyak tidak memanjang ke samping
            $sheet->getStyle("J2:J{$highestRow}")->getAlignment()->setWrapText(true);
        }

        return [];
    }

    public function title(): string
    {
        return 'Hasil Rekomendasi';
    }
}