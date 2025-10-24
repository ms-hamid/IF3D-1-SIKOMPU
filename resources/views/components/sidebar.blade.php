{{-- resources/views/components/sidebar.blade.php --}}
<aside class="w-64 bg-white text-gray-700 flex flex-col justify-between h-screen border-r border-gray-200 shadow-sm fixed top-0 left-0">

  <div class="flex-1 flex flex-col px-3 pt-3 pb-5 overflow-y-auto">
    <!-- Logo -->
    <div class="flex items-center mb-3 mt-0 space-x-3 pl-1">
      <img src="{{ asset('images/logo_sikompu.png') }}" 
           alt="Logo SiKomPu" 
           class="w-24 h-24 object-contain -ml-6">
      <div class="leading-tight">
        <h1 class="text-lg font-bold text-[#1E3A8A] tracking-wide -ml-8">SIKOMPU</h1>
        <p class="text-[11px] text-gray-600 font-medium leading-tight -ml-8">
          SISTEM PENENTUAN<br>KOORDINATOR PENGAMPU
        </p>
      </div>
    </div>

    <!-- Garis Pemisah -->
    <div class="border-t border-gray-200 mb-5"></div>

    <!-- Navigasi -->
    <nav class="flex-1 space-y-2 -mt-6">
      {{-- Dashboard --}}
      <div class="-mx-3">
        <a href="{{ route('dashboard.dosen') }}"
           class="flex items-center w-full px-4 py-2.5 font-medium transition
           {{ request()->routeIs('dashboard.*') 
              ? 'bg-[#1E40AF] text-white shadow-sm' 
              : 'text-gray-700 hover:bg-blue-50 hover:text-[#1E3A8A]' }}">
          <i class="fa-solid fa-chart-line mr-3 text-base"></i> 
          Dasbor
        </a>
      </div>

      {{-- Self Assessment --}}
      <div class="-mx-3">
        <a href="{{ route('self-assessment.index') }}"
           class="flex items-center w-full px-4 py-2.5 font-medium transition
           {{ request()->routeIs('self-assessment.*') 
              ? 'bg-[#1E40AF] text-white shadow-sm' 
              : 'text-gray-700 hover:bg-blue-50 hover:text-[#1E3A8A]' }}">
          <i class="fa-regular fa-square-check mr-3 text-base"></i>
          Self Assessment
        </a>
      </div>

      {{-- Sertifikasi --}}
      <div class="-mx-3">
        <a href="{{ route('sertifikasi.index') }}"
           class="flex items-center w-full px-4 py-2.5 font-medium transition
           {{ request()->routeIs('sertifikasi.*') 
              ? 'bg-[#1E40AF] text-white shadow-sm' 
              : 'text-gray-700 hover:bg-blue-50 hover:text-[#1E3A8A]' }}">
          <i class="fa-regular fa-id-badge mr-3 text-base"></i>
          Sertifikat
        </a>
      </div>

      {{-- Penelitian --}}
      <div class="-mx-3">
        <a href="{{ route('penelitian.index') }}"
           class="flex items-center w-full px-4 py-2.5 font-medium transition
           {{ request()->routeIs('penelitian.*') 
              ? 'bg-[#1E40AF] text-white shadow-sm' 
              : 'text-gray-700 hover:bg-blue-50 hover:text-[#1E3A8A]' }}">
          <i class="fa-regular fa-book mr-3 text-base"></i>
          Penelitian
        </a>
      </div>

      {{-- Laporan --}}
      <div class="-mx-3">
        <a href="{{ route('laporan.index') }}"
           class="flex items-center w-full px-4 py-2.5 font-medium transition
           {{ request()->routeIs('laporan.*') 
              ? 'bg-[#1E40AF] text-white shadow-sm' 
              : 'text-gray-700 hover:bg-blue-50 hover:text-[#1E3A8A]' }}">
          <i class="fa-regular fa-file-lines mr-3 text-base"></i>
          Laporan
        </a>
      </div>
    </nav>
  </div>

  <!-- Tombol Logout (selalu di bawah) -->
  <div class="p-3 border-t border-gray-200">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit"
              class="w-full flex items-center justify-center gap-2 py-2 bg-[#1E3A8A] hover:bg-[#1E40AF] text-white text-sm font-semibold rounded-lg transition">
        <i class="fa-solid fa-right-from-bracket text-base"></i> Logout
      </button>
    </form>
  </div>

</aside>
