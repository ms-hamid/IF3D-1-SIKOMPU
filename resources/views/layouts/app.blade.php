<!DOCTYPE html>
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

<body class="bg-gray-50 font-sans">

  <div class="flex h-screen overflow-hidden">
    {{-- Sidebar (tetap di kiri) --}}
    <aside class="w-64 bg-white border-r border-gray-200 fixed top-0 left-0 h-screen overflow-y-auto z-30">
      <x-sidebar />
    </aside>

    {{-- Main Content (kanan sidebar) --}}
    <div class="flex-1 ml-64 flex flex-col h-screen">
      
      {{-- Header (sticky di atas) --}}
      <header class="sticky top-0 z-20 bg-gray-50 border-b border-gray-200 px-8 py-4 flex justify-between items-center">
        <h4 class="text-2xl font-bold text-gray-800">
          @yield('page_title', 'Dashboard')
        </h4>
        <div class="text-right">
          <span class="text-gray-500">👤 {{ auth()->user()->name ?? 'Guest' }}</span>
        </div>
      </header>

      {{-- Konten halaman (scrollable) --}}
      <main class="flex-1 overflow-y-auto p-8">
        {{-- Alert sukses --}}
        @if(session('success'))
          <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
          </div>
        @endif

        @yield('content')
      </main>
    </div>
  </div>

  {{-- Scripts --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
