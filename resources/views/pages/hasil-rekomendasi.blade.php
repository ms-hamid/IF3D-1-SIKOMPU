@extends('layouts.app')

@section('title', 'Hasil Rekomendasi')
@section('page_title', 'Hasil Rekomendasi')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }" @close-modal.window="openModal = false">

{{-- 1. HEADER JUDUL & TOMBOL EKSPOR --}}
<div class="flex flex-col sm:flex-row justify-between sm:items-center border-b border-gray-200 pb-4 mb-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Hasil Rekomendasi</h2>
        <p class="text-sm font-normal text-gray-500 mt-1">Semester Ganjil 2025/2026 - Politeknik Negeri Batam</p>
    </div>
    
    {{-- Tombol Aksi Ekspor (Diposisikan di kanan atas) --}}
    <div class="flex space-x-3 mt-4 sm:mt-0">
        <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600  border-red-600 rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Ekspor PDF
        </button>
        <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-150 shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Ekspor Excel
        </button>
    </div>
</div>

{{-- 2. RINGKASAN DATA (CARDS) --}}
@php
$cards = [
   ['title' => 'Total Mata Kuliah', 'value' => 48, 'icon_bg' => 'bg-blue-100', 'icon_text' => 'text-blue-600', 'icon_svg' => '<path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>'],
   ['title' => 'Koordinator Ditetapkan', 'value' => 48, 'icon_bg' => 'bg-green-100', 'icon_text' => 'text-green-600', 'icon_svg' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>'],
   ['title' => 'Pengampu Ditetapkan', 'value' => 156, 'icon_bg' => 'bg-purple-100', 'icon_text' => 'text-purple-600', 'icon_svg' => '<path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>'],
   ['title' => 'Skor Rata-rata', 'value' => '8.7', 'icon_bg' => 'bg-yellow-100', 'icon_text' => 'text-yellow-700', 'icon_svg' => '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.817 2.05a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.817-2.05a1 1 0 00-1.175 0l-2.817 2.05c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" fill="currentColor"></path>'],
];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
@foreach ($cards as $card)
<div class="bg-white p-5 rounded-xl shadow-lg flex items-center justify-between border border-gray-100">
   <div>
       <p class="text-sm font-medium text-gray-500">{{ $card['title'] }}</p>
       <p class="text-3xl font-bold text-gray-900 mt-1">{{ $card['value'] }}</p>
   </div>
   <div class="p-3 rounded-xl {{ $card['icon_bg'] }} {{ $card['icon_text'] }}"> {{-- Ganti rounded-full jadi rounded-xl (lebih kotak) --}}
       <svg class="w-6 h-6" fill="{{ $card['icon_bg'] == 'bg-yellow-100' ? 'currentColor' : 'none' }}" stroke="{{ $card['icon_bg'] == 'bg-yellow-100' ? 'none' : 'currentColor' }}" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            @if ($card['icon_bg'] != 'bg-yellow-100') <g stroke="currentColor"> @endif
            {!! $card['icon_svg'] !!}
            @if ($card['icon_bg'] != 'bg-yellow-100') </g> @endif
       </svg>
   </div>
</div>
@endforeach
</div>

{{-- --- Section: Filter dan Tabel Data --- --}}

{{-- 3. FILTER & PENCARIAN --}}
<div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100 mt-6">
<h4 class="text-xl font-bold text-gray-800 mb-4">Filter & Pencarian</h4>
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
   {{-- Input Pencarian --}}
   <div class="md:col-span-2 relative w-full">
       <input type="text" placeholder="Cari mata kuliah atau koordinator..." class="w-full pl-4 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
   </div>

   {{-- Dropdown Program Studi --}}
   <select class="w-full border border-gray-300 rounded-lg py-2 px-4 focus:ring-blue-500 focus:border-blue-500 text-gray-700">
    <option value="" disabled selected>Semua Program Studi</option>
    <option>Teknik Informatika</option>
    <option>Teknik Geomatika</option>
    <option>Teknik Rekayasa Multimedia</option>
    <option>Animasi</option>
    <option>Rekayasa Keamanan Siber</option>
    <option>Teknik Rekayasa Perangkat Lunak</option>
    <option>Teknologi Permainan</option>
    <option>S2 Magister Terapan Teknik Komputer</option>


    {{-- ... Opsi lainnya ... --}}
   </select>

   {{-- Dropdown Semester --}}
   <select class="w-full border border-gray-300 rounded-lg py-2 px-4 focus:ring-blue-500 focus:border-blue-500 text-gray-700">
       <option>Semua Semester</option>
       <option>Ganjil 2025/2026</option>
       <option>Genap 2024/2025</option>
   </select>

   {{-- Tombol Terapkan Filter --}}
   <button class="w-full flex items-center justify-center px-4 py-2 text-white bg-blue-700 rounded-lg hover:bg-blue-800 transition duration-150 whitespace-nowrap shadow-md">
       <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
       Terapkan Filter
   </button>
</div>
</div>
    {{-- 4.Tabel Data Rekomendasi --}}
    <div class="bg-white p-6 shadow-lg rounded-lg border border-gray-100">
        <h4 class="text-xl font-bold text-gray-800 mb-4">Rekomendasi Koordinator & Pengampu</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode MK
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mata Kuliah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Koordinator
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pengampu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Skor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $recommendations = [
                            ['kode' => 'TIF101', 'mk' => 'Algoritma dan Pemrograman', 'sks' => '3 SKS • Semester 1', 'koordinator' => 'Dr. Retno Sari', 'koor_skor' => '9.2', 'koor_initial' => 'DR', 'pengampu' => ['Abdul Rahman, M.Kom', 'Siti Maryam, S.Kom'], 'skor' => '8.9', 'pengampu_initials' => ['AR', 'SM']],
                            ['kode' => 'TIF102', 'mk' => 'Matematika Diskrit', 'sks' => '3 SKS • Semester 1', 'koordinator' => 'Prof. Ahmad Hasan', 'koor_skor' => '9.5', 'koor_initial' => 'AH', 'pengampu' => ['Linda Nurhasanah, M.T', 'Riko Kurniawan, S.Kom'], 'skor' => '9.1', 'pengampu_initials' => ['LN', 'RK']],
                            ['kode' => 'TIF201', 'mk' => 'Struktur Data', 'sks' => '3 SKS • Semester 3', 'koordinator' => 'Dr. Dian Pratiwi', 'koor_skor' => '8.8', 'koor_initial' => 'DP', 'pengampu' => ['Farid Hidayat, M.Kom', 'Andi Nugroho, S.T'], 'skor' => '8.3', 'pengampu_initials' => ['FH', 'AN']],
                            ['kode' => 'TIF301', 'mk' => 'Basis Data', 'sks' => '3 SKS • Semester 3', 'koordinator' => 'Muhammad Wijaya, Ph.D', 'koor_skor' => '9.0', 'koor_initial' => 'MW', 'pengampu' => ['Eka Sari, M.T', 'Budi Purnomo, S.Kom'], 'skor' => '8.7', 'pengampu_initials' => ['ES', 'BP']],
                            ['kode' => 'TIF401', 'mk' => 'Rekayasa Perangkat Lunak', 'sks' => '3 SKS • Semester 5', 'koordinator' => 'Dr. Sari Ayu', 'koor_skor' => '9.3', 'koor_initial' => 'SA', 'pengampu' => ['Taufik Hidayat, M.Kom', 'Nita Fitriani, S.T'], 'skor' => '9.0', 'pengampu_initials' => ['TH', 'NF']],
                        ];
                    @endphp

                    @foreach ($recommendations as $data)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data['kode'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-medium text-gray-900">{{ $data['mk'] }}</p>
                            <p class="text-xs text-gray-500">{{ $data['sks'] }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-semibold mr-3">
                                    {{ $data['koor_initial'] }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $data['koordinator'] }}</p>
                                    <p class="text-xs text-gray-500">Skor: {{ $data['koor_skor'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @foreach ($data['pengampu'] as $p_index => $pengampu)
                                <div class="flex items-center mb-1 last:mb-0">
                                    <div class="h-8 w-8 rounded-full bg-gray-400 text-white flex items-center justify-center text-xs font-semibold mr-3">
                                        {{ $data['pengampu_initials'][$p_index] }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $pengampu }}</span>
                                </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-lg bg-green-100 text-green-800">
                                {{ $data['skor'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-blue-700 hover:text-blue-900">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <span>Menampilkan 1-5 dari 48 mata kuliah</span>
            <div class="flex space-x-1">
                <button class="px-3 py-1 border border-gray-300 rounded-md text-gray-500 hover:bg-gray-100 text-xs">Previous</button>
                <button class="px-3 py-1 border border-blue-700 bg-blue-700 text-white rounded-md font-semibold text-xs">1</button>
                <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100 text-xs">2</button>
                <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100 text-xs">3</button>
                <button class="px-3 py-1 border border-gray-300 rounded-md text-gray-500 hover:bg-gray-100 text-xs">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection
