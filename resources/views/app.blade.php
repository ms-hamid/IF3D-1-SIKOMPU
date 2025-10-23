<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'SIKOMPU')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
  <div class="d-flex dashboard">

    <aside class="sidebar">
      <div class="sidebar-header text-center mb-4">
        <img src="{{ asset('images/logo.sikompu.png') }}" alt="Logo SIKOMPU" class="sidebar-logo">
        <h5 class="fw-bold mt-2 text-white">SIKOMPU</h5>
        <small class="text-light">Sistem Penentuan Koordinator & Pengampu</small>
      </div>

      <nav class="nav flex-column mt-4">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <i class="fa-solid fa-chart-line me-2"></i> Dashboard
        </a>
        <a href="{{ route('generate.index') }}" class="nav-link {{ request()->routeIs('generate.index') ? 'active' : '' }}">
          <i class="fa-solid fa-gears me-2"></i> Generate Hasil
        </a>
        <a href="{{ route('dosen.index') }}" class="nav-link {{ request()->routeIs('dosen.index') ? 'active' : '' }}">
          <i class="fa-solid fa-user-tie me-2"></i> Manajemen Dosen
        </a>
        <a href="{{ route('matakuliah.index') }}" class="nav-link {{ request()->routeIs('matakuliah.index') ? 'active' : '' }}">
          <i class="fa-solid fa-book me-2"></i> Manajemen Mata Kuliah
        </a>
        <a href="{{ route('prodi.index') }}" class="nav-link {{ request()->routeIs('prodi.index') ? 'active' : '' }}">
          <i class="fa-solid fa-building-columns me-2"></i> Manajemen Prodi
        </a>
        <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
          <i class="fa-solid fa-file-lines me-2"></i> Laporan
        </a>
      </nav>

      <div class="sidebar-footer mt-auto text-center">
        <hr class="border-light">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-light btn-sm w-100">
            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
          </button>
        </form>
      </div>
    </aside>

    <main class="main-content">
      <nav class="header d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">@yield('page_title', 'Dashboard')</h4>
        <div class="user-info text-end">
          <span class="text-muted">👤 {{ auth()->user()->name ?? 'Guest' }}</span>
        </div>
      </nav>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @yield('content')
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>

  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
