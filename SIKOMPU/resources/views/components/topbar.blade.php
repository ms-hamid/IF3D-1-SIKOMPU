<header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between">
    
    {{-- Hamburger Menu (Mobile) --}}
    <button 
        @click="sidebarOpen = !sidebarOpen"
        class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
        <i class="fa-solid fa-bars text-xl"></i>
    </button>

    {{-- Title (Desktop Only) --}}
    <h1 class="hidden lg:block text-xl font-bold text-gray-800">
        @yield('title', 'Dashboard')
    </h1>

    {{-- Right Side: Notification + Profile --}}
    <div class="flex items-center space-x-4 ml-auto">
        
        {{-- Notification Icon --}}
        <button class="relative text-gray-600 hover:text-gray-900 focus:outline-none">
            <i class="fa-regular fa-bell text-xl"></i>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                3
            </span>
        </button>

        {{-- Profile Dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button 
                @click="open = !open"
                class="flex items-center space-x-3 focus:outline-none hover:bg-gray-50 rounded-lg px-3 py-2 transition">
                
                {{-- Avatar --}}
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-sm overflow-hidden">
                    @if(Auth::user()->foto)
                        <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                    @endif
                </div>

                {{-- Name & Role --}}
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-gray-800">
                        {{ Auth::user()->nama_lengkap }}
                    </p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->jabatan }}</p>
                </div>

                <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
            </button>

            {{-- Dropdown Menu --}}
            <div 
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50">
                
                <div class="px-4 py-2 border-b border-gray-200">
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->nidn }}</p>
                </div>

                {{-- Link Profil (Gabungan Profil + Ganti Password) --}}
                <a href="{{ route('profil.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fa-solid fa-user mr-3 text-gray-400"></i>
                    Profil Saya
                </a>

                <hr class="my-2">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <i class="fa-solid fa-right-from-bracket mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>