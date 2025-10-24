<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'SIKOMPU')</title>

  {{-- Tailwind CSS CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Bootstrap (jika masih ingin dipakai untuk komponen tertentu) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  {{-- Custom CSS --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-white font-sans">

  <div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="w-64 bg-indigo-700 text-white flex flex-col p-4 shadow-lg">
      <div class="text-center mb-6">
        <img src="{{ asset('images/logo.sikompu.png') }}" alt="Logo SIKOMPU" class="mx-auto w-16 h-16 rounded-full shadow-md">
        <h5 class="font-bold mt-3 text-lg">SIKOMPU</h5>
        <p class="text-xs text-indigo-200">Sistem Penentuan Koordinator & Pengampu</p>
      </div>

      <nav class="flex-1 space-y-2">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard.dosen') }}"
           class="flex items-center px-4 py-2 rounded-lg transition
           {{ request()->routeIs('dashboard.*') ? 'bg-indigo-800 font-semibold' : 'hover:bg-indigo-600' }}">
          <i class="fa-solid fa-chart-line mr-2"></i> Dashboard
        </a>

        {{-- Self-Assessment --}}
        <a href="{{ route('self-assessment.index') }}"
           class="flex items-center px-4 py-2 rounded-lg transition
           {{ request()->routeIs('self-assessment.*') ? 'bg-indigo-800 font-semibold' : 'hover:bg-indigo-600' }}">
          <i class="fa-solid fa-gears mr-2"></i> Self-Assessment
        </a>

        {{-- Sertifikasi --}}
        <a href="{{ route('sertifikasi.index') }}"
           class="flex items-center px-4 py-2 rounded-lg transition
           {{ request()->routeIs('sertifikasi.*') ? 'bg-indigo-800 font-semibold' : 'hover:bg-indigo-600' }}">
          <i class="fa-solid fa-user-tie mr-2"></i> Sertifikasi
        </a>

        {{-- Penelitian --}}
        <a href="{{ route('penelitian.index') }}"
           class="flex items-center px-4 py-2 rounded-lg transition
           {{ request()->routeIs('penelitian.*') ? 'bg-indigo-800 font-semibold' : 'hover:bg-indigo-600' }}">
          <i class="fa-solid fa-book mr-2"></i> Penelitian
        </a>

        {{-- Manajemen Prodi --}}
        <a href="{{ route('prodi.index') }}"
           class="flex items-center px-4 py-2 rounded-lg transition
           {{ request()->routeIs('prodi.*') ? 'bg-indigo-800 font-semibold' : 'hover:bg-indigo-600' }}">
          <i class="fa-solid fa-building-columns mr-2"></i> Manajemen Prodi
        </a>

        {{-- Laporan --}}
        <a href="{{ route('laporan.index') }}"
           class="flex items-center px-4 py-2 rounded-lg transition
           {{ request()->routeIs('laporan.*') ? 'bg-indigo-800 font-semibold' : 'hover:bg-indigo-600' }}">
          <i class="fa-solid fa-file-lines mr-2"></i> Laporan
        </a>
      </nav>

      {{-- Logout --}}
      <div class="mt-auto pt-4 border-t border-indigo-500 text-center">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold">
            <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
          </button>
        </form>
      </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 p-8">
      {{-- Header --}}
      <nav class="flex justify-between items-center mb-6">
        <h4 class="text-2xl font-bold text-gray-800">@yield('page_title', 'Dashboard')</h4>
        <div class="text-right">
          <span class="text-gray-500">👤 {{ auth()->user()->name ?? 'Guest' }}</span>
        </div>
      </nav>

      {{-- Alert sukses --}}
      @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
          {{ session('success') }}
        </div>
      @endif

      {{-- Konten halaman --}}
      @yield('content')
    </main>
  </div>

  {{-- Scripts --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
