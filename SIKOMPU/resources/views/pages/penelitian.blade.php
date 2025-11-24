@extends('layouts.app')

@section('title', 'Penelitian')
@section('page_title', 'Penelitian')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ 
    openModal: false, 
    openEditModal: false,
    editData: {},
    deleteData: {},
    openDeleteModal: false
}">

    {{-- Notifikasi Success & Error --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-red-700 font-bold">&times;</button>
    </div>
    @endif

    {{-- Header actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-lg sm:text-xl font-semibold text-gray-700">Daftar Penelitian</h1>
        <div class="flex flex-wrap gap-3">
            <button onclick="window.location.reload()" 
                    class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg shadow-sm text-gray-700 text-sm sm:text-base">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
            <button @click="openModal = true" 
                    class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-blue-800 hover:bg-blue-700 rounded-lg text-white shadow-sm text-sm sm:text-base">
                <i class="fa-solid fa-plus"></i> Tambah Penelitian
            </button>
        </div>
    </div>

    {{-- Modal Tambah Penelitian --}}
    <div
        x-cloak
        x-show="openModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-start justify-center sm:items-center bg-black/60 z-50 p-4 overflow-y-auto"
        style="display: none;"
    >
        <div 
            @click.away="openModal = false" 
            class="bg-white rounded-lg shadow-lg p-5 sm:p-6 w-full max-w-md my-8"
        >
            <x-tambah-penelitian />
        </div>
    </div>

    {{-- Modal Edit Penelitian --}}
    <div
        x-cloak
        x-show="openEditModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-start justify-center sm:items-center bg-black/60 z-50 p-4 overflow-y-auto"
        style="display: none;"
    >
        <div 
            @click.away="openEditModal = false" 
            class="bg-white rounded-lg shadow-lg p-5 sm:p-6 w-full max-w-md my-8"
        >
            <x-edit-penelitian />
        </div>
    </div>

    {{-- Modal Hapus Penelitian --}}
    <div
        x-cloak
        x-show="openDeleteModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-center justify-center bg-black/60 z-50 p-4"
        style="display: none;"
    >
        <div 
            @click.away="openDeleteModal = false" 
            class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md"
        >
            <x-delete-penelitian />
        </div>
    </div>

    {{-- Statistik ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-blue-100 p-4 rounded border border-blue-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Total Penelitian</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-blue-700">{{ $penelitians->count() }}</p>
            <p class="text-gray-500 text-xs sm:text-sm">Karya penelitian</p>
        </div>

        <div class="bg-green-100 p-4 rounded border border-green-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Penelitian Terbaru</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-green-700">
                {{ $penelitians->max('tahun_publikasi') ?? '-' }}
            </p>
            <p class="text-gray-500 text-xs sm:text-sm">Tahun publikasi</p>
        </div>

        <div class="bg-purple-100 p-4 rounded border border-pink-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Peran Aktif</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-pink-700">
                {{ $penelitians->unique('peran')->count() }}
            </p>
            <p class="text-gray-500 text-xs sm:text-sm">Jenis peran</p>
        </div>
    </div>

    {{-- Daftar penelitian --}}
    @if($penelitians->isEmpty())
    <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
        <i class="fa-solid fa-folder-open text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Penelitian</h3>
        <p class="text-gray-500 mb-4">Mulai tambahkan penelitian Anda dengan klik tombol "Tambah Penelitian"</p>
        <button @click="openModal = true" 
                class="px-4 py-2 bg-blue-800 hover:bg-blue-700 rounded-lg text-white shadow-sm">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Penelitian
        </button>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach ($penelitians as $p)
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-3">
                <h3 class="font-semibold text-gray-800 flex items-start gap-2 text-sm sm:text-base">
                    <i class="fa-solid fa-book text-blue-600 mt-1"></i>
                    <span class="line-clamp-2">{{ $p->judul_penelitian }}</span>
                </h3>
            </div>

            <div class="space-y-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="inline-block bg-blue-100 text-blue-700 text-xs sm:text-sm px-2 py-1 rounded-full">
                        <i class="fa-solid fa-user-tie mr-1"></i>{{ $p->peran }}
                    </span>
                </div>

                <div class="text-gray-600 text-xs sm:text-sm space-y-1">
                    <p class="flex items-center gap-2">
                        <i class="fa-regular fa-calendar w-4"></i> 
                        <span>Tahun: {{ $p->tahun_publikasi }}</span>
                    </p>
                    
                    @if($p->link_publikasi)
                    <p class="flex items-center gap-2">
                        <i class="fa-solid fa-link w-4"></i>
                        <a href="{{ $p->link_publikasi }}" target="_blank" 
                           class="text-blue-600 hover:text-blue-800 hover:underline truncate">
                            Lihat Publikasi
                        </a>
                    </p>
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-gray-200">
                <button @click="editData = {
                            id: {{ $p->id }},
                            judul_penelitian: '{{ addslashes($p->judul_penelitian) }}',
                            tahun_publikasi: {{ $p->tahun_publikasi }},
                            peran: '{{ $p->peran }}',
                            link_publikasi: '{{ $p->link_publikasi ?? '' }}'
                        }; openEditModal = true"
                        class="px-3 py-1 text-xs sm:text-sm bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-md transition-colors">
                    <i class="fa-solid fa-edit"></i> Edit
                </button>
                <button @click="deleteData = {
                            id: {{ $p->id }},
                            judul: '{{ addslashes($p->judul_penelitian) }}',
                            tahun: {{ $p->tahun_publikasi }},
                            peran: '{{ $p->peran }}'
                        }; openDeleteModal = true"
                        class="px-3 py-1 text-xs sm:text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-md transition-colors">
                    <i class="fa-solid fa-trash"></i> Hapus
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</main>

@endsection