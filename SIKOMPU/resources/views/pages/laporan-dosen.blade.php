@extends('layouts.app')

@section('title', 'Laporan')
@section('page_title', 'Laporan')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ 
    openModal: false, 
    selectedReport: 'rekap_pengampu', // Default selected report
    headerKop: true // Di set true agar checkbox tercentang by default sesuai gambar terbaru
}">

    {{-- KARTU KONTEN UTAMA --}}
    <section class="space-y-6">

        {{-- SECTION 1: PILIH JENIS LAPORAN (Tidak diubah dari perbaikan sebelumnya) --}}
        <div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-5">Pilih Jenis Laporan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Card 1: Rekap Final Pengampu --}}
                <label for="report-1" @click="selectedReport = 'rekap_pengampu'" 
                    class="flex items-start p-4 border rounded-xl cursor-pointer transition duration-150"
                    :class="selectedReport === 'rekap_pengampu' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white hover:bg-gray-50'">
                    <div class="p-3 bg-green-100 rounded-lg mr-4">
                        <i class="fa-solid fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="flex-grow">
                        <span class="font-semibold text-gray-900">Rekap Final Pengampu</span>
                        <p class="text-sm text-gray-600">Laporan lengkap penugasan dosen pengampu mata kuliah</p>
                    </div>
                    <input type="radio" id="report-1" name="report_type" value="rekap_pengampu" class="form-radio h-5 w-5 text-blue-600 mt-1 focus:ring-blue-500" :checked="selectedReport === 'rekap_pengampu'">
                </label>
                
                {{-- Card 2: Beban Mengajar Dosen --}}
                <label for="report-2" @click="selectedReport = 'beban_mengajar'" 
                    class="flex items-start p-4 border rounded-xl cursor-pointer transition duration-150"
                    :class="selectedReport === 'beban_mengajar' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white hover:bg-gray-50'">
                    <div class="p-3 bg-green-100 rounded-lg mr-4">
                        <i class="fa-solid fa-chart-bar text-green-600 text-xl"></i>
                    </div>
                    <div class="flex-grow">
                        <span class="font-semibold text-gray-900">Beban Mengajar Dosen</span>
                        <p class="text-sm text-gray-600">Laporan beban kerja dan distribusi mengajar dosen</p>
                    </div>
                    <input type="radio" id="report-2" name="report_type" value="beban_mengajar" class="form-radio h-5 w-5 text-green-600 mt-1 focus:ring-green-500" :checked="selectedReport === 'beban_mengajar'">
                </label>

                {{-- Card 3: Koordinator Program Studi --}}
                <label for="report-3" @click="selectedReport = 'koordinator_prodi'" 
                    class="flex items-start p-4 border rounded-xl cursor-pointer transition duration-150"
                    :class="selectedReport === 'koordinator_prodi' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white hover:bg-gray-50'">
                    <div class="p-3 bg-purple-100 rounded-lg mr-4">
                        <i class="fa-solid fa-user-tie text-purple-600 text-xl"></i>
                    </div>
                    <div class="flex-grow">
                        <span class="font-semibold text-gray-900">Koordinator Program Studi</span>
                        <p class="text-sm text-gray-600">Laporan penugasan koordinator per program studi</p>
                    </div>
                    <input type="radio" id="report-3" name="report_type" value="koordinator_prodi" class="form-radio h-5 w-5 text-purple-600 mt-1 focus:ring-purple-500" :checked="selectedReport === 'koordinator_prodi'">
                </label>
                
                {{-- Card 4: Laboran & Praktikum --}}
                <label for="report-4" @click="selectedReport = 'praktikum'" 
                    class="flex items-start p-4 border rounded-xl cursor-pointer transition duration-150"
                    :class="selectedReport === 'praktikum' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white hover:bg-gray-50'">
                    <div class="p-3 bg-green-100 rounded-lg mr-4">
                        <i class="fa-solid fa-flask text-orange-600 text-xl"></i>
                    </div>
                    <div class="flex-grow">
                        <span class="font-semibold text-gray-900">Laboran & Praktikum</span>
                        <p class="text-sm text-gray-600">Laporan penugasan laboran dan kegiatan praktikum</p>
                    </div>
                    <input type="radio" id="report-4" name="report_type" value="praktikum" class="form-radio h-5 w-5 text-orange-600 mt-1 focus:ring-orange-500" :checked="selectedReport === 'praktikum'">
                </label>
            </div>
        </div>

  {{-- SECTION 2: FILTER LAPORAN (Perbaikan) --}}
  <div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100 mt-6">
    <h2 class="text-xl font-bold text-gray-800 mb-5">Filter Laporan</h2>

    <div class="space-y-4">

        <div>
            <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
            <div class="relative mt-1">
                <select id="tahun_ajaran"
                    class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base py-2.5 px-3 pr-10 appearance-none transition">
                    <option>2024/2025</option>
                    <option>2023/2024</option>
                </select>
                <i class="fa-solid absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            </div>
        </div>

        <div>
            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
            <div class="relative mt-1">
                <select id="semester"
                    class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base py-2.5 px-3 pr-10 appearance-none transition">
                    <option>Ganjil</option>
                    <option>Genap</option>
                </select>
                <i class="fa-solid absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            </div>
        </div>

        <div>
            <label for="program_studi" class="block text-sm font-medium text-gray-700">Program Studi</label>
            <div class="relative mt-1">
                <select id="program_studi"
                    class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base py-2.5 px-3 pr-10 appearance-none transition">
                    <option>Semua Program Studi</option>
                    <option>Teknik Informatika</option>
                    <option>Teknik Geomatika</option>
                </select>
                <i class="fa-solid absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            </div>
        </div>

    </div>
</div>

{{-- SECTION 3: GENERATE LAPORAN (Perbaikan) --}}
<div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100 mt-6">
    <h2 class="text-xl font-bold text-gray-800 mb-5">Generate Laporan</h2>
    
    {{-- Deskripsi dan Checkbox Header Kop di baris yang berbeda --}}
    <div class="flex justify-between items-center mb-4">
        <p class="text-sm text-gray-600 font-medium">Pilih format laporan yang akan diunduh</p>
        
        {{-- Checkbox Header Kop INLINE --}}
        <label for="header_kop_checkbox" class="flex items-center space-x-1 text-sm text-gray-600 cursor-pointer">
            <input type="checkbox" id="header_kop_checkbox" x-model="headerKop" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <span>Header laporan menyertakan kop Politeknik Negeri Batam</span>
        </label>
    </div>

    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
        {{-- Tombol PDF --}}
        <button class="flex-1 px-6 py-3 bg-red-600 text-white font-bold rounded-xl shadow-md hover:bg-red-700 transition duration-150 flex items-center justify-center">
            <i class="fa-solid fa-file-pdf mr-2"></i> Generate & Download PDF
        </button>
        {{-- Tombol Excel --}}
        <button class="flex-1 px-6 py-3 bg-green-600 text-white font-bold rounded-xl shadow-md hover:bg-green-700 transition duration-150 flex items-center justify-center">
            <i class="fa-solid fa-file-excel mr-2"></i> Generate & Download Excel
        </button>
    </div>
    
    {{-- Waktu Proses Generate (Ikon disesuaikan dengan gambar) --}}
    <p class="text-xs text-gray-500 flex items-center pt-3">
        <i class="fa-solid fa-clock-rotate-left mr-2 text-gray-500"></i>
        Proses generate laporan membutuhkan waktu 10-30 detik tergantung jumlah data.
    </p>
</div>
</section>
</main>
@endsection
