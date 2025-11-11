<aside 
  class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-gray-200 shadow-sm transform 
         -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out"
  :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
>
  <div class="flex flex-col h-full justify-between px-3 pt-3 pb-5 overflow-y-auto">

    {{-- ===========================
         LOGO SECTION
    ============================ --}}
    <div class="flex items-center space-x-3 mb-5 pl-1">
      <div class="w-14 h-14 overflow-hidden rounded-xl bg-white flex items-center justify-center">
        <img 
          src="{{ asset('images/logo_sikompu.png') }}" 
          alt="Logo SiKompu" 
          class="w-full h-full object-cover object-center scale-[1.7]"
        >
      </div>

      <div class="leading-tight">
        <h1 class="text-lg font-bold text-[#1E3A8A] tracking-wide">SIKOMPU</h1>
        <p class="text-[11px] text-gray-600 uppercase">
          Sistem Penentuan<br>Koordinator Pengampu
        </p>
      </div>
    </div>

    <div class="border-t border-gray-200 mb-3"></div>

    {{-- ===========================
         NAVIGATION MENU
    ============================ --}}
    <nav class="flex-1 space-y-1 text-[15px]">
      @php
          /**
           * Simulasi role sementara (ubah sesuai login user)
           * 'dosen' | 'koordinator' | 'admin'
           */
          $role = 'dosen';

          // Menu dasar (umum)
          $menus = [];

          // ---------------------------
          // ROLE: DOSEN
          // ---------------------------
          if ($role === 'dosen') {
              $menus = [
                  ['route' => 'dashboard.dosen', 'icon' => 'fa-solid fa-gauge', 'label' => 'Dashboard Dosen'],
                  ['route' => 'self-assessment.index', 'icon' => 'fa-regular fa-square-check', 'label' => 'Self Assessment'],
                  ['route' => 'sertifikasi.index', 'icon' => 'fa-solid fa-id-card', 'label' => 'Sertifikasi'],
                  ['route' => 'penelitian.index', 'icon' => 'fa-solid fa-flask', 'label' => 'Penelitian'],
                  ['route' => 'laporan.index', 'icon' => 'fa-regular fa-file-lines', 'label' => 'Laporan'],
              ];
          }

          // ---------------------------
          // ROLE: KOORDINATOR / STRUKTURAL
          // ---------------------------
          if ($role === 'koordinator') {
              $menus = [
                  ['route' => 'dashboard.struktural', 'icon' => 'fa-solid fa-gauge', 'label' => 'Dashboard Struktural'],
                  ['route' => 'manajemen.dosen', 'icon' => 'fa-solid fa-user-tie', 'label' => 'Manajemen Dosen'],
                  ['route' => 'manajemen.prodi', 'icon' => 'fa-solid fa-building-columns', 'label' => 'Manajemen Prodi'],
                  ['route' => 'manajemen.matkul', 'icon' => 'fa-solid fa-book', 'label' => 'Manajemen Matakuliah'],
                  ['route' => 'hasil.rekomendasi', 'icon' => 'fa-regular fa-file-lines', 'label' => 'Hasil Rekomendasi'],
                  ['route' => 'laporan.struktural', 'icon' => 'fa-regular fa-file-lines', 'label' => 'Laporan Struktural'],
              ];
          }

          // ---------------------------
          // ROLE: ADMIN (opsional)
          // ---------------------------
          if ($role === 'admin') {
              $menus = [
                  ['route' => 'dashboard.admin', 'icon' => 'fa-solid fa-gauge', 'label' => 'Dashboard Admin'],
                  ['route' => 'user.management', 'icon' => 'fa-solid fa-users', 'label' => 'Manajemen User'],
                  ['route' => 'setting.index', 'icon' => 'fa-solid fa-gear', 'label' => 'Pengaturan Sistem'],
              ];
          }
      @endphp

      {{-- ===========================
           LOOP MENU
      ============================ --}}
      @foreach ($menus as $menu)
          @php
              $isActive = request()->routeIs($menu['route'] . '*');
          @endphp

          <a href="{{ route($menu['route']) }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition-all duration-300
              {{ $isActive 
                  ? 'bg-gradient-to-br from-[#1E3A8A] to-[#1E40AF] text-white' 
                  : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700 active:bg-blue-50 focus:bg-blue-50 focus:text-blue-700' }}">
            
            <div class="flex items-center justify-center w-8 h-8 rounded-md 
                        {{ $isActive ? 'bg-white text-blue-900' : 'text-gray-500 group-hover:bg-blue-600 group-hover:text-white' }}
                        transition-colors duration-300">
              <i class="{{ $menu['icon'] }} text-sm"></i>
            </div>

            <span>{{ $menu['label'] }}</span>
          </a>
      @endforeach
    </nav>

  </div>
</aside>