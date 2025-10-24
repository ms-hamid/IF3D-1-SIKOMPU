@extends('layouts.app')

@section('title', 'Penelitian')
@section('page_title', 'Penelitian')

@section('content')

<div class="container mx-auto px-6 py-8">

    {{-- Header actions --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-700">Daftar Penelitian</h1>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg shadow-sm text-gray-700">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
            <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white shadow-sm">
                <i class="fa-solid fa-plus"></i> Tambah Penelitian
            </button>
        </div>
    </div>

    {{-- Statistik ringkas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 p-4 rounded-xl shadow-sm text-center">
            <h3 class="text-sm text-gray-500">Total Penelitian</h3>
            <p class="text-3xl font-semibold text-blue-700">2</p>
            <p class="text-gray-500 text-sm">Karya penelitian</p>
        </div>

        <div class="bg-green-50 p-4 rounded-xl shadow-sm text-center">
            <h3 class="text-sm text-gray-500">Total Bonus</h3>
            <p class="text-3xl font-semibold text-green-700">+1.0</p>
            <p class="text-gray-500 text-sm">Nilai bonus penelitian</p>
        </div>

        <div class="bg-purple-50 p-4 rounded-xl shadow-sm text-center">
            <h3 class="text-sm text-gray-500">Bidang Aktif</h3>
            <p class="text-3xl font-semibold text-purple-700">5</p>
            <p class="text-gray-500 text-sm">Bidang Penelitian</p>
        </div>
    </div>

    {{-- Daftar penelitian --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Penelitian 1 --}}
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    📘 Uji Penelitian
                </h3>
            </div>

            <span class="inline-block bg-pink-100 text-pink-700 text-xs px-3 py-1 rounded-full mb-2">AI & Pembelajaran Mesin</span>
            <span class="inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full mb-2">Diterbitkan</span>

            <div class="text-gray-500 text-sm mb-3">
                <p><i class="fa-regular fa-calendar"></i> Tahun: 2024</p>
                <p><i class="fa-solid fa-book"></i> Jurnal: Jurnal Uji</p>
            </div>

            <div class="text-xs text-gray-600">
                <p class="font-semibold mb-1">Mata Kuliah Terkait:</p>
                <p class="bg-gray-50 border rounded-md p-2">Pemrograman Dasar</p>
            </div>

            <div class="flex justify-between items-center mt-3">
                <p class="text-green-600 text-sm font-semibold">+0.5 bonus penelitian</p>
                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-md font-medium">Bonus Penelitian</span>
            </div>
        </div>

        {{-- Penelitian 2 --}}
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    📘 TESTET
                </h3>
            </div>

            <span class="inline-block bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full mb-2">Jaringan</span>
            <span class="inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full mb-2">Diterbitkan</span>

            <div class="text-gray-500 text-sm mb-3">
                <p><i class="fa-regular fa-calendar"></i> Tahun: 2024</p>
                <p><i class="fa-solid fa-book"></i> Jurnal: TESTSET</p>
            </div>

            <div class="text-xs text-gray-600">
                <p class="font-semibold mb-1">Mata Kuliah Terkait:</p>
                <p class="bg-gray-50 border rounded-md p-2">Jaringan Komputer</p>
            </div>

            <div class="flex justify-between items-center mt-3">
                <p class="text-green-600 text-sm font-semibold">+0.5 bonus penelitian</p>
                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-md font-medium">Bonus Penelitian</span>
            </div>
        </div>

    </div>
</div>


@endsection