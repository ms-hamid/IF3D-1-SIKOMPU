@extends('layouts.app')

@section('title', 'Sertifikasi')
@section('page_title', 'Sertifikasi')

@section('content')
<main class="flex-1 p-6 space-y-8" x-data="{ openModal: false }" @close-modal.window="openModal = false">

    {{-- Header actions --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-700">Daftar Sertifikat</h1>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg shadow-sm text-gray-700">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
            <button @click="openModal = true" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white shadow-sm">
                <i class="fa-solid fa-plus"></i> Tambah Sertifikat
            </button>
        </div>
    </div>

    {{-- Modal Form Upload Sertifikat --}}
    <div
        x-cloak
        x-show="openModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div @click.away="openModal = false" class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md">
            {{-- Panggil component form --}}
            <x-sertifikat />
        </div>
    </div>

    {{-- Statistik ringkas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 p-4 rounded-xl shadow-sm text-center">
            <h3 class="text-sm text-gray-500">Total Sertifikat</h3>
            <p class="text-3xl font-semibold text-blue-700">3</p>
            <p class="text-gray-500 text-sm">Sertifikat terdaftar</p>
        </div>

        <div class="bg-green-50 p-4 rounded-xl shadow-sm text-center">
            <h3 class="text-sm text-gray-500">Total Bonus</h3>
            <p class="text-3xl font-semibold text-green-700">+11.5</p>
            <p class="text-gray-500 text-sm">Nilai bonus kumulatif</p>
        </div>

        <div class="bg-yellow-50 p-4 rounded-xl shadow-sm text-center">
            <h3 class="text-sm text-gray-500">Kategori Aktif</h3>
            <p class="text-3xl font-semibold text-yellow-700">5</p>
            <p class="text-gray-500 text-sm">Bidang kompetensi</p>
        </div>
    </div>

    {{-- Daftar sertifikat --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Sertifikat 1 --}}
        <div class="bg-white rounded-x border p-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">🏅 Sertifikat Uji</h3>
                <div class="text-yellow-500 text-sm flex gap-1">★★★★☆</div>
            </div>
            <span class="inline-block bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full mb-2">Rekayasa Perangkat Lunak</span>
            <div class="text-gray-500 text-sm mb-3">
                <p><i class="fa-regular fa-building"></i> Lembaga Ujian</p>
                <p><i class="fa-regular fa-calendar"></i> Tahun: 2024</p>
            </div>
            <div class="text-xs text-gray-600">
                <p class="font-semibold mb-1">Mata Kuliah Terkait:</p>
                <p class="bg-gray-50 border rounded-md p-2">Pemrograman Dasar</p>
            </div>
            <p class="text-green-600 text-sm font-semibold mt-3">+3.5 bonus</p>
        </div>

        {{-- Sertifikat 2 --}}
        <div class="bg-white rounded-x border p-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">🏅 Data Analytics</h3>
                <div class="text-yellow-500 text-sm flex gap-1">★★★★★</div>
            </div>
            <span class="inline-block bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full mb-2">Analisis Data</span>
            <div class="text-gray-500 text-sm mb-3">
                <p><i class="fa-brands fa-google"></i> Google</p>
                <p><i class="fa-regular fa-calendar"></i> Tahun: 2025</p>
            </div>
            <div class="text-xs text-gray-600">
                <p class="font-semibold mb-1">Mata Kuliah Terkait:</p>
                <p class="bg-gray-50 border rounded-md p-2">Basis Data & Statistik</p>
            </div>
            <p class="text-green-600 text-sm font-semibold mt-3">+5 bonus</p>
        </div>

        {{-- Sertifikat 3 --}}
        <div class="bg-white rounded-x border p-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">🏅 AWS Certified</h3>
                <div class="text-yellow-500 text-sm flex gap-1">★★★★☆</div>
            </div>
            <span class="inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full mb-2">Pengembangan Web</span>
            <div class="text-gray-500 text-sm mb-3">
                <p><i class="fa-brands fa-google"></i> Google</p>
                <p><i class="fa-regular fa-calendar"></i> Tahun: 2025</p>
            </div>
            <div class="text-xs text-gray-600">
                <p class="font-semibold mb-1">Mata Kuliah Terkait:</p>
                <p class="bg-gray-50 border rounded-md p-2">Pengembangan Web</p>
            </div>
            <p class="text-green-600 text-sm font-semibold mt-3">+3.5 bonus</p>
        </div>

    </div>
</main>
@endsection
