{{-- ========== TOPBAR ========== --}}
<header class="bg-white border-b border-gray-200 shadow-sm flex items-center justify-between px-4 py-3 sticky top-0 z-40">
    {{-- Left Section: Hamburger + Page Title --}}
    <div class="flex items-center space-x-3">
        {{-- Tombol Hamburger (Mobile) --}}
        <button 
            @click="sidebarOpen = !sidebarOpen"
            class="text-gray-700 hover:text-blue-600 focus:outline-none lg:hidden transition-transform duration-200"
            aria-label="Toggle sidebar">
            <i class="fa-solid fa-bars-staggered text-xl"></i>
        </button>


        {{-- Judul Halaman --}}
        <h1 class="text-lg sm:text-xl font-semibold text-gray-800">
            @yield('title')
        </h1>
    </div>

    {{-- Right Section: User Info --}}
    <div class="flex items-center gap-4">
        {{-- Notifikasi --}}
        <button class="relative p-2 hover:bg-blue-50 rounded-full transition hidden sm:flex">
            <svg xmlns="http://www.w3.org/2000/svg" 
                class="h-6 w-6 text-gray-700" 
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405M19 13V8a6 6 0 10-12 0v5l-1.405 1.405M8 17h8"/>
            </svg>
            <span class="absolute top-1.5 right-1.5 inline-flex h-2.5 w-2.5 bg-red-500 rounded-full"></span>
        </button>

        {{-- Garis Pemisah --}}
        <div class="hidden sm:block w-px h-6 bg-gray-300"></div>

        {{-- User Info & Dropdown --}}
        <div 
            x-data="{ open: false }"
            x-cloak
            class="relative flex items-center gap-3"
        >

            {{-- Foto Profil Dummy --}}
            <button 
                @click="open = !open"
                class="focus:outline-none flex items-center"
            >
                {{-- Avatar Dummy --}}
                @php
                    $name = auth()->user()->name ?? 'John Doe';
                    $initial = strtoupper(substr($name, 0, 1));
                    // Warna tetap: gradasi biru navy
                    $color = 'from-blue-700 to-blue-900';
                @endphp

                <div 
                    class="h-10 w-10 rounded-full bg-gradient-to-br {{ $color }} 
                        flex items-center justify-center text-white font-semibold cursor-pointer shadow-sm"
                >
                    {{ $initial }}
                </div>

                {{-- Nama User (tampil di tablet & desktop) --}}
                <div class="hidden sm:flex flex-col leading-tight text-right ml-2">
                    <span class="text-sm font-semibold text-gray-800">
                        {{ $name }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ auth()->user()->role ?? 'User' }}
                    </span>
                </div>
            </button>



            {{-- Tombol Titik Tiga (desktop/tablet) --}}
            <button 
                @click="open = !open"
                class="hidden sm:flex p-1 hover:bg-blue-50 rounded-full transition focus:outline-none"
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
                class="absolute right-0 top-14 w-56 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden z-30 py-2"
            >
                {{-- Nama User (khusus mobile) --}}
                <div class="sm:hidden px-4 pb-2 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">
                        {{ auth()->user()->name ?? 'John Doe' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ auth()->user()->role ?? 'User' }}
                    </p>
                </div>

                {{-- Menu Items --}}
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 transition">
                    <i class="bi bi-person-circle text-gray-600 text-base"></i>
                    <span>Profile</span>
                </a>
                <a href="{{ route('ganti_password') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 transition">
                    <i class="bi bi-key text-gray-600 text-base"></i>
                    <span>Change Password</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 transition">
                        <i class="bi bi-box-arrow-right text-gray-600 text-base"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
