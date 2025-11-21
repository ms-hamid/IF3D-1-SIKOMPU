@extends('layouts.app')

@section('title', 'Sertifikasi')
@section('page_title', 'Sertifikasi')

@section('content')
{{-- Variabel Alpine.js untuk kontrol modal --}}
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openSertifikatModal: false }"> 
    
    {{-- Header dan Tombol Aksi --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-lg sm:text-xl font-semibold text-gray-700">Daftar Sertifikasi</h1>
        <div class="flex flex-wrap gap-2">
            {{-- Tombol Refresh --}}
            {{-- Menggunakan event @click untuk me-refresh halaman (cara sederhana) --}}
            <button onclick="window.location.reload();" 
                    class="flex items-center gap-2 border border-gray-300 px-4 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition whitespace-nowrap">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
            
            <button @click="openSertifikatModal = true" 
                    class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm whitespace-nowrap shadow-md">
                <i class="fa-solid fa-plus"></i> Tambah Sertifikat
            </button>
            
        
        </div>
    </div>
    
    
    {{-- Statistik ringkas (Sesuai tampilan terbaru) --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
        
        {{-- Total Penelitian (Biru) --}}
        <div class="p-6 rounded-xl shadow-md flex flex-col justify-center items-center space-y-1" style="background-color: #f0f4ff; border: 1px solid #d4e0ff;">
            <h3 class="text-base font-semibold text-blue-700">Total Penelitian</h3>
            <p class="text-3xl font-bold text-blue-900">2</p>
            <p class="text-sm text-blue-600">Karya penelitian</p>
        </div>

        {{-- Total Bonus (Hijau) --}}
        <div class="p-6 rounded-xl shadow-md flex flex-col justify-center items-center space-y-1" style="background-color: #f0fff4; border: 1px solid #d4ffd4;">
            <h3 class="text-base font-semibold text-green-700">Total Bonus</h3>
            <p class="text-3xl font-bold text-green-900">+1.0</p>
            <p class="text-sm text-green-600">Nilai bonus penelitian</p>
        </div>

        {{-- Bidang Aktif (Ungu/Pink) --}}
        <div class="p-6 rounded-xl shadow-md flex flex-col justify-center items-center space-y-1" style="background-color: #fff0ff; border: 1px solid #ffd4ff;">
            <h3 class="text-base font-semibold text-purple-700">Bidang Aktif</h3>
            <p class="text-3xl font-bold text-purple-900">5</p>
            <p class="text-sm text-purple-600">Bidang Penelitian</p>
        </div>
    </div>
    
    <hr class="border-gray-200">

    {{-- Daftar Sertifikat (Card Grid) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- Card Sertifikat 1: Sertifikat Uji (MEDALI DIPERBARUI) --}}
<div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 space-y-3">
    <div class="flex justify-between items-start">
        <div class="flex items-center gap-3">
            {{-- Ikon Medali diperbarui untuk lebih menonjol --}}
            <i class="fa-solid fa-medal text-xl text-yellow-600 drop-shadow-sm"></i>
            <h4 class="font-bold text-lg text-gray-800">Sertifikat Uji</h4>
        </div>
        <div class="text-yellow-500 text-sm flex items-center gap-1">
            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
            <span class="text-green-600 font-semibold ml-2">+3.5 bonus</span>
        </div>
    </div>
    
    <span class="inline-block bg-red-100 text-red-700 text-xs font-medium px-2.5 py-0.5 rounded-full">Rekayasa Perangkat Lunak</span>
    
    <div class="text-sm text-gray-600 space-y-1">
        <p><i class="fa-solid fa-building text-gray-400 w-4"></i> Lembaga Ujian</p>
        <p><i class="fa-solid fa-calendar text-gray-400 w-4"></i> Tahun: 2024</p>
    </div>
    
    <div class="mt-3 pt-3 border-t border-gray-100">
        <p class="text-sm font-semibold text-gray-700 mb-1">Mata Kuliah Terkait:</p>
        <p class="text-sm text-gray-500">Pemrograman Dasar</p>
    </div>
</div>

        {{-- Card Sertifikat 2: Data Analytics --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 space-y-3">
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-medal text-xl text-yellow-600"></i>
                    <h4 class="font-bold text-lg text-gray-800">Data Analytics</h4>
                </div>
                <div class="text-yellow-500 text-sm flex items-center gap-1">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <span class="text-green-600 font-semibold ml-2">+5 bonus</span>
                </div>
            </div>
            
            <span class="inline-block bg-blue-100 text-blue-700 text-xs font-medium px-2.5 py-0.5 rounded-full">Analisis Data</span>
            
            <div class="text-sm text-gray-600 space-y-1">
                <p><i class="fa-solid fa-building text-gray-400 w-4"></i> Google</p>
                <p><i class="fa-solid fa-calendar text-gray-400 w-4"></i> Tahun: 2025</p>
            </div>
            
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-sm font-semibold text-gray-700 mb-1">Mata Kuliah Terkait:</p>
                <p class="text-sm text-gray-500">Basis Data & Statistik</p>
            </div>
        </div>
        
        {{-- Card Sertifikat 3: AWS Certified --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 space-y-3">
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-medal text-xl text-yellow-600"></i>
                    <h4 class="font-bold text-lg text-gray-800">AWS Certified</h4>
                </div>
                <div class="text-yellow-500 text-sm flex items-center gap-1">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                    <span class="text-green-600 font-semibold ml-2">+3.5 bonus</span>
                </div>
            </div>
            
            <span class="inline-block bg-green-100 text-green-700 text-xs font-medium px-2.5 py-0.5 rounded-full">Pengembangan Web</span>
            
            <div class="text-sm text-gray-600 space-y-1">
                <p><i class="fa-solid fa-building text-gray-400 w-4"></i> Google</p>
                <p><i class="fa-solid fa-calendar text-gray-400 w-4"></i> Tahun: 2025</p>
            </div>
            
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-sm font-semibold text-gray-700 mb-1">Mata Kuliah Terkait:</p>
                <p class="text-sm text-gray-500">Pengembangan Web</p>
            </div>
        </div>

    </div>

{{-- Include Form Tambah Dosen --}}
@include('components.sertifikat')
</main>
@endsection