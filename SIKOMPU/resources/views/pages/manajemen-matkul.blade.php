@extends('layouts.app')

@section('title', 'Manajemen Mata Kuliah')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm" x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-check text-green-500 mr-3 text-xl"></i>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm" x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-exclamation text-red-500 mr-3 text-xl"></i>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-300 pb-3 gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Mata Kuliah</h1>
            <p class="text-gray-500 text-sm">Kelola data mata kuliah dan kategori kompetensi</p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center space-x-3">
            {{-- Tombol Refresh --}}
            <button 
                onclick="window.location.reload()" 
                class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition">
                <i class="fa-solid fa-rotate-right mr-1"></i> Refresh
            </button>

            {{-- Tombol Tambah Mata Kuliah --}}
            <button 
                @click="$dispatch('open-add-matkul-modal')" 
                class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Mata Kuliah
            </button>
        </div>
    </div>

    {{-- Statistik ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm border border-green-200 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Total Mata Kuliah</p>
                    <p class="text-3xl font-bold text-green-700">{{ $totalMataKuliah }}</p>
                    <p class="text-xs text-gray-500 mt-1">Mata kuliah terdaftar</p>
                </div>
                <div class="bg-green-200 p-3 rounded-full">
                    <i class="fa-solid fa-book text-green-700 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-sm border border-blue-200 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Total SKS</p>
                    <p class="text-3xl font-bold text-blue-700">{{ $totalSKS }}</p>
                    <p class="text-xs text-gray-500 mt-1">Satuan kredit semester</p>
                </div>
                <div class="bg-blue-200 p-3 rounded-full">
                    <i class="fa-solid fa-graduation-cap text-blue-700 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-sm border border-purple-200 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold mb-1">Semester Aktif</p>
                    <p class="text-3xl font-bold text-purple-700">{{ $totalSemester }}</p>
                    <p class="text-xs text-gray-500 mt-1">Semester tersedia</p>
                </div>
                <div class="bg-purple-200 p-3 rounded-full">
                    <i class="fa-solid fa-calendar-days text-purple-700 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Mata Kuliah per Semester --}}
    <section class="space-y-6">
        @forelse($mataKuliahBySemester as $semester => $mataKuliahList)
        <div>
            {{-- Header Semester --}}
            <div class="flex items-center justify-between mb-3 bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 rounded-lg shadow">
                <h2 class="font-bold text-white text-lg">
                    <i class="fa-solid fa-layer-group mr-2"></i>
                    Semester {{ $semester }}
                    <span class="bg-white text-blue-700 text-xs font-semibold px-3 py-1 rounded-full ml-2">
                        {{ $mataKuliahList->count() }} mata kuliah
                    </span>
                </h2>
                <div class="text-white text-sm">
                    <i class="fa-solid fa-calculator mr-1"></i>
                    Total: {{ $mataKuliahList->sum('sks') }} SKS
                </div>
            </div>

            {{-- Grid Cards Mata Kuliah --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($mataKuliahList as $mk)
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition group">
                    {{-- Header Card --}}
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 text-base group-hover:text-blue-600 transition flex items-center gap-2">
                                <i class="fa-solid fa-book-open text-blue-500 text-sm"></i>
                                {{ $mk->nama_mk }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1 font-mono">{{ $mk->kode_mk }}</p>
                        </div>
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-md whitespace-nowrap">
                            Sem {{ $mk->semester }}
                        </span>
                    </div>

                    {{-- Info Card --}}
                    <div class="space-y-2 mb-4 border-t border-gray-100 pt-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                <i class="fa-solid fa-graduation-cap text-gray-400 mr-2"></i>SKS
                            </span>
                            <span class="font-semibold text-gray-800">{{ $mk->sks }} SKS</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                <i class="fa-solid fa-clock text-gray-400 mr-2"></i>Sesi
                            </span>
                            <span class="font-semibold text-gray-800">{{ $mk->sesi }} Sesi</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                <i class="fa-solid fa-building-columns text-gray-400 mr-2"></i>Prodi
                            </span>
                            <span class="font-medium text-gray-700 text-xs truncate max-w-[150px]" title="{{ $mk->prodi->nama_prodi ?? '-' }}">
                                {{ $mk->prodi->nama_prodi ?? '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2 border-t border-gray-100 pt-3">
                        <button 
                            class="flex-1 flex items-center justify-center px-3 py-2 text-xs font-medium text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition">
                            <i class="fa-solid fa-edit mr-1"></i> Edit
                        </button>
                        <form 
                            action="{{ route('matakuliah.destroy', $mk->id) }}" 
                            method="POST" 
                            onsubmit="return confirm('Yakin ingin menghapus mata kuliah {{ $mk->nama_mk }}?')"
                            class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="w-full flex items-center justify-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                <i class="fa-solid fa-trash mr-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        {{-- Empty State --}}
        <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="inline-block p-6 bg-gray-100 rounded-full mb-4">
                <i class="fa-solid fa-book-open text-gray-400 text-5xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Mata Kuliah</h3>
            <p class="text-gray-500 mb-6">Mulai tambahkan mata kuliah untuk mengelola kurikulum</p>
            <button 
                @click="$dispatch('open-add-matkul-modal')"
                class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Mata Kuliah Pertama
            </button>
        </div>
        @endforelse
    </section>

</main>

{{-- Include Modal Form Tambah Mata Kuliah --}}
@include('components.tambah_matkul')
@endsection