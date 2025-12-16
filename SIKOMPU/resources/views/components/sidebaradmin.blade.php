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

    {{-- Navigasi Struktural --}}
    <nav class="flex-1 space-y-1">
      <a href="{{ route('dashboard.struktural') }}" 
         class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
         {{ request()->routeIs('dashboard.*') 
            ? 'bg-blue-800 text-white' 
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <i class="fa-solid fa-chart-line mr-3"></i> Dashboard
      </a>

      <a href="{{ route('manajemen.dosen') }}" 
         class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
         {{ request()->routeIs('manajemen.dosen*') 
            ? 'bg-blue-800 text-white' 
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <i class="fa-solid fa-users mr-3"></i> Manajemen Dosen
      </a>

      <a href="{{ route('manajemen.matkul') }}" 
         class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
         {{ request()->routeIs('manajemen.matkul*') 
            ? 'bg-blue-800 text-white' 
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <i class="fa-solid fa-book-open mr-3"></i> Manajemen Matkul
      </a>

      <a href="{{ route('manajemen.prodi') }}" 
         class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
         {{ request()->routeIs('manajemen.prodi*') 
            ? 'bg-blue-800 text-white' 
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <i class="fa-solid fa-building-columns mr-3"></i> Manajemen Prodi
      </a>

      <a href="{{ route('hasil.rekomendasi') }}" 
         class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
         {{ request()->routeIs('hasil.rekomendasi*') 
            ? 'bg-blue-800 text-white' 
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <i class="fa-solid fa-star mr-3"></i> Hasil Rekomendasi
      </a>
      <a href="{{ route('self.Assesment') }}" 
      class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
      {{ request()->routeIs('self.Assesment*') 
         ? 'bg-blue-800 text-white' 
         : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
     <i class="fa-solid fa-clipboard-check mr-3"></i> Self-Assesment
   </a>
   <a href="{{ route('sertifikasi.index') }}" 
      class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
      {{ request()->routeIs('sertifikasi*') 
         ? 'bg-blue-800 text-white' 
         : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
     <i class="fa-solid fa-medal mr-3"></i> Sertifikat
   </a>

   <a href="{{ route('penelitian.index') }}" 
      class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
      {{ request()->routeIs('penelitian*') 
         ? 'bg-blue-800 text-white' 
         : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
     <i class="fa-solid fa-flask mr-3"></i> Penelitian
   </a>

   <a href="{{ route('peforma.ai') }}" 
      class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
      {{ request()->routeIs('peforma.ai*') 
         ? 'bg-blue-800 text-white' 
         : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
     <i class="fa-solid fa-bolt mr-3"></i> Peforma AI
   </a>
  
      <a href="{{ route('laporan.struktural') }}" 
         class="flex items-center px-4 py-2.5 rounded-md font-medium text-sm transition duration-200
         {{ request()->routeIs('laporan.*') 
            ? 'bg-blue-800 text-white' 
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <i class="fa-regular fa-file-lines mr-3"></i> Laporan
      </a>
    </nav>

  </div>
</aside>
