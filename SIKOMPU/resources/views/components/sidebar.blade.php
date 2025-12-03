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
           * Ambil role dari user yang login
           */
          $user = auth()->user();
          $jabatan = $user ? $user->jabatan : null;
          
          // Tentukan role berdasarkan jabatan
          $role = 'guest';
          $struktural = ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi'];
          
          if (in_array($jabatan, $struktural)) {
              $role = 'struktural';
          } elseif (in_array($jabatan, ['Dosen', 'Laboran'])) {
              $role = 'dosen';
          }

          // Menu dasar (umum)
          $menus = [];

          // ---------------------------
          // ROLE: DOSEN & LABORAN
          // ---------------------------
          if ($role === 'dosen') {
              $menus = [
                  ['route' => 'dashboard.dosen', 'icon' => 'fa-solid fa-chart-line', 'label' => 'Dashboard'],
                  ['route' => 'self.assesment', 'icon' => 'fa-solid fa-clipboard-check', 'label' => 'Self-assesment'],
                  ['route' => 'sertifikat', 'icon' => 'fa-solid fa-medal', 'label' => 'Sertifikat'],
                  ['route' => 'penelitian', 'icon' => 'fa-solid fa-flask', 'label' => 'Penelitian'],
                  ['route' => 'laporan.index', 'icon' => 'fa-regular fa-file-lines', 'label' => 'Laporan'],
              ];
          }

          // ---------------------------
          // ROLE: STRUKTURAL (Kepala, Sekretaris, Kaprodi)
          // ---------------------------
          if ($role === 'struktural') {
              $menus = [
                 ['route' => 'dashboard.struktural', 'icon' => 'fa-solid fa-chart-line', 'label' => 'Dashboard'],
                 ['route' => 'manajemen.dosen', 'icon' => 'fa-solid fa-users', 'label' => 'Manajemen Dosen'],
                 ['route' => 'manajemen.matkul', 'icon' => 'fa-solid fa-book-open', 'label' => 'Manajemen Matkul'],
                 ['route' => 'manajemen.prodi', 'icon' => 'fa-solid fa-building-columns', 'label' => 'Manajemen Prodi'],
                 ['route' => 'hasil.rekomendasi', 'icon' => 'fa-solid fa-star', 'label' => 'Hasil Rekomendasi'],
                 ['route' => 'self.Assesment', 'icon' => 'fa-solid fa-clipboard-check', 'label' => 'Self-Assesment'],
                 ['route' => 'sertifikat', 'icon' => 'fa-solid fa-medal', 'label' => 'Sertifikat'],
                 ['route' => 'penelitian2', 'icon' => 'fa-solid fa-flask', 'label' => 'Penelitian'],
                 ['route' => 'peforma.ai', 'icon' => 'fa-solid fa-bolt', 'label' => 'Peforma AI'],
                 ['route' => 'laporan.index', 'icon' => 'fa-regular fa-file-lines', 'label' => 'Laporan'],
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
                        {{ $isActive ? 'bg-white text-blue-900' : 'text-gray-500' }}
                        transition-colors duration-300">
              <i class="{{ $menu['icon'] }} text-sm"></i>
            </div>

            <span>{{ $menu['label'] }}</span>
          </a>
      @endforeach
    </nav>

    {{-- ===========================
         USER INFO (Tanpa Logout)
    ============================ --}}
    <div class="border-t border-gray-200 pt-3 mt-3">
      @if(auth()->check())
        <div class="px-4 py-2 bg-gray-50 rounded-lg">
          <p class="text-xs text-gray-500 mb-1">Login sebagai:</p>
          <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->nama_lengkap }}</p>
          <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->jabatan }}</p>
        </div>
      @endif
    </div>

  </div>
</aside>
