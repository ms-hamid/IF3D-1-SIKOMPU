@extends('layouts.app')

@section('title', 'Hasil Rekomendasi')
@section('page_title', 'Hasil Rekomendasi')

@section('content')
<main class="flex-1 p-6 space-y-8 bg-gray-50 min-h-screen">

    {{-- 1. HEADER & ACTION BUTTONS --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Hasil Rekomendasi</h2>
            <p class="text-sm text-gray-500 mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
                Semester Ganjil 2025/2026 — Politeknik Negeri Batam
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('hasil-rekomendasi.generate') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Generate Rekomendasi
                </button>
            </form>

            <a href="{{ route('hasil-rekomendasi.export.pdf', request()->all()) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                PDF
            </a>

            <a href="{{ route('hasil-rekomendasi.export.excel', request()->all()) }}" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Excel
            </a>
        </div>
    </div>

    {{-- 2. STATS CARDS (Tetap sama) --}}
    @php
    $cards = [
        ['title' => 'Total Mata Kuliah', 'value' => $totalMk ?? 0, 'color' => 'blue', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ['title' => 'Koordinator Ditetapkan', 'value' => $totalKoordinator ?? 0, 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['title' => 'Pengampu Ditetapkan', 'value' => $totalPengampu ?? 0, 'color' => 'purple', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0m6 3a2 2 0 11-4 0 2 2 0 014 0M7 10a2 2 0 11-4 0 2 2 0 014 0'],
        ['title' => 'Skor Rata-rata', 'value' => number_format($avgSkor ?? 0, 2), 'color' => 'yellow', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.49 10.101c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z']
    ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($cards as $card)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between transition-transform hover:scale-[1.02]">
            <div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">{{ $card['title'] }}</p>
                <p class="text-3xl font-black text-gray-900 mt-1">{{ $card['value'] }}</p>
            </div>
            <div class="p-3.5 rounded-2xl bg-{{ $card['color'] }}-50 text-{{ $card['color'] }}-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"></path>
                </svg>
            </div>
        </div>
        @endforeach
    </div>

    {{-- 3. FILTER SECTION --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <x-filter-rekomendasi :list-prodi="$listProdi" />
    </div>

    {{-- 4. TABLE SECTION --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Mata Kuliah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Koordinator</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Pengampu</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Skor</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($hasilRekomendasi as $hasil)
                        @php
                            // Ambil detail, pastikan relasi 'user' terpanggil
                            $groupedMk = $hasil->detailHasilRekomendasi->groupBy('matakuliah_id');
                        @endphp

                        @foreach ($groupedMk as $details)
                            @php
                                $mk = optional($details->first())->mataKuliah;
                                // Perbaikan pencarian peran (case-insensitive)
                                $koor = $details->filter(function ($d) {
                                    return strtolower(trim($d->peran_penugasan, "'")) === 'koordinator';
                                })->first();

                                $pengampu = $details->filter(function ($d) {
                                    return strtolower(trim($d->peran_penugasan, "'")) === 'pengampu';
                                });
                                $skor = $koor?->skor_dosen_di_mk ?? 0;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- NAMA MK --}}
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-blue-600 mb-0.5">{{ $mk?->kode_mk }}</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $mk?->nama_mk }}</span>
                                        <span class="text-xs text-gray-400 mt-1">{{ $mk?->sks }} SKS • Semester {{ $mk?->semester }}</span>
                                    </div>
                                </td>

                                {{-- KOORDINATOR --}}
                                <td class="px-6 py-5">
                                    @if($koor && $koor->user)
                                        <div class="flex items-center group">
                                            <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white shadow-sm group-hover:scale-110 transition-transform">
                                                {{ strtoupper(substr($koor->user->nama_lengkap, 0, 2)) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-bold text-gray-800 leading-none">{{ $koor->user->nama_lengkap }}</p>
                                                <p class="text-[10px] text-gray-400 mt-1 uppercase font-medium">NIDN: {{ $koor->user->nidn ?? '-' }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400">Belum Ada</span>
                                    @endif
                                </td>

                                {{-- PENGAMPU --}}
                                <td class="px-6 py-5">
                                    @php $pengampuUtama = $pengampu->first(); @endphp
                                    @if($pengampuUtama && $pengampuUtama->user)
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 border border-slate-200">
                                                {{ strtoupper(substr($pengampuUtama->user->nama_lengkap, 0, 2)) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-700">{{ $pengampuUtama->user->nama_lengkap }}</p>
                                                @if($pengampu->count() > 1)
                                                    <span class="text-[10px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded font-bold">
                                                        +{{ $pengampu->count() - 1 }} LAINNYA
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-300 italic">Tidak ada pengampu</span>
                                    @endif
                                </td>

                                {{-- SKOR --}}
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg {{ $skor > 0.7 ? 'bg-emerald-50 text-emerald-700' : 'bg-orange-50 text-orange-700' }} text-sm font-bold border {{ $skor > 0.7 ? 'border-emerald-100' : 'border-orange-100' }}">
                                        {{ number_format($skor, 3) }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-5 text-center">
                                    <a href="{{ route('hasil-rekomendasi.detail', ['id' => $hasil->id, 'kode_mk' => $mk->kode_mk]) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-bold text-blue-600 hover:text-white border border-blue-600 hover:bg-blue-600 rounded-lg transition-all">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="bg-gray-50 p-4 rounded-full mb-4">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Data rekomendasi belum tersedia</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection