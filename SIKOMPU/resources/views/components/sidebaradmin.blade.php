<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Utama</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="D:\PBL\IF3D-1-SIKOMPU\public\images\logo_sikompu.png">
</head>
<body class="bg-gray-50 min-h-screen flex">

  <!-- SIDEBAR -->
  <aside class="w-64 bg-[#1E3A8A] text-white flex flex-col">
    <div class="flex items-center gap-3 px-6 py-5 border-b border-white/20">
      <img src="/images/logo.png" alt="Logo" class="w-10 h-10">
      <div>
        <h1 class="font-semibold text-lg leading-tight">SiKomPu</h1>
        <p class="text-xs text-blue-100">Sistem Penentuan Koordinator & Pengampu</p>
      </div>
    </div>

    <nav class="flex-1 mt-4 space-y-1">
      <a href="#" class="flex items-center px-6 py-2 bg-blue-900 hover:bg-blue-800 rounded-r-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9m0 0l9 9M4 10v10a1 1 0 001 1h3m10-11v11a1 1 0 001 1h3m-7-1h-4" />
        </svg>
        Dasbor
      </a>
      <a href="#" class="flex items-center px-6 py-2 hover:bg-blue-800">
        <span class="mr-3">👨‍🏫</span> Manajemen Dosen
      </a>
      <a href="#" class="flex items-center px-6 py-2 hover:bg-blue-800">
        <span class="mr-3">📚</span> Manajemen Mata Kuliah
      </a>
      <a href="#" class="flex items-center px-6 py-2 hover:bg-blue-800">
        <span class="mr-3">🏫</span> Manajemen Prodi
      </a>
      <a href="#" class="flex items-center px-6 py-2 hover:bg-blue-800">
        <span class="mr-3">📊</span> Generate Hasil
      </a>
      <a href="#" class="flex items-center px-6 py-2 hover:bg-blue-800">
        <span class="mr-3">📄</span> Laporan
      </a>
    </nav>

    <div class="mt-auto px-6 py-4 border-t border-white/20 text-xs text-blue-100">
      © 2025 SiKomPu
    </div>
  </aside>

  <!-- MAIN CONTENT AREA -->
  <div class="flex-1 flex flex-col">

    <!-- TOPBAR -->
    <header class="bg-white shadow-sm flex items-center justify-between px-6 py-4">
      <div>
        <h2 class="text-xl font-semibold text-gray-800">Dashboard Utama</h2>
        <p class="text-sm text-gray-500">Kelola data kompetensi dan penilaian Anda</p>
      </div>

      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://ui-avatars.com/api/?name=Dr.+Ahmad+Wijaya&background=1E3A8A&color=fff" alt="User Avatar" class="w-10 h-10 rounded-full">
          <div class="text-right">
            <p class="font-semibold text-gray-800">Dr. Ahmad Wijaya</p>
            <p class="text-sm text-gray-500">Kajur Informatika</p>
          </div>
        </div>

        <button class="p-2 rounded-full hover:bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
        </button>
      </div>
    </header>

    <!-- TEMPAT KONTEN -->
    <main class="flex-1 p-6">
      @yield('content')
    </main>

  </div>
</body>
</html>
