@extends('layouts.app')

@section('title', 'Laporan')
@section('page_title', 'Laporan')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }" @close-modal.window="openModal = false">
{{-- KARTU KONTEN UTAMA --}}
    <section class="space-y-6">
    {{-- SECTION 1: PILIH JENIS LAPORAN (Sesuai Screenshot) --}}
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
    <section class="space-y-6">
        <h2 class="text-xl font-bold text-gray-800 border-b pb-3">Pilih Jenis Laporan</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            {{-- Card 1: Rekap Final Pengampu --}}
            <label for="report-1" class="flex items-start p-4 border border-gray-200 rounded-xl cursor-pointer bg-white hover:bg-blue-50 transition duration-150 shadow-md">
                <div class="flex-grow">
                    <div class="flex items-center space-x-4 mb-2">
                        {{-- Ikon lebih menonjol --}}
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fa-solid fa-users text-blue-600 text-xl"></i>
                        </div>
                        <span class="font-bold text-gray-900 text-lg">Rekap Final Pengampu</span>
                    </div>
                    <p class="text-sm text-gray-600 pl-16">Laporan lengkap penugasan dosen pengampu mata kuliah.</p>
                </div>
                <input type="radio" id="report-1" name="report_type" value="rekap_pengampu" class="form-radio h-5 w-5 text-blue-600 mt-1 focus:ring-blue-500" checked>
            </label>
            
            {{-- Card 2: Beban Mengajar Dosen --}}
            <label for="report-2" class="flex items-start p-4 border border-gray-200 rounded-xl cursor-pointer bg-white hover:bg-blue-50 transition duration-150 shadow-md">
                <div class="flex-grow">
                    <div class="flex items-center space-x-4 mb-2">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fa-solid fa-chart-bar text-green-600 text-xl"></i>
                        </div>
                        <span class="font-bold text-gray-900 text-lg">Beban Mengajar Dosen</span>
                    </div>
                    <p class="text-sm text-gray-600 pl-16">Laporan beban kerja dan distribusi mengajar dosen.</p>
                </div>
                <input type="radio" id="report-2" name="report_type" value="beban_mengajar" class="form-radio h-5 w-5 text-blue-600 mt-1 focus:ring-green-500">
            </label>

            {{-- Card 3: Koordinator Program Studi --}}
            <label for="report-3" class="flex items-start p-4 border border-gray-200 rounded-xl cursor-pointer bg-white hover:bg-blue-50 transition duration-150 shadow-md">
                <div class="flex-grow">
                    <div class="flex items-center space-x-4 mb-2">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fa-solid fa-user-tie text-purple-600 text-xl"></i>
                        </div>
                        <span class="font-bold text-gray-900 text-lg">Koordinator Program Studi</span>
                    </div>
                    <p class="text-sm text-gray-600 pl-16">Laporan penugasan koordinator per program studi.</p>
                </div>
                <input type="radio" id="report-3" name="report_type" value="koordinator_prodi" class="form-radio h-5 w-5 text-blue-600 mt-1 focus:ring-purple-500">
            </label>
            
            {{-- Card 4: Laporan Laboratorium & Praktikum --}}
            <label for="report-4" class="flex items-start p-4 border border-gray-200 rounded-xl cursor-pointer bg-white hover:bg-blue-50 transition duration-150 shadow-md">
                <div class="flex-grow">
                    <div class="flex items-center space-x-4 mb-2">
                        <div class="p-3 bg-orange-100 rounded-full">
                            <i class="fa-solid fa-flask text-orange-600 text-xl"></i>
                        </div>
                        <span class="font-bold text-gray-900 text-lg">Laboran & Praktikum</span>
                    </div>
                    <p class="text-sm text-gray-600 pl-16">Laporan penugasan laboran dan kegiatan praktikum.</p>
                </div>
                <input type="radio" id="report-4" name="report_type" value="praktikum" class="form-radio h-5 w-5 text-blue-600 mt-1 focus:ring-orange-500">
            </label>
        </div>
    </section>
</div>
    {{-- SECTION 2: FILTER LAPORAN --}}
    <div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100 mt-6">
        <section class="space-y-6">
            <h2 class="text-xl font-bold text-gray-800 border-b pb-3">Filter Laporan</h2>
    
            <!-- Grid 3 kolom rapi -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
                <!-- Tahun Ajaran -->
                <div>
                    <label for="tahun_ajaran" class="block text-sm font-semibold text-gray-700 mb-1">Tahun Ajaran</label>
                    <div class="relative">
                        <select id="tahun_ajaran"
                            class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base py-2.5 px-3 pr-10 appearance-none transition">
                            <option>2024/2025</option>
                            <option>2023/2024</option>
                            <option>2022/2023</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                    </div>
                </div>
    
                <!-- Semester -->
                <div>
                    <label for="semester" class="block text-sm font-semibold text-gray-700 mb-1">Semester</label>
                    <div class="relative">
                        <select id="semester"
                            class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base py-2.5 px-3 pr-10 appearance-none transition">
                            <option>Ganjil</option>
                            <option>Genap</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                    </div>
                </div>
    
                <!-- Program Studi -->
                <div>
                    <label for="program_studi" class="block text-sm font-semibold text-gray-700 mb-1">Program Studi</label>
                    <div class="relative">
                        <select id="program_studi"
                            class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base py-2.5 px-3 pr-10 appearance-none transition">
                            <option>Semua Program Studi</option>
                            <option>Teknik Informatika</option>
                            <option>Sistem Informasi</option>
                            <option>Akuntansi Manajerial</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                    </div>
                </div>
    
            </div>
        </section>
    </div>
     {{-- SECTION 3: GENERATE LAPORAN --}}
     <div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100 mt-6">
        <h2 class="text-xl font-bold text-gray-800 border-b pb-3">Generate Laporan</h2>
        
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <i class="fa-solid fa-circle-info text-blue-500"></i>
            <p>Pilih format laporan yang akan diunduh</p>
        </div>
        
        <div class="flex items-center space-x-2 text-sm text-gray-700">
            <input type="checkbox" id="header_kop" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <label for="header_kop">Header laporan menyertakan kop Politeknik Negeri Batam</label>
        </div>

        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 pt-3">
            <button class="flex-1 px-6 py-3 bg-red-600 text-white font-bold rounded-xl shadow-lg hover:bg-red-700 transition duration-150 flex items-center justify-center">
                <i class="fa-solid fa-file-pdf mr-2"></i> Generate & Download PDF
            </button>
            <button class="flex-1 px-6 py-3 bg-green-600 text-white font-bold rounded-xl shadow-lg hover:bg-green-700 transition duration-150 flex items-center justify-center">
                <i class="fa-solid fa-file-excel mr-2"></i> Generate & Download Excel
            </button>
        </div>
        
        <p class="text-xs text-gray-500 flex items-center pt-2">
            <i class="fa-solid fa-hourglass-half mr-2 text-yellow-500"></i>
            Proses generate laporan membutuhkan waktu 10-30 detik tergantung jumlah data.
        </p>
    </section>
    
</div>


</main>
@endsection
