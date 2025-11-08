<!-- <!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Overlay untuk mobile --}}
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false" 
        class="fixed inset-0 bg-black bg-opacity-25 z-20 md:hidden">
    </div>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        
        {{-- Header --}}
        <header class="flex items-center justify-between bg-white shadow p-4 border-b border-gray-200">
            <div class="flex items-center">
                <button class="md:hidden mr-4" @click="sidebarOpen = true">
                    ☰
                </button>
                <h2 class="text-xl font-semibold">@yield('pageTitle', 'Dashboard')</h2>
            </div>
            <div>
                <span class="hidden sm:inline">Welcome, {{ Auth::user()->name ?? 'User' }}</span>
            </div>
        </header>

        {{-- Halaman Konten --}}
        <main class="flex-1 overflow-auto">
            @yield('content')
        </main>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html> -->








<!-- <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'SIKOMPU')</title>

  <style>
    [x-cloak] { display: none !important; }
  </style>

  {{-- Tailwind CSS CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Bootstrap (opsional) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  {{-- Custom CSS --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 font-sans flex flex-col min-h-screen">

  {{-- Sidebar --}}
  <aside class="w-64 bg-white border-r border-gray-200 fixed top-0 left-0 h-screen overflow-y-auto z-30">
    <x-sidebar />
  </aside>

  {{-- Main content wrapper --}}
  <div class="flex-1 ml-64 flex flex-col">
    
    {{-- Header --}}
    <x-navbar />
  

    {{-- Konten halaman --}}
    <main class="flex-1 overflow-y-auto p-8">
      {{-- Alert sukses --}}
      @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
          {{ session('success') }}
        </div>
      @endif

      @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />

  </div>

  {{-- Scripts --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html> -->


<!-- @extends('layouts.app')

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
@endsection -->


<!-- Sidebar

<aside 
  class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-gray-200 shadow-sm transform 
         -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out"
  :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
>
  <div class="flex flex-col h-full justify-between px-3 pt-3 pb-5 overflow-y-auto">

    {{-- Logo --}}
    <div class="flex items-center mb-5 space-x-3 pl-1">
      <img src="{{ asset('images/logo_sikompu.png') }}" alt="Logo SiKompu" class="w-14 h-14 object-contain -ml-1">
      <div>
        <h1 class="text-lg font-bold text-[#1E3A8A] leading-tight">SIKOMPU</h1>
        <p class="text-[11px] text-gray-600 leading-tight">SISTEM PENENTUAN<br>KOORDINATOR PENGAMPU</p>
      </div>
    </div>

    <div class="border-t border-gray-200 mb-3"></div>

    {{-- Navigasi --}}
    <nav class="flex-1 space-y-1 text-[15px]">
      @php
        $menus = [
          ['route' => 'dashboard.dosen', 'icon' => 'fa-solid fa-gauge', 'label' => 'Dashboard'],
          ['route' => 'self-assessment.index', 'icon' => 'fa-regular fa-square-check', 'label' => 'Self Assessment'],
          ['route' => 'sertifikasi.index', 'icon' => 'fa-solid fa-id-card', 'label' => 'Sertifikasi'],
          ['route' => 'penelitian.index', 'icon' => 'fa-solid fa-flask', 'label' => 'Penelitian'],
          ['route' => 'laporan.index', 'icon' => 'fa-regular fa-file-lines', 'label' => 'Laporan'],
        ];
      @endphp

      @foreach ($menus as $menu)
        @php
          $isActive = request()->routeIs(Str::before($menu['route'], '.') . '.*');
        @endphp

        <a href="{{ route($menu['route']) }}"
          class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition-all duration-300
          {{ $isActive 
              ? 'bg-gradient-to-br from-[#1E3A8A] to-[#1E40AF] text-white' 
              : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700 active:bg-blue-50 focus:bg-blue-50 focus:text-blue-700' }}">
          
          <div class="flex items-center justify-center w-8 h-8 rounded-md 
                      {{ $isActive ? 'bg-white text-blue-900' : 'bg- text-gray-500 group-hover:bg-blue-600 group-hover:text-white' }}
                      transition-colors duration-300">
            <i class="{{ $menu['icon'] }} text-sm"></i>
          </div>

          <span>{{ $menu['label'] }}</span>
        </a>
      @endforeach
    </nav>

  </div>
</aside> -->



