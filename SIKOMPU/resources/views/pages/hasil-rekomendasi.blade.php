@extends('layouts.app')

@section('title', 'Hasil Rekomendasi')
@section('page_title', 'Hasil Rekomendasi')

@section('content')
    <main class="flex-1 p-4 sm:p-6 space-y-6">

        {{-- ========================================================= --}}
        {{-- 1. HEADER JUDUL + TOMBOL EKSPOR --}}
        {{-- ========================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center border-b border-gray-200 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Hasil Rekomendasi</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Semester Ganjil 2025/2026 - Politeknik Negeri Batam
                </p>
            </div>

            <div class="flex space-x-3 mt-4 sm:mt-0">
                <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 shadow">
                    Ekspor PDF
                </button>
                <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 shadow">
                    Ekspor Excel
                </button>
            </div>
        </div>

        {{-- ========================= --}}
        {{-- 2. RINGKASAN DATA (CARDS) --}}
        {{-- ========================= --}}
        @php
        $cards = [
        [
            'title' => 'Total Mata Kuliah',
            'value' => $totalMk ?? 0,
            'icon_bg' => 'bg-blue-100',
            'icon_text' => 'text-blue-600',
            'icon_svg' => '
                <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2
                        m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2
                        m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                </path>'
        ],
        [
            'title' => 'Koordinator Ditetapkan',
            'value' => $totalKoordinator ?? 0,
            'icon_bg' => 'bg-green-100',
            'icon_text' => 'text-green-600',
            'icon_svg' => '
                <path d="M9 12l2 2 4-4m6 2
                        a9 9 0 11-18 0
                        a9 9 0 0118 0z"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                </path>'
        ],
        [
            'title' => 'Pengampu Ditetapkan',
            'value' => $totalPengampu ?? 0,
            'icon_bg' => 'bg-purple-100',
            'icon_text' => 'text-purple-600',
            'icon_svg' => '
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857
                        M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                        M7 20H2v-2a3 3 0 015.356-1.857
                        M7 20v-2c0-.656.126-1.283.356-1.857
                        m0 0a5.002 5.002 0 019.288 0
                        M15 7a3 3 0 11-6 0 3 3 0 016 0
                        m6 3a2 2 0 11-4 0 2 2 0 014 0
                        M7 10a2 2 0 11-4 0 2 2 0 014 0"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                </path>'
        ],
        [
            'title' => 'Skor Rata-rata',
            'value' => number_format($avgSkor ?? 0, 2),
            'icon_bg' => 'bg-yellow-100',
            'icon_text' => 'text-yellow-700',
            'icon_svg' => '
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0
                        l1.07 3.292a1 1 0 00.95.69h3.462
                        c.969 0 1.371 1.24.588 1.81
                        l-2.817 2.05a1 1 0 00-.363 1.118
                        l1.07 3.292c.3.921-.755 1.688-1.54 1.118
                        l-2.817-2.05a1 1 0 00-1.175 0
                        l-2.817 2.05c-.784.57-1.838-.197-1.539-1.118
                        l1.07-3.292a1 1 0 00-.364-1.118
                        L2.98 8.72c-.783-.57-.38-1.81.588-1.81
                        h3.461a1 1 0 00.951-.69l1.07-3.292z"
                    fill="currentColor">
                </path>'
        ],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($cards as $card)
        <div class="bg-white p-5 rounded-xl shadow-lg flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">{{ $card['title'] }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">
                    {{ $card['value'] }}
                </p>
            </div>

            <div class="p-3 rounded-xl {{ $card['icon_bg'] }} {{ $card['icon_text'] }}">
                <svg class="w-6 h-6"
                    fill="{{ $card['icon_bg'] == 'bg-yellow-100' ? 'currentColor' : 'none' }}"
                    stroke="{{ $card['icon_bg'] == 'bg-yellow-100' ? 'none' : 'currentColor' }}"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">

                    @if ($card['icon_bg'] != 'bg-yellow-100')
                    <g stroke="currentColor">
                    @endif

                    {!! $card['icon_svg'] !!}

                    @if ($card['icon_bg'] != 'bg-yellow-100')
                    </g>
                    @endif
                </svg>
            </div>
        </div>
        @endforeach
        </div>


        {{-- ========================================================= --}}
        {{-- 3. FILTER & PENCARIAN --}}
        {{-- ========================================================= --}}
        <x-filter-rekomendas />

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">

                {{-- TABLE HEADER --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode MK</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Mata Kuliah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Koordinator</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pengampu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Skor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>

                {{-- TABLE BODY --}}
                <tbody class="bg-white divide-y divide-gray-200">

                @forelse ($hasilRekomendasi as $hasil)

                    @php
                        $groupedMk = $hasil->detailHasilRekomendasi->groupBy('matakuliah_id');
                        $isFiltered = request()->has('prodi');
                    @endphp

                    @foreach ($groupedMk as $details)

                        @php
                            $mk = optional($details->first())->mataKuliah;

                            $koor = $details->firstWhere('peran_penugasan_lower', 'koordinator');
                            $pengampu = $details->where('peran_penugasan_lower', 'pengampu');

                            $skor = $koor?->skor_dosen_di_mk ?? 0;
                        @endphp

                        <tr>
                            {{-- KODE MK --}}
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $mk?->kode_mk ?? '-' }}
                            </td>

                            {{-- NAMA MK --}}
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $mk?->nama_mk ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $mk?->sks ?? 0 }} SKS • Semester {{ $mk?->semester ?? '-' }}
                                </p>
                            </td>

                            {{-- KOORDINATOR --}}
                            <td class="px-6 py-4">
                                @if($koor && $koor->user)
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-semibold mr-3">
                                            {{ strtoupper(substr($koor->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $koor->user->name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Skor: {{ number_format($koor->skor_dosen_di_mk, 3) }}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">Belum ditentukan</span>
                                @endif
                            </td>

                            {{-- PENGAMPU --}}
                            <td class="px-6 py-4">

                                {{-- JIKA FILTER → TAMPILKAN SEMUA --}}
                                @if($isFiltered)

                                    @forelse ($pengampu as $p)
                                        @if($p->user)
                                            <div class="flex items-center mb-1 last:mb-0">
                                                <div class="h-8 w-8 rounded-full bg-gray-400 text-white flex items-center justify-center text-xs font-semibold mr-3">
                                                    {{ strtoupper(substr($p->user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $p->user->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        Skor: {{ number_format($p->skor_dosen_di_mk, 3) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <span class="text-gray-500 text-sm">Belum ada pengampu</span>
                                    @endforelse

                                {{-- DEFAULT → HANYA 1 --}}
                                @else
                                    @php
                                        $pengampuUtama = $pengampu->first();
                                    @endphp

                                    @if($pengampuUtama && $pengampuUtama->user)
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gray-400 text-white flex items-center justify-center text-xs font-semibold mr-3">
                                                {{ strtoupper(substr($pengampuUtama->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $pengampuUtama->user->name }}
                                                </p>

                                                @if($pengampu->count() > 1)
                                                    <p class="text-xs text-gray-500">
                                                        +{{ $pengampu->count() - 1 }} pengampu lain
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-500 text-sm">Belum ada pengampu</span>
                                    @endif
                                @endif
                            </td>

                            {{-- SKOR --}}
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-lg bg-green-100 text-green-800 text-sm font-semibold">
                                    {{ number_format($skor, 3) }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-6 py-4 text-sm font-medium">
                                @if(Route::has('hasil.show'))
                                    <a href="{{ route('hasil.show', $hasil->id) }}"
                                       class="text-blue-700 hover:text-blue-900">
                                        Detail
                                    </a>
                                @else
                                    <span class="text-gray-400">Detail</span>
                                @endif
                            </td>
                        </tr>

                    @endforeach

                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            Data rekomendasi belum tersedia
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection


