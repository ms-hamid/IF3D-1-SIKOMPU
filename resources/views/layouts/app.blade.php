<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false }" x-cloak>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome + Tailwind + Vite --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

    {{-- ======================== SIDEBAR ======================== --}}
    <aside 
        class="fixed inset-y-0 left-0 w-64 z-50 bg-white border-r border-gray-200 shadow-sm transform 
                transition-transform duration-300 ease-in-out lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        @include('components.sidebar')
    </aside>

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
        <div class="sticky top-0 z-50 bg-white shadow-sm"> {{-- sticky + top-0 + z-50 --}}
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

    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs" defer></script>
</body>
</html>
