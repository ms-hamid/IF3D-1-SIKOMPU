<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false }" x-cloak>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    {{-- Tailwind + Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Alpine.js - HANYA SATU KALI DI SINI --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Loading Screen Styles --}}
    <style>
        [x-cloak] { display: none !important; }
        
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 0.2s ease-out;
        }
        #loading-screen.hidden {
            opacity: 0;
            pointer-events: none;
        }
        #loading-screen[style*="display: none"] {
            display: none !important;
        }
        .spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #2563eb;
            border-radius: 50%;
            width: 64px;
            height: 64px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    {{-- ======================== LOADING SCREEN ======================== --}}
    <div id="loading-screen">
        <div class="text-center">
            <div class="spinner mx-auto mb-4"></div>
            <p class="text-gray-600 font-medium text-lg">Memuat halaman...</p>
        </div>
    </div>

    {{-- ======================== SIDEBAR ======================== --}}
    @auth
        @if(auth()->user()->jabatan == 'Dosen' || auth()->user()->jabatan == 'Laboran')
            <x-sidebardosen />
        @elseif(auth()->user()->jabatan == 'Struktural' || auth()->user()->jabatan == 'Admin' || auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin'))
            <x-sidebaradmin />
        @else
            <x-sidebar />
        @endif
    @else
        <x-sidebar />
    @endauth

    {{-- ======================== OVERLAY (Mobile) ======================== --}}
    <div 
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 lg:hidden z-40 transition-opacity duration-300 ease-in-out pointer-events-auto"
        x-transition.opacity
    ></div>

    {{-- ======================== MAIN CONTENT WRAPPER ======================== --}}
    <div class="flex flex-1 flex-col lg:ml-64 relative z-0">

        {{-- ======================== TOPBAR ======================== --}}
        <div class="sticky top-0 z-50 bg-white shadow-sm">
            @include('components.topbar')
        </div>

        {{-- ======================== PAGE CONTENT ======================== --}}
        <main class="flex-1 px-2 sm:px-6 py-3 sm:py-5 overflow-y-auto">
            @yield('content')
        </main>

        {{-- ======================== FOOTER ======================== --}}
        <footer class="bg-white border-t border-gray-200 shadow-sm mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row justify-between items-center text-gray-500 text-xs sm:text-sm">
                <p class="text-center sm:text-left">&copy; {{ date('Y') }} Politeknik Negeri Batam. All rights reserved.</p>
                <p class="text-center sm:text-right mt-2 sm:mt-0">Versi 1.0.0</p>
            </div>
        </footer>
    </div>

    {{-- ======================== LOADING SCRIPT ======================== --}}
    <script>
        (function() {
            const loader = document.getElementById('loading-screen');
            if (!loader) return;

            let isHidden = false;

            // Hide loading screen when page is fully loaded - only once
            function hideLoader() {
                if (isHidden) return;
                isHidden = true;
                
                loader.classList.add('hidden');
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 200);
            }

            // Check if page is already loaded
            if (document.readyState === 'complete') {
                hideLoader();
            } else {
                // Hide when DOM is ready (faster)
                if (document.readyState === 'interactive') {
                    setTimeout(hideLoader, 100);
                }
                // Also hide when everything is loaded
                window.addEventListener('load', hideLoader, { once: true });
            }

            // Show loading on form submissions only
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.tagName === 'FORM' && !form.classList.contains('no-loading')) {
                    loader.classList.remove('hidden');
                    loader.style.display = 'flex';
                }
            });
        })();
    </script>

</body>
</html>