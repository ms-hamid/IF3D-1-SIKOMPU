<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penugasan - {{ $user->nama_lengkap }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { text-align: center; color: #1f2937; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #6b7280; margin-bottom: 10px; font-size: 14px; }
        .dosen-info { text-align: center; color: #374151; margin-bottom: 20px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #4B5563; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .koordinator { background-color: #dbeafe !important; }
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
    
    <h1>LAPORAN PENUGASAN MATA KULIAH</h1>
    <p class="subtitle">Semester: {{ $semester }}</p>
    <p class="dosen-info">Dosen: {{ $user->nama_lengkap }} ({{ $user->nidn }})</p>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%;">Kode MK</th>
                <th style="width: 35%;">Mata Kuliah</th>
                <th style="width: 15%;">Prodi</th>
                <th style="width: 7%;">SKS</th>
                <th style="width: 7%;">Sesi</th>
                <th style="width: 13%;">Peran</th>
                <th style="width: 8%;">Skor</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalSesi = 0;
                $totalSKS = 0;
            @endphp
            
            @foreach($penugasan as $detail)
                @php
                    $mk = $detail->mataKuliah;
                    $isKoordinator = strtolower($detail->peran_penugasan) === 'koordinator';
                    $totalSesi += $mk->sesi;
                    $totalSKS += $mk->sks;
                @endphp
                <tr class="{{ $isKoordinator ? 'koordinator' : '' }}">
                    <td>{{ $no++ }}</td>
                    <td>{{ $mk->kode_mk }}</td>
                    <td>{{ $mk->nama_mk }}</td>
                    <td>{{ $mk->prodi->nama_prodi ?? '-' }}</td>
                    <td>{{ $mk->sks }}</td>
                    <td>{{ $mk->sesi }}</td>
                    <td>{{ ucfirst($detail->peran_penugasan) }}</td>
                    <td>{{ number_format($detail->skor_dosen_di_mk, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        <div class="summary-title">RINGKASAN</div>
        @php
            $koordinatorCount = $penugasan->filter(fn($d) => strtolower($d->peran_penugasan) === 'koordinator')->count();
            $pengampuCount = $penugasan->filter(fn($d) => strtolower($d->peran_penugasan) === 'pengampu')->count();
            $maxSesi = in_array($user->jabatan, ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi']) ? 12 : 20;
        @endphp
        <div class="summary-item">Total Mata Kuliah: <strong>{{ $penugasan->count() }}</strong></div>
        <div class="summary-item">Total SKS: <strong>{{ $totalSKS }}</strong></div>
        <div class="summary-item">Total Sesi: <strong>{{ $totalSesi }} / {{ $maxSesi }}</strong></div>
        <div class="summary-item">Koordinator: <strong>{{ $koordinatorCount }} MK</strong></div>
        <div class="summary-item">Pengampu: <strong>{{ $pengampuCount }} MK</strong></div>
    </div>
    
    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y, H:i:s') }} WIB
    </div>
</body>
</html>