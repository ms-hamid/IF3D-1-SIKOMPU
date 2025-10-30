@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<main class="flex-1 p-6">

  {{-- Banner --}}
  <div class="bg-gradient-to-br from-[#1E3A8A] to-[#1E40AF] text-white rounded-2xl p-5 flex justify-between items-center mb-6">
    <div>
      <h3 class="font-semibold text-lg">
        Sistem Penentuan Koordinator & Pengampu Dosen
      </h3>
      <p class="text-sm text-blue-100 mb-3">
        Kelola dan optimalkan distribusi beban mengajar dosen dengan algoritma cerdas
      </p>
      <button class="bg-white text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-50">
        Generate Rekomendasi Semester Ini
      </button>
    </div>
    <img src="{{ asset('images/div.png') }}" alt="Robot Icon" class="w-16 h-16 object-contain">
  </div>

  {{-- Greeting --}}
  <div class="w-full border-b border-gray-300 pb-3 mb-6">
    <div class="flex items-center space-x-3">
      <div class="bg-green-100 p-2 rounded-lg">
        <i class="fa-solid fa-chart-line text-green-600"></i>
      </div>
      <div class="flex flex-col">
        <h3 class="text-lg font-semibold text-gray-800">
          Selamat Datang, {{ auth()->user()->name ?? 'Nama Dosen' }}
        </h3>
        <p class="text-gray-500 text-sm mt-1 flex items-center">
          <i class="fa-regular fa-calendar text-gray-400 mr-1"></i>
          Minggu, 28 September 2025
        </p>
      </div>
    </div>
  </div>

  {{-- Card Data Diri Dosen --}}
  <div class="bg-white border rounded p-6 flex flex-col lg:flex-row items-start gap-8 mb-6">
    <div class="w-64 h-64 flex-shrink-0 mx-auto lg:mx-0">
      <img src="{{ asset('images/foto-dosen.png') }}" alt="Foto Dosen" class="rounded-xl w-full h-full object-cover border">
    </div>

    <div class="flex-1">
      <h2 class="text-lg font-semibold text-gray-700 uppercase tracking-wide mb-2">Data Diri Dosen</h2>
      <hr class="border-gray-300 mb-3">

      <table class="text-sm text-gray-700">
        <tr><td class="pr-3 py-1 font-medium">Nama</td><td class="pr-2">:</td><td>Dr. Mega Sari</td></tr>
        <tr><td class="pr-3 py-1 font-medium">NIDN</td><td class="pr-2">:</td><td>1122334455</td></tr>
        <tr><td class="pr-3 py-1 font-medium">Program Studi</td><td class="pr-2">:</td><td>Teknik Informatika</td></tr>
        <tr><td class="pr-3 py-1 font-medium">Email</td><td class="pr-2">:</td><td>mega.sari@polibatam.ac.id</td></tr>
        <tr>
          <td class="pr-3 py-1 font-medium">Status</td><td class="pr-2">:</td>
          <td><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Sudah Isi Self-Assessment</span></td>
        </tr>
      </table>
    </div>
  </div>

  {{-- Card Gabungan: Diagram (Kiri) + Aktivitas (Kanan) --}}
  <div class="bg-white border rounded p-6 flex flex-col lg:flex-row gap-6 shadow-sm">

    {{-- Diagram Self-Assessment --}}
    <div class="flex flex-col items-center justify-center flex-1 border-b lg:border-b-0 lg:border-r border-gray-200 pb-6 lg:pb-0 lg:pr-6">
      <h4 class="font-semibold text-gray-700 mb-4 text-center">Rata-Rata Dosen Self-Assessment</h4>
      <div class="relative w-40 h-40">
        <svg class="w-full h-full">
          <circle cx="50%" cy="50%" r="70" stroke="#e5e7eb" stroke-width="10" fill="none" />
          <circle cx="50%" cy="50%" r="70" stroke="#2563eb" stroke-width="10" fill="none"
                  stroke-dasharray="440" stroke-dashoffset="88"
                  stroke-linecap="round" transform="rotate(-90 80 80)" />
        </svg>
        <div class="absolute inset-0 flex items-center justify-center">
          <span class="text-2xl font-bold text-gray-800">80%</span>
        </div>
      </div>
      <div class="flex justify-center mt-3 text-sm text-gray-500">
        <span class="flex items-center mr-4"><span class="w-3 h-3 bg-blue-600 rounded-full mr-1"></span> Sudah Mengisi</span>
        <span class="flex items-center"><span class="w-3 h-3 bg-gray-200 rounded-full mr-1"></span> Belum Mengisi</span>
      </div>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="flex-1">
      <div class="flex items-center justify-between mb-4">
        <h4 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru Anda</h4>
        <a href="#" class="text-blue-600 text-sm hover:underline">Lihat semua</a>
      </div>

      <ul class="space-y-4">
        <li class="relative border-l-4 border-blue-500 bg-blue-50/40 rounded-xl p-4 hover:shadow transition">
          <div class="flex justify-between items-start">
            <h5 class="font-semibold text-gray-800">Perbarui Self-Assessment</h5>
            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-md font-medium">Selesai</span>
          </div>
          <p class="text-sm text-gray-600 mt-1">Mata kuliah Algoritma dan Pemrograman</p>
          <p class="text-xs text-gray-400 mt-2 text-right">2 jam yang lalu</p>
        </li>

        <li class="relative border-l-4 border-green-500 bg-green-50/40 rounded-xl p-4 hover:shadow transition">
          <div class="flex justify-between items-start">
            <h5 class="font-semibold text-gray-800">Unggah Sertifikat Baru</h5>
            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-md font-medium">Tersimpan</span>
          </div>
          <p class="text-sm text-gray-600 mt-1">“Certified Scrum Master”</p>
          <p class="text-xs text-gray-400 mt-2 text-right">5 jam yang lalu</p>
        </li>

        <li class="relative border-l-4 border-yellow-500 bg-yellow-50/40 rounded-xl p-4 hover:shadow transition">
          <div class="flex justify-between items-start">
            <h5 class="font-semibold text-gray-800">Generate Rekomendasi</h5>
            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-md font-medium">Proses</span>
          </div>
          <p class="text-sm text-gray-600 mt-1">Semester Genap 2024/2025 telah selesai</p>
          <p class="text-xs text-gray-400 mt-2 text-right">1 hari yang lalu</p>
        </li>
      </ul>
    </div>
  </div>

</main>
@endsection
