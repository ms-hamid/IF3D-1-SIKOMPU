@extends('layouts.app')

@section('title', 'Penelitian')
@section('page_title', 'Penelitian')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }">

    {{-- Header actions (Tombol di kanan atas, sebaris dengan judul) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-lg sm:text-xl font-semibold text-gray-700">Daftar Penelitian</h1>
        <div class="flex flex-wrap gap-2">
            <button class="flex items-center gap-1 border border-gray-300 px-3 py-1.5 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition whitespace-nowrap" onclick="window.location.reload()">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
            <button @click="openModal = true" class="flex items-center gap-1 bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition text-sm whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Tambah Penelitian
            </button>
        </div>
    </div>

    {{-- Modal Form Upload Penelitian (Asumsi komponen x-penelitian ada) --}}
    <div
        x-cloak
        x-show="openModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-start justify-center sm:items-center bg-black/60 z-50 p-4 min-h-screen"
        style="display: none;"
    >
        <div 
            @click.away="openModal = false" 
            class="bg-white shadow-lg p-5 sm:p-6 w-full max-w-md rounded-lg"
        >
            <x-penelitian />
        </div>
    </div>
    
    {{-- Statistik ringkas (Sesuai tampilan terbaru) --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
        
        {{-- Total Penelitian (Biru) --}}
        <div class="p-6 rounded-xl shadow-md flex flex-col justify-center items-center space-y-1" style="background-color: #f0f4ff; border: 1px solid #d4e0ff;">
            <h3 class="text-base font-semibold text-blue-700">Total Penelitian</h3>
            <p class="text-3xl font-bold text-blue-900">2</p>
            <p class="text-sm text-blue-600">Karya penelitian</p>
        </div>

        {{-- Total Bonus (Hijau) --}}
        <div class="p-6 rounded-xl shadow-md flex flex-col justify-center items-center space-y-1" style="background-color: #f0fff4; border: 1px solid #d4ffd4;">
            <h3 class="text-base font-semibold text-green-700">Total Bonus</h3>
            <p class="text-3xl font-bold text-green-900">+1.0</p>
            <p class="text-sm text-green-600">Nilai bonus penelitian</p>
        </div>

        {{-- Bidang Aktif (Ungu/Pink) --}}
        <div class="p-6 rounded-xl shadow-md flex flex-col justify-center items-center space-y-1" style="background-color: #fff0ff; border: 1px solid #ffd4ff;">
            <h3 class="text-base font-semibold text-purple-700">Bidang Aktif</h3>
            <p class="text-3xl font-bold text-purple-900">5</p>
            <p class="text-sm text-purple-600">Bidang Penelitian</p>
        </div>
    </div>
    
    <hr class="border-gray-200">

    {{-- Daftar penelitian (Sesuai tampilan kartu terbaru) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach ([
            [
                'judul' => 'Uji Penelitian', 
                'kategori' => 'AI & Pembelajaran Mesin', 
                'kategori_style' => 'background-color: #f0f4ff; color: #5a67d8;', // Warna ungu muda
                'status' => 'Diterbitkan', 
                'status_style' => 'background-color: #e6ffed; color: #38a169;', // Warna hijau muda
                'tahun' => 2024, 
                'lembaga' => 'Jurnal Uji',
                'mata_kuliah' => 'Pemrograman Dasar', 
                'bonus' => '+0.5',
            ],
            [
                'judul' => 'TESTET', 
                'kategori' => 'Jaringan', 
                'kategori_style' => 'background-color: #fffbe6; color: #d69e2e;', // Warna kuning muda
                'status' => 'Diterbitkan', 
                'status_style' => 'background-color: #e6ffed; color: #38a169;', // Warna hijau muda
                'tahun' => 2024, 
                'lembaga' => 'TESTSTESTE', 
                'mata_kuliah' => 'Jaringan Komputer', 
                'bonus' => '+0.5',
            ],
            // Tambahkan data penelitian lain di sini
        ] as $p)
        {{-- KARTU PENELITIAN --}}
        <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm flex flex-col justify-between">
            
            <div class="flex items-start mb-3">
                {{-- Icon Buku --}}
                <i class="fa-solid fa-book text-xl text-blue-600 mr-3 mt-1"></i>
                
                <div class="flex-1">
                    {{-- Judul Penelitian --}}
                    <h3 class="font-bold text-gray-800 text-base mb-2">{{ $p['judul'] }}</h3>

                    {{-- Badge Kategori --}}
                    <span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium mb-1" style="{{ $p['kategori_style'] }}">
                        {{ $p['kategori'] }}
                    </span>
                    
                    {{-- Badge Status --}}
                    <span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium mb-2" style="{{ $p['status_style'] }}">
                        {{ $p['status'] }}
                    </span>
                    
                    {{-- Detail Lembaga & Tahun --}}
                    <div class="text-gray-600 text-sm space-y-1">
                        <p class="flex items-center gap-2">
                            <i class="fa-regular fa-calendar-alt w-4 text-gray-500"></i>
                            Tahun {{ $p['tahun'] }}
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-building-columns w-4 text-gray-500"></i>
                            {{ $p['lembaga'] }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Mata Kuliah Terkait (Box abu-abu muda) --}}
            <div class="bg-gray-50 rounded-md p-3 mb-4 text-xs text-gray-600 border border-gray-200 mt-2">
                <p class="font-semibold mb-1">Mata Kuliah Terkait:</p>
                <p class="text-gray-800">{{ $p['mata_kuliah'] }}</p>
            </div>
            
            {{-- Bonus Info (Footer Kartu) --}}
            <div class="flex justify-between items-center pt-2">
                <p class="text-gray-600 text-sm font-medium">{{ $p['bonus'] }} bonus penelitian</p>
                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-md font-medium">
                    Bonus Penelitian
                </span>
            </div>
        </div>
        @endforeach
    </div>

</main>
@endsection