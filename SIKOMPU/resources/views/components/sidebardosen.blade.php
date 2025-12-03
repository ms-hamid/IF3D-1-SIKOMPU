<aside 
  class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-gray-200 shadow-sm transform 
         -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out"
  :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
>
  <div class="flex flex-col h-full justify-between px-3 pt-3 pb-5 overflow-y-auto">

    {{-- Logo --}}
    <div class="flex items-center mb-4 space-x-3 pl-1">
      <img src="{{ asset('images/logo_sikompu.png') }}" alt="Logo SiKompu" class="w-14 h-14 object-contain -ml-1">
      <div>
        <h1 class="text-lg font-bold text-[#1E3A8A] leading-tight">SIKOMPU</h1>
        <p class="text-[11px] text-gray-600 leading-tight">SISTEM PENENTUAN<br>KOORDINATOR PENGAMPU</p>
      </div>
    </div>

    <div class="border-t border-gray-200 mb-3"></div>

    {{-- Navigasi --}}
    <nav class="flex-1 space-y-1">
      <a href="{{ route('dashboard.dosen') }}" 
         class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
         {{ request()->routeIs('dashboard.*') 
            ? 'bg-blue-800 text-white' 
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <i class="fa-solid fa-chart-line mr-3"></i> Dashboard
      </a>

      <a href="{{ route('self-assessment.index') }}" 
         class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
         {{ request()->routeIs('self-assessment.*') 
            ? 'bg-blue-800 text-white' 
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <i class="fa-solid fa-clipboard-check mr-3"></i> Self Assessment
      </a>

      <a href="{{ route('penelitian.index') }}" 
         class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
         {{ request()->routeIs('penelitian.*') 
            ? 'bg-blue-800 text-white' 
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <i class="fa-solid fa-flask mr-3"></i> Penelitian
      </a>

      <a href="{{ route('sertifikasi.index') }}" 
         class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
         {{ request()->routeIs('sertifikasi.*') 
            ? 'bg-blue-800 text-white' 
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <i class="fa-solid fa-medal mr-3"></i> Sertifikasi
      </a>

      <a href="{{ route('laporan.index') }}" 
      class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
      {{ request()->routeIs('laporan.*') 
         ? 'bg-blue-800 text-white' 
         : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
     <i class="fa-solid fa-file-alt mr-3"></i> Laporan
     </a>
   </nav>
   
   </div>
</aside>
