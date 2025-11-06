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
          // 🔹 SIMULASI ROLE SEMENTARA (ubah sesuai role yang mau kamu lihat)
          $role = 'koordinator'; // ubah ke 'admin' atau 'koordinator'

          // 🔹 Menu umum untuk semua role
          $menus = [
              ['route' => 'dashboard.dosen', 'icon' => 'fa-solid fa-gauge', 'label' => 'Dashboard'],
          ];

          // 🔹 Menu khusus dosen
          if ($role === 'dosen') {
              $menus = array_merge($menus, [
                  ['route' => 'self-assessment.index', 'icon' => 'fa-regular fa-square-check', 'label' => 'Self Assessment'],
                  ['route' => 'sertifikasi.index', 'icon' => 'fa-solid fa-id-card', 'label' => 'Sertifikasi'],
                  ['route' => 'penelitian.index', 'icon' => 'fa-solid fa-flask', 'label' => 'Penelitian'],
                  ['route' => 'laporan.index', 'icon' => 'fa-regular fa-file-lines', 'label' => 'Laporan'],
              ]);
          }

          // 🔹 Menu khusus structural
          if ($role === 'koordinator') {
              $menus = array_merge($menus, [

                // tambah menu structural disini 

              ]);
          }
      @endphp

      {{-- Loop menu --}}
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
 