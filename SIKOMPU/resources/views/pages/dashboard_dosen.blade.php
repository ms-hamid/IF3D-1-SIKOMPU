@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<main class="flex-1 px-3 sm:px-6 py-4 space-y-6">

  {{-- Banner --}}
  <x-dashboard.banner />

  {{-- Greeting --}}
  <section class="border-b border-gray-300 pb-3">
    <div class="flex items-center space-x-3">
      <div class="bg-green-100 p-2 rounded-lg">
        <i class="fa-solid fa-chart-line text-green-600"></i>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-gray-800">
          Selamat Datang, Dr. Mega Sari
        </h3>
        <p class="text-gray-500 text-sm flex items-center mt-1">
          <i class="fa-regular fa-calendar text-gray-400 mr-1"></i>
          Minggu, 28 September 2025
        </p>
      </div>
    </div>
  </section>

  {{-- Card Data Diri --}}
  <x-dashboard.profile-card />

  {{-- Aktivitas Terbaru --}}
  <section class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm flex flex-col lg:flex-row gap-6">

    {{-- Diagram Self-Assessment --}}
    <div class="flex flex-col items-center justify-center flex-1 
                border-b lg:border-b-0 lg:border-r border-gray-200 
                pb-6 lg:pb-0 lg:pr-6 text-center">

      <h4 class="font-semibold text-gray-700 mb-4 text-base sm:text-lg">
        Rata-Rata Dosen Self-Assessment
      </h4>

      {{-- SVG Diagram yang responsif --}}
      <div class="relative w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40">
        <svg viewBox="0 0 160 160" class="w-full h-full">
          <!-- Latar belakang lingkaran -->
          <circle cx="80" cy="80" r="70" stroke="#e5e7eb" stroke-width="10" fill="none" />
          <!-- Progress lingkaran -->
          <circle 
            cx="80" cy="80" r="70" 
            stroke="#2563eb" stroke-width="10" fill="none"
            stroke-dasharray="440" stroke-dashoffset="88"
            stroke-linecap="round" 
            transform="rotate(-90 80 80)" />
        </svg>

        <div class="absolute inset-0 flex items-center justify-center">
          <span class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800">80%</span>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row justify-center items-center mt-3 
                  text-xs sm:text-sm text-gray-500 gap-1 sm:gap-4">
        <span class="flex items-center">
          <span class="w-3 h-3 bg-blue-600 rounded-full mr-1"></span> Sudah Mengisi
        </span>
        <span class="flex items-center">
          <span class="w-3 h-3 bg-gray-300 rounded-full mr-1"></span> Belum Mengisi
        </span>
      </div>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="flex-1">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 text-center sm:text-left">
        <h4 class="text-base sm:text-lg font-semibold text-gray-800">Aktivitas Terbaru Anda</h4>
        <a href="#" class="text-blue-600 text-sm hover:underline mt-1 sm:mt-0">Lihat semua</a>
      </div>

      <ul class="space-y-4">
        <li class="relative border-l-4 border-blue-500 bg-blue-50/60 rounded-xl p-4 hover:shadow-md transition-all duration-200 ease-in-out">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
            <h5 class="font-semibold text-gray-800">Perbarui Self-Assessment</h5>
            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-md font-medium mt-1 sm:mt-0">Selesai</span>
          </div>
          <p class="text-sm text-gray-600 mt-1">Mata kuliah Algoritma dan Pemrograman</p>
          <p class="text-xs text-gray-400 mt-2 text-right">2 jam yang lalu</p>
        </li>

        <li class="relative border-l-4 border-green-500 bg-green-50/60 rounded-xl p-4 hover:shadow-md transition-all duration-200 ease-in-out">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
            <h5 class="font-semibold text-gray-800">Unggah Sertifikat Baru</h5>
            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-md font-medium mt-1 sm:mt-0">Tersimpan</span>
          </div>
          <p class="text-sm text-gray-600 mt-1">“Certified Scrum Master”</p>
          <p class="text-xs text-gray-400 mt-2 text-right">5 jam yang lalu</p>
        </li>

        <li class="relative border-l-4 border-yellow-500 bg-yellow-50/60 rounded-xl p-4 hover:shadow-md transition-all duration-200 ease-in-out">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
            <h5 class="font-semibold text-gray-800">Generate Rekomendasi</h5>
            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-md font-medium mt-1 sm:mt-0">Proses</span>
          </div>
          <p class="text-sm text-gray-600 mt-1">Semester Genap 2024/2025 telah selesai</p>
          <p class="text-xs text-gray-400 mt-2 text-right">1 hari yang lalu</p>
        </li>
      </ul>
    </div>
  </section>
</main>
@endsection


