@extends('layouts.app')

@section('title', 'Penelitian')
@section('page_title', 'Penelitian')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }">

    {{-- Header actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-lg sm:text-xl font-semibold text-gray-700">Daftar Penelitian</h1>
        <div class="flex flex-wrap gap-3">
            <button class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg shadow-sm text-gray-700 text-sm sm:text-base">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
            <button @click="openModal = true" class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-blue-800 hover:bg-blue-700 rounded-lg text-white shadow-sm text-sm sm:text-base">
                <i class="fa-solid fa-plus"></i> Tambah Penelitian
            </button>
        </div>
    </div>

    {{-- Modal Form Upload Penelitian --}}
    <div
        x-cloak
        x-show="openModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-start justify-center sm:items-center bg-black/60 z-50 p-4 min-h-screen"
        style="display: none;"
    >
        <div 
            @click.away="openModal = false" 
            class="bg-white shadow-lg p-5 sm:p-6 w-full max-w-md"
        >
            <x-penelitian />
        </div>
    </div>

    {{-- Statistik ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-blue-100 p-4 rounded border border-blue-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Total Penelitian</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-blue-700">2</p>
            <p class="text-gray-500 text-xs sm:text-sm">Karya penelitian</p>
        </div>

        <div class="bg-green-100 p-4 rounded border border-green-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Total Bonus</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-green-700">+1.0</p>
            <p class="text-gray-500 text-xs sm:text-sm">Nilai bonus penelitian</p>
        </div>

        <div class="bg-purple-100 p-4 rounded border border-pink-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Bidang Aktif</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-pink-700">5</p>
            <p class="text-gray-500 text-xs sm:text-sm">Bidang Penelitian</p>
        </div>
    </div>

    {{-- Daftar penelitian --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach ([
            [
                'judul' => 'Uji Penelitian', 
                'kategori' => 'AI & Pembelajaran Mesin', 
                'status' => 'Diterbitkan', 
                'tahun' => 2024, 
                'jurnal' => 'Jurnal Uji', 
                'mata_kuliah' => 'Pemrograman Dasar', 
                'bonus' => '+0.5'
            ],
            [
                'judul' => 'TESTET', 
                'kategori' => 'Jaringan', 
                'status' => 'Diterbitkan', 
                'tahun' => 2024, 
                'jurnal' => 'TESTSET', 
                'mata_kuliah' => 'Jaringan Komputer', 
                'bonus' => '+0.5'
            ]
        ] as $p)
        <div class="bg-white rounded-x border-gray-200 p-4 sm:p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2 text-sm sm:text-base">📘 {{ $p['judul'] }}</h3>
            </div>

            <span class="inline-block bg-pink-100 text-pink-700 text-xs sm:text-sm px-2 py-1 rounded-full mb-1">{{ $p['kategori'] }}</span>
            <span class="inline-block bg-green-100 text-green-700 text-xs sm:text-sm px-2 py-1 rounded-full mb-2">{{ $p['status'] }}</span>

            <div class="text-gray-500 text-xs sm:text-sm mb-3 space-y-1">
                <p><i class="fa-regular fa-calendar"></i> Tahun: {{ $p['tahun'] }}</p>
                <p><i class="fa-solid fa-book"></i> Jurnal: {{ $p['jurnal'] }}</p>
            </div>

            <div class="text-xs sm:text-sm text-gray-600 mb-2">
                <p class="font-semibold mb-1">Mata Kuliah Terkait:</p>
                <p class="bg-gray-200 rounded-md p-2">{{ $p['mata_kuliah'] }}</p>
            </div>

            <div class="flex justify-between items-center mt-2">
                <p class="text-green-600 text-sm sm:text-base font-semibold">{{ $p['bonus'] }} bonus penelitian</p>
                <span class="bg-green-100 text-green-700 text-xs sm:text-sm px-3 py-1 rounded-md font-medium">Bonus Penelitian</span>
            </div>
        </div>
        @endforeach
    </div>

</main>
@endsection
