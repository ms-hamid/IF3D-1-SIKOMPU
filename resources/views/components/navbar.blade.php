<nav class="bg-white border-b border-gray-200 px-4 py-2.5 flex items-center justify-between">
    <div class="flex items-center space-x-2">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
        <div>
            <h1 class="font-semibold text-lg">SiKomPu</h1>
            <p class="text-xs text-gray-500">Sistem Penentuan Koordinator Pengampu</p>
             <script src="https://cdn.tailwindcss.com"></script>
             <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <button class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405M19 13V8a6 6 0 10-12 0v5l-1.405 1.405M8 17h8"/>
            </svg>
            <span
                class="absolute top-0 right-0 inline-flex h-2 w-2 bg-red-500 rounded-full"></span>
        </button>

        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/user-avatar.png') }}" class="h-8 w-8 rounded-full border" alt="User">
            <div class="text-sm">
                <p class="font-semibold">Dr. Ahmad Wijaya</p>
                <p class="text-gray-500 text-xs">Kajur Informatika</p>
            </div>
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
    </div>
</nav>
