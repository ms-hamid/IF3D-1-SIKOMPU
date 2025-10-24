<aside class="w-64 h-screen bg-white border-r border-gray-200 fixed top-0 left-0">
    <div class="p-4 border-b">
        <h1 class="text-xl font-bold text-blue-700">Dashboard Utama</h1>
         <script src="https://cdn.tailwindcss.com"></script>
         <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <p class="text-xs text-gray-500">Kelola data kompetensi dan penilaian Anda</p>
    </div>

    <nav class="mt-4">
        <ul class="space-y-1">
            <li>
                <a href="{{ url('/sidebar') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="ml-2">Dasbor</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/dosen') }}"
                   class="flex items-center px-4 py-2 text-white bg-blue-600 rounded-r-full">
                    <i class="fas fa-user-tie w-5"></i>
                    <span class="ml-2 font-medium">Manajemen Dosen</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/matakuliah') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                    <i class="fas fa-book w-5"></i>
                    <span class="ml-2">Manajemen Mata Kuliah</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/sidebar')  }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                    <i class="fas fa-building w-5"></i>
                    <span class="ml-2">Manajemen Prodi</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/sidebar') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                    <i class="fas fa-cogs w-5"></i>
                    <span class="ml-2">Generate Hasil</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/sidebar') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="ml-2">Laporan</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
