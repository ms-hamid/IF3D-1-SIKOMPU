@extends('layouts.app')

@section('title', 'Sertifikasi')
@section('page_title', 'Sertifikasi')

@section('content')
<main 
    class="flex-1 p-4 sm:p-6 space-y-6" 
    x-data="{ openModal: false }" 
    x-effect="openModal ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')"
    @close-modal.window="openModal = false"
>

    {{-- Header actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-lg sm:text-xl font-semibold text-gray-700">Daftar Sertifikat</h1>
        <div class="flex flex-wrap gap-3">
            <button class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg shadow-sm text-gray-700 text-sm sm:text-base">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
            <button @click="openModal = true" class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-blue-800 hover:bg-blue-700 rounded-lg text-white shadow-sm text-sm sm:text-base">
                <i class="fa-solid fa-plus"></i> Tambah Sertifikat
            </button>
        </div>
    </div>

    {{-- Modal Form Upload Sertifikasi --}}
    <div
        x-cloak
        x-show="openModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-start justify-center sm:items-center bg-black/60 z-80 p-4 min-h-screen"
        style="display: none;"
    >
        <div 
            @click.away="openModal = false" 
            class="bg-white shadow-lg p-5 sm:p-6 w-full max-w-md "
        >
            <x-sertifikat />
        </div>
    </div>

    {{-- Statistik ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-blue-100 p-4 rounded border border-blue-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Total Sertifikat</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-blue-700">3</p>
            <p class="text-gray-500 text-xs sm:text-sm">Sertifikat terdaftar</p>
        </div>

        <div class="bg-green-100 p-4 rounded border border-green-300  text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Total Bonus</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-green-700">+11.5</p>
            <p class="text-gray-500 text-xs sm:text-sm">Nilai bonus kumulatif</p>
        </div>

        <div class="bg-purple-100 p-4 rounded border border-pink-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Kategori Aktif</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-pink-700">5</p>
            <p class="text-gray-500 text-xs sm:text-sm">Bidang kompetensi</p>
        </div>
    </div>

    {{-- Daftar sertifikat --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach ([
            ['nama' => 'Sertifikat Uji', 'rating' => '★★★★☆', 'kategori' => 'Rekayasa Perangkat Lunak', 'lembaga' => 'Lembaga Ujian', 'tahun' => 2024, 'mata_kuliah' => 'Pemrograman Dasar', 'bonus' => '+3.5'],
            ['nama' => 'Data Analytics', 'rating' => '★★★★★', 'kategori' => 'Analisis Data', 'lembaga' => 'Google', 'tahun' => 2025, 'mata_kuliah' => 'Basis Data & Statistik', 'bonus' => '+5'],
            ['nama' => 'AWS Certified', 'rating' => '★★★★☆', 'kategori' => 'Pengembangan Web', 'lembaga' => 'Google', 'tahun' => 2025, 'mata_kuliah' => 'Pengembangan Web', 'bonus' => '+3.5'],
        ] as $s)
        <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2 text-sm sm:text-base">🏅 {{ $s['nama'] }}</h3>
                <div class="text-yellow-500 text-xs sm:text-sm flex gap-1">{{ $s['rating'] }}</div>
            </div>
            <span class="inline-block bg-gray-100 text-gray-700 text-xs sm:text-sm px-2 py-1 rounded-full mb-2">{{ $s['kategori'] }}</span>
            <div class="text-gray-500 text-xs sm:text-sm mb-3 space-y-1">
                <p><i class="fa-regular fa-building"></i> {{ $s['lembaga'] }}</p>
                <p><i class="fa-regular fa-calendar"></i> Tahun: {{ $s['tahun'] }}</p>
            </div>
            <div class="text-xs sm:text-sm text-gray-600 mb-2">
                <p class="font-semibold mb-1">Mata Kuliah Terkait:</p>
                <p class="bg-gray-200  rounded-md p-2">{{ $s['mata_kuliah'] }}</p>
            </div>
            <p class="text-green-600 text-sm sm:text-base font-semibold mt-2">{{ $s['bonus'] }} bonus</p>
        </div>
        @endforeach
    </div>

</main>
@endsection
