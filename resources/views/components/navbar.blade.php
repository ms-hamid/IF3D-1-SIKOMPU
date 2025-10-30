<!-- ======= HEADER ======= -->
<header class="sticky top-0 z-20 bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between shadow-sm">
    {{-- Judul Halaman --}}
    <h4 class="text-xl font-semibold text-gray-800 tracking-tight">
        @yield('page_title', 'Dashboard')
    </h4>

    {{-- Bagian Kanan --}}
    <div class="flex items-center gap-6">
        {{-- Notifikasi --}}
        <button class="relative p-2 hover:bg-gray-100 rounded-full transition">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-6 w-6 text-gray-700" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405M19 13V8a6 6 0 10-12 0v5l-1.405 1.405M8 17h8"/>
            </svg>
            <span class="absolute top-1.5 right-1.5 inline-flex h-2.5 w-2.5 bg-red-500 rounded-full"></span>
        </button>

        {{-- Garis Pemisah --}}
        <div class="w-px h-6 bg-gray-300"></div>

        {{-- User Info & Dropdown --}}
        <div 
            x-data="{ open: false }"
            x-cloak
            class="relative flex items-center gap-3"
        >
            {{-- Avatar Default --}}
            <img 
                src="https://cdn-icons-png.flaticon.com/512/847/847969.png" 
                alt="Default User Avatar"
                class="h-10 w-10 rounded-full border border-gray-200 shadow-sm object-cover bg-gray-50"
            >

            {{-- Nama User --}}
            <div class="flex flex-col leading-tight text-right">
                <span class="text-sm font-semibold text-gray-800">
                    {{ auth()->user()->name ?? 'John Doe' }}
                </span>
                <span class="text-xs text-gray-500">
                    {{ auth()->user()->role ?? 'User' }}
                </span>
            </div>

            {{-- Tombol Titik Tiga --}}
            <button 
                @click="open = !open"
                class="p-1 hover:bg-gray-100 rounded-full transition focus:outline-none"
            >
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-5 w-5 text-gray-600" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 6h.01M12 12h.01M12 18h.01" />
                </svg>
            </button>

            {{-- Dropdown Menu --}}
            <div 
                x-show="open"
                @click.away="open = false"
                x-transition.opacity.scale.origin.top.right
                class="absolute right-0 top-14 w-56 left-0.5 bg-white border border-gray-200 rounded- shadow-lg overflow-hidden z-30 py-2"
            >
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition">
                    <i class="bi bi-person-circle text-gray-600 text-base"></i>
                    <span>Profile</span>
                </a>
                <a href="{{ route('ganti_password') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition">
                    <i class="bi bi-key text-gray-600 text-base"></i>
                    <span>Change Password</span>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- ======= STYLE & SCRIPT ======= -->
<style>
    [x-cloak] { display: none !important; }
</style>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
