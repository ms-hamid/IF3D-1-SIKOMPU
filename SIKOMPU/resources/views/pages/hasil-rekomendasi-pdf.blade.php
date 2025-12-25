<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Rekomendasi Pengampu</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #1e40af;
        }
        
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: #1e40af;
            font-weight: bold;
        }
        
        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #666;
        }
        
        .info-box {
            background: #f8f9fa;
            padding: 8px 10px;
            margin-bottom: 12px;
            border-left: 3px solid #1e40af;
            width: 100%;
        }
        
        .info-box p {
            margin: 3px 0;
            font-size: 9px;
        }
        
        .info-box strong {
            color: #1e40af;
            display: inline-block;
            min-width: 120px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        
        thead th {
            background-color: #1e40af;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #1e40af;
        }
        
        tbody td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            font-size: 8px;
            vertical-align: top;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .text-center { text-align: center; }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .small-text {
            font-size: 7px;
            color: #666;
            margin-top: 2px;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: right;
            font-size: 8px;
            color: #666;
        }
        
        .signature {
            margin-top: 30px;
            text-align: right;
        }
        
        .signature-line {
            margin-top: 50px;
            border-bottom: 1px solid #333;
            width: 200px;
            display: inline-block;
        }

        .col-no { width: 3%; }
        .col-kode { width: 8%; }
        .col-mk { width: 20%; }
        .col-sks { width: 4%; }
        .col-sem { width: 4%; }
        .col-koordinator { width: 22%; }
        .col-skor { width: 7%; }
        .col-pengampu { width: 25%; }
        .col-jml { width: 4%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>HASIL REKOMENDASI PENGAMPU MATA KULIAH</h1>
        <p>Politeknik Negeri Batam</p>
        <p>{{ $semester }}</p>
    </div>

    <div class="info-box">
        <p><strong>Program Studi:</strong> {{ $filterInfo['prodi'] }}</p>
        <p><strong>Filter Semester:</strong> {{ $filterInfo['semester'] }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ $tanggal }}</p>
        <p><strong>Total Mata Kuliah:</strong> {{ count($data) }} MK</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no text-center">NO</th>
                <th class="col-kode">KODE MK</th>
                <th class="col-mk">MATA KULIAH</th>
                <th class="col-sks text-center">SKS</th>
                <th class="col-sem text-center">SEM</th>
                <th class="col-koordinator">KOORDINATOR</th>
                <th class="col-skor text-center">SKOR</th>
                <th class="col-pengampu">PENGAMPU</th>
                <th class="col-jml text-center">JML</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $item['matakuliah']->kode_mk ?? '-' }}</strong></td>
                <td>
                    {{ $item['matakuliah']->nama_mk ?? '-' }}
                    @if(isset($item['matakuliah']->prodi))
                        <div class="small-text">{{ $item['matakuliah']->prodi->nama_prodi }}</div>
                    @endif
                </td>
                <td class="text-center">{{ $item['matakuliah']->sks ?? 0 }}</td>
                <td class="text-center">{{ $item['matakuliah']->semester ?? '-' }}</td>

                {{-- Kolom Koordinator --}}
                <td>
                    @if(isset($item['koordinator']) && $item['koordinator']->user)
                        <div style="font-weight: bold;">
                            {{ $item['koordinator']->user->nama_lengkap }}
                        </div>
                        <div class="small-text">NIDN: {{ $item['koordinator']->user->nidn ?? '-' }}</div>
                    @else
                        <span style="color: #999;">Belum ditentukan</span>
                    @endif
                </td>

                <td class="text-center">
                    <span class="badge">{{ number_format($item['skor'], 3) }}</span>
                </td>

                {{-- Kolom Pengampu --}}
                <td>
                    @if(isset($item['pengampu']) && $item['pengampu']->count() > 0)
                        @foreach($item['pengampu'] as $p)
                            <div style="margin-bottom: 2px;">
                                {{ $loop->iteration }}. {{ $p->user->nama_lengkap ?? 'Nama tidak ada' }}
                                <span style="font-size: 6px; color: #888;">({{ $p->user->nidn ?? '-' }})</span>
                            </div>
                        @endforeach
                    @else
                        <span style="color: #999; font-style: italic;">Belum ada pengampu</span>
                    @endif
                </td>

                <td class="text-center"><strong>{{ $item['pengampu']->count() }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 20px;">
                    <em>Tidak ada data rekomendasi</em>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem pada {{ now()->format('d F Y, H:i:s') }} WIB</p>
    </div>

    <div class="signature">
        <p>Batam, {{ $tanggal }}</p>
        <p style="margin-top: 40px;">
            <span class="signature-line"></span><br>
            <strong>Kepala Program Studi</strong>
        </p>
    </div>
</body>
</html>