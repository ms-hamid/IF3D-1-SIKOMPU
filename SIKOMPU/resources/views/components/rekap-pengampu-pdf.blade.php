<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm; }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 9pt;
            color: #333;
        }

        /* ================= HEADER ================= */
        .header-container {
            border-bottom: 2px solid #444;
            margin-bottom: 16px;
            padding-bottom: 8px;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }

        .subtitle {
            margin-top: 4px;
            font-size: 12pt;
            color: #555;
        }

        /* ================= TABLE ================= */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #cbd5e0;
            padding: 5px 4px;
            vertical-align: top;
        }

        th {
            background: #f2f2f2;
            text-transform: uppercase;
            font-size: 9pt;
        }

        /* Kolom sempit */
        .col-no,
        .col-kode {
            text-align: center;
            font-size: 8pt;
            padding: 3px 2px;
            white-space: nowrap;
        }

        /* Kolom pengampu */
        .col-dosen-tim {
            word-wrap: break-word;
        }

        ul {
            margin: 0;
            padding-left: 14px;
        }

        li {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>

    <!-- ================= HEADER ================= -->
    <div class="header-container">
        <h1>Rekap Final Pengampu Mata Kuliah</h1>
        <div class="subtitle">Semester: {{ $semester }}</div>
        <div style="font-size:8pt; margin-top:4px;">
            Dicetak pada: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <!-- ================= TABLE ================= -->
    <table>

        <!-- 🔥 KUNCI UTAMA PDF: COLGROUP -->
        <colgroup>
            <col style="width:3%">
            <col style="width:6%">
            <col style="width:22%">
            <col style="width:15%">
            <col style="width:17%">
            <col style="width:37%">
        </colgroup>

        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-kode">Kode</th>
                <th>Mata Kuliah (SKS)</th>
                <th>Program Studi</th>
                <th>Koordinator</th>
                <th>Tim Pengampu</th>
            </tr>
        </thead>

        <tbody>
            @php $no = 1; @endphp
            @foreach($dataPerMK as $details)
                @php
                    $mk = $details->first()->mataKuliah;
                    $koordinator = $details->first(fn($d) =>
                        strtolower($d->peran_penugasan) === 'koordinator'
                    );
                    $pengampus = $details->filter(fn($d) =>
                        strtolower($d->peran_penugasan) === 'pengampu'
                    );
                @endphp

                <tr>
                    <td class="col-no">{{ $no++ }}</td>
                    <td class="col-kode">{{ $mk->kode_mk }}</td>
                    <td>
                        <strong>{{ $mk->nama_mk }}</strong><br>
                        <small>Sesi: {{ $mk->sesi }} | SKS: {{ $mk->sks }}</small>
                    </td>
                    <td>{{ $mk->prodi->nama_prodi ?? '-' }}</td>
                    <td>{{ $koordinator?->user->nama_lengkap ?? '-' }}</td>
                    <td class="col-dosen-tim">
                        @if($pengampus->isNotEmpty())
                            <ul>
                                @foreach($pengampus as $p)
                                    <li>{{ $p->user->nama_lengkap }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span style="color:#aaa;">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
