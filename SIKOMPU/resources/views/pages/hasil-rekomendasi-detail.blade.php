@extends('layouts.app')

@section('title', 'Detail Mata Kuliah')
@section('page_title', 'Detail Mata Kuliah')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6">

    {{-- Tombol Kembali --}}
    <div class="mb-8">
        <a href="{{ route('hasil.rekomendasi') }}"
           class="px-4 py-2 bg-blue-700 rounded hover:bg-blue-800 text-white">
            ← Kembali ke Hasil Rekomendasi
        </a>
    </div>

    @foreach($detail->groupBy('matakuliah_id') as $details)

        @php
            $mk = $details->first()->mataKuliah;

            // ================= KATEGORI MK + BOBOT =================
            $mkKategori = $mk->kategori ?? collect();

            $bobotKategori = $mkKategori->mapWithKeys(function ($k) {
                return [$k->id => $k->pivot->bobot];
            });

            // ================= HELPER =================
            $getSelfAssessment = function ($user) use ($mk) {
                return optional(
                    $user->selfAssessments
                        ->where('matakuliah_id', $mk->id)
                        ->sortByDesc('created_at')
                        ->first()
                )->nilai;
            };

            $getPendidikan = function ($user) {
                return optional($user->pendidikans->last())->jenjang;
            };

            $getTotalSertifikat = function ($user) use ($bobotKategori) {
                $total = 0;
                foreach ($user->sertifikat as $s) {
                    if (isset($bobotKategori[$s->kategori_id])) {
                        $total += $bobotKategori[$s->kategori_id];
                    }
                }
                return $total;
            };

            $getJumlahPenelitian = function ($user) use ($bobotKategori) {
                return $user->penelitians
                    ->whereIn('kategori_id', $bobotKategori->keys())
                    ->count();
            };

            // ================= DATA =================
            $koor = $details->firstWhere('peran_penugasan', 'Koordinator');
            $pengampu = $details->where('peran_penugasan', 'Pengampu');
        @endphp

        <div class="mb-6 border rounded-lg p-5 shadow bg-white">

            {{-- Info MK --}}
            <h2 class="text-xl font-bold mb-1">
                {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
            </h2>
            <p class="text-gray-500 mb-4">
                {{ $mk->sks }} SKS • Semester {{ $mk->semester }}
            </p>

            {{-- ================= KOORDINATOR (TABLE) ================= --}}
            <div class="mb-10">
                <h3 class="font-semibold mb-3">Koordinator</h3>

                @if($koor && $koor->user)
                    <div class="overflow-x-auto">
                        <table class="min-w-full data-table text-sm">
                            <colgroup>
                                <col style="width: 30%">
                                <col style="width: 14%">
                                <col style="width: 14%">
                                <col style="width: 14%">
                                <col style="width: 14%">
                                <col style="width: 14%">
                            </colgroup>
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-3 py-2 text-left">Nama</th>
                                    <th class="border px-3 py-2 text-center">Self Assessment</th>
                                    <th class="border px-3 py-2 text-center">Pendidikan</th>
                                    <th class="border px-3 py-2 text-center">Sertifikat</th>
                                    <th class="border px-3 py-2 text-center">Penelitian</th>
                                    <th class="border px-3 py-2 text-center">Skor AI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-gray-50">
                                    <td class="border px-3 py-2 text-left">
                                        {{ $koor->user->nama_lengkap }}
                                    </td>
                                    <td class="border px-3 py-2 text-center">
                                        {{ $getSelfAssessment($koor->user) ?? '-' }}
                                    </td>
                                    <td class="border px-3 py-2 text-center">
                                        {{ $getPendidikan($koor->user) ?? '-' }}
                                    </td>
                                    <td class="border px-3 py-2 text-center">
                                        {{ $getTotalSertifikat($koor->user) }}
                                    </td>
                                    <td class="border px-3 py-2 text-center">
                                        {{ $getJumlahPenelitian($koor->user) }}
                                    </td>
                                    <td class="border px-3 py-2 text-center font-semibold">
                                        {{ number_format($koor->skor_dosen_di_mk, 3) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <span class="text-gray-500">Belum ditentukan</span>
                @endif
            </div>

            {{-- ================= PENGAMPU (TABLE) ================= --}}
            <div>
                <h3 class="font-semibold mb-3">
                    Pengampu ({{ $pengampu->count() }})
                </h3>

                @if($pengampu->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full data-table text-sm">
                            <colgroup>
                                <col style="width: 30%">
                                <col style="width: 14%">
                                <col style="width: 14%">
                                <col style="width: 14%">
                                <col style="width: 14%">
                                <col style="width: 14%">
                            </colgroup>
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-3 py-2 text-left">Nama</th>
                                    <th class="border px-3 py-2 text-center">Self Assessment</th>
                                    <th class="border px-3 py-2 text-center">Pendidikan</th>
                                    <th class="border px-3 py-2 text-center">Sertifikat</th>
                                    <th class="border px-3 py-2 text-center">Penelitian</th>
                                    <th class="border px-3 py-2 text-center">Skor AI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengampu as $p)
                                    @if($p->user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="border px-3 py-2 text-left">
                                                {{ $p->user->nama_lengkap }}
                                            </td>
                                            <td class="border px-3 py-2 text-center">
                                                {{ $getSelfAssessment($p->user) ?? '-' }}
                                            </td>
                                            <td class="border px-3 py-2 text-center">
                                                {{ $getPendidikan($p->user) ?? '-' }}
                                            </td>
                                            <td class="border px-3 py-2 text-center">
                                                {{ $getTotalSertifikat($p->user) }}
                                            </td>
                                            <td class="border px-3 py-2 text-center">
                                                {{ $getJumlahPenelitian($p->user) }}
                                            </td>
                                            <td class="border px-3 py-2 text-center font-semibold">
                                                {{ number_format($p->skor_dosen_di_mk, 3) }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <span class="text-gray-500 ml-4">Belum ada pengampu</span>
                @endif
            </div>
        </div>
    @endforeach

</main>
@endsection
