<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Final Pengampu - {{ $semester }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { text-align: center; color: #1f2937; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #6b7280; margin-bottom: 10px; font-size: 14px; }
        .info { text-align: center; color: #374151; margin-bottom: 20px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #4B5563; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .summary { margin-top: 30px; padding: 15px; background: #f3f4f6; border-radius: 8px; }
        .summary-title { font-weight: bold; margin-bottom: 10px; color: #1f2937; }
        .summary-item { margin: 5px 0; color: #374151; }
        .print-btn { 
            position: fixed; top: 20px; right: 20px; 
            padding: 10px 20px; background: #3b82f6; color: white; 
            border: none; border-radius: 5px; cursor: pointer; 
            font-weight: bold; z-index: 1000;
        }
        .print-btn:hover { background: #2563eb; }
        @media print { 
            .print-btn { display: none; }
            body { padding: 0; }
        }
        .footer { margin-top: 30px; color: #6b7280; font-size: 10px; text-align: right; }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Print / Save PDF</button>
    
    <h1>REKAP FINAL PENGAMPU MATA KULIAH</h1>
    <p class="subtitle">Semester: {{ $semester }}</p>
    <p class="info">Tanggal Generate: {{ now()->format('d F Y, H:i') }}</p>
    
    @if($dataPerMK->isEmpty())
        <p style="text-align: center; color: #999;">Tidak ada data pengampu untuk semester ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 10%;">Kode MK</th>
                    <th style="width: 30%;">Mata Kuliah</th>
                    <th style="width: 10%;">SKS</th>
                    <th style="width: 10%;">Sesi</th>
                    <th style="width: 15%;">Prodi</th>
                    <th style="width: 10%;">Koordinator</th>
                    <th style="width: 10%;">Pengampu</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($dataPerMK as $matakuliahId => $details)
                    @php
                        $mk = $details->first()->mataKuliah;
                        $koordinator = $details->first(fn($d) => strtolower($d->peran_penugasan) === 'koordinator');
                        $pengampus = $details->filter(fn($d) => strtolower($d->peran_penugasan) === 'pengampu');
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $mk->kode_mk ?? '-' }}</td>
                        <td>{{ $mk->nama_mk ?? '-' }}</td>
                        <td>{{ $mk->sks ?? '-' }}</td>
                        <td>{{ $mk->sesi ?? '-' }}</td>
                        <td>{{ $mk->prodi->nama_prodi ?? '-' }}</td>
                        <td>{{ $koordinator ? $koordinator->user->nama_lengkap : '-' }}</td>
                        <td>
                            @if($pengampus->isNotEmpty())
                                {{ $pengampus->pluck('user.nama_lengkap')->join(', ') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="summary">
            <div class="summary-title">RINGKASAN</div>
            @php
                $totalMK = $dataPerMK->count();
                $totalKoordinator = $dataPerMK->filter(fn($details) => $details->first(fn($d) => strtolower($d->peran_penugasan) === 'koordinator'))->count();
                $totalPengampu = $dataPerMK->sum(fn($details) => $details->filter(fn($d) => strtolower($d->peran_penugasan) === 'pengampu')->count());
            @endphp
            <div class="summary-item">Total Mata Kuliah: <strong>{{ $totalMK }}</strong></div>
            <div class="summary-item">Total Koordinator: <strong>{{ $totalKoordinator }}</strong></div>
            <div class="summary-item">Total Pengampu: <strong>{{ $totalPengampu }}</strong></div>
        </div>
    @endif
    
    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y, H:i:s') }} WIB
    </div>
</body>
</html>