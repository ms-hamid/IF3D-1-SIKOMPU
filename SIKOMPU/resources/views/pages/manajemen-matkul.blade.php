@extends('layouts.app')

@section('title', 'Manajemen MataKuliah')
@section('page_title', 'Manajemen Matakuliah')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6">

    {{-- Alert Success/Error --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-300 pb-3 gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Mata Kuliah</h1>
            <p class="text-gray-500 text-sm">Kelola mata kuliah dan kategori kompetensi</p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex flex-wrap items-center gap-2">
            {{-- Filter Prodi --}}
            <select onchange="window.location.href='{{ route('matakuliah.index') }}?prodi_id='+this.value" 
                    class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition">
                <option value="">Semua Prodi</option>
                @foreach($prodiList as $prodi)
                    <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                        {{ $prodi->nama_prodi }}
                    </option>
                @endforeach
            </select>

            {{-- Tombol Import --}}
            <button 
                onclick="alert('Fitur import akan segera tersedia')"
                class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition">
                <i class="fa-solid fa-file-import mr-1"></i> Import
            </button>

            {{-- Tombol Refresh --}}
            <button 
                onclick="window.location.reload()" 
                class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition">
                <i class="fa-solid fa-rotate-right mr-1"></i> Refresh
            </button>

            {{-- Tombol Tambah Mata Kuliah --}}
            <button onclick="openTambahModal()"
                    class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Mata Kuliah
            </button>
        </div>
    </div>

    {{-- Statistik ringkas --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="items-center p-6 bg-green-100 rounded-xl shadow-sm border-green-100 text-center rounded-lg hover:bg-gray-100 transition">
            <p class="text-gray-700 text-sm font-semibold">Total Mata Kuliah</p>
            <p class="text-3xl font-bold text-green-700 mt-1">{{ $totalMataKuliah }}</p> 
            <p class="text-xs text-gray-500 mt-1">Mata kuliah terdaftar</p>
        </div>
        <div class="items-center p-6 bg-blue-100 rounded-xl shadow-sm border-blue-100 text-center rounded-lg hover:bg-gray-100 transition">
            <p class="text-gray-700 text-sm font-semibold">Total SKS</p>
            <p class="text-3xl font-bold text-blue-700 mt-1">{{ $totalSKS }}</p> 
            <p class="text-xs text-gray-500 mt-1">Satuan kredit semester</p>
        </div>
        <div class="items-center p-6 bg-purple-100 rounded-xl shadow-sm border-purple-100 text-center rounded-lg hover:bg-gray-100 transition">
            <p class="text-gray-700 text-sm font-semibold">Kategori Aktif</p>
            <p class="text-3xl font-bold text-purple-700 mt-1">{{ $totalKategori }}</p> 
            <p class="text-xs text-gray-500 mt-1">Kategori kompetensi</p>
        </div>
        <div class="items-center p-6 bg-orange-100 rounded-xl shadow-sm border-orange-100 text-center rounded-lg hover:bg-gray-100 transition">
            <p class="text-gray-700 text-sm font-semibold">Semester Aktif</p>
            <p class="text-3xl font-bold text-orange-700 mt-1">{{ $totalSemester }}</p> 
            <p class="text-xs text-gray-500 mt-1">Semester Tersedia</p>
        </div>
    </div>

    {{-- Daftar Mata Kuliah per Semester --}}
    <section class="space-y-6">
        @forelse($mataKuliahBySemester as $semester => $mataKuliahList)
        <div>
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Semester {{ $semester }}</h2>
                <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $mataKuliahList->count() }} mata kuliah
                </span>
            </div>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($mataKuliahList as $mk)
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm 
                            transition ease-in-out duration-300 
                            hover:scale-[1.02] hover:shadow-lg 
                            active:scale-[0.98] active:shadow-md">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-book text-blue-500"></i>
                            <h3 class="font-semibold text-gray-800 text-sm">{{ $mk->nama_mk }}</h3>
                        </div>
                        <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-0.5 rounded-md whitespace-nowrap">
                            Semester {{ $mk->semester }}
                        </span>
                    </div>
                    
                    <p class="text-xs text-gray-500 mb-2">{{ $mk->kode_mk }}</p>
                    
                    <p class="text-sm text-gray-600 mb-1">{{ $mk->sks }} SKS | {{ $mk->sesi }} Sesi</p>
                    <p class="text-xs text-gray-500 mb-2">Prodi: {{ $mk->prodi->nama_prodi ?? '-' }}</p>
                    
                    {{-- Tombol Aksi --}}
                    <div class="flex gap-2 mt-3">
                        <button type="button"
                                onclick='openEditModal({{ json_encode([
                                    "id" => $mk->id,
                                    "kode_mk" => $mk->kode_mk,
                                    "nama_mk" => $mk->nama_mk,
                                    "sks" => $mk->sks,
                                    "sesi" => $mk->sesi,
                                    "semester" => $mk->semester,
                                    "prodi_id" => $mk->prodi_id
                                ]) }})'
                                class="flex-1 px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-md transition cursor-pointer">
                            <i class="fa-solid fa-edit"></i> Edit
                        </button>
                        <button type="button"
                                onclick='openDeleteModal({{ json_encode([
                                    "id" => $mk->id,
                                    "nama_mk" => $mk->nama_mk,
                                    "kode_mk" => $mk->kode_mk,
                                    "sks" => $mk->sks,
                                    "semester" => $mk->semester
                                ]) }})'
                                class="flex-1 px-3 py-1.5 text-xs font-medium text-white bg-red-500 hover:bg-red-600 rounded-md transition cursor-pointer">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <i class="fa-solid fa-inbox text-gray-300 text-5xl mb-3"></i>
            <p class="text-gray-500 text-lg">Belum ada data mata kuliah</p>
            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Mata Kuliah" untuk menambah data baru</p>
        </div>
        @endforelse
    </section>

    {{-- Custom Pagination --}}
    @if($mataKuliah->hasPages())
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="flex flex-col items-center gap-4">
            {{-- Info Text di Atas --}}
            <div class="text-sm text-gray-600">
                Menampilkan 
                <span class="font-semibold text-gray-800">{{ $mataKuliah->firstItem() }}</span>
                sampai 
                <span class="font-semibold text-gray-800">{{ $mataKuliah->lastItem() }}</span>
                dari 
                <span class="font-semibold text-gray-800">{{ $mataKuliah->total() }}</span>
                hasil
            </div>

            {{-- Tombol Pagination di Bawah --}}
            <div class="flex items-center gap-1">
                {{-- Previous Button --}}
                @if ($mataKuliah->onFirstPage())
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $mataKuliah->previousPageUrl() }}" 
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($mataKuliah->getUrlRange(1, $mataKuliah->lastPage()) as $page => $url)
                    @if ($page == $mataKuliah->currentPage())
                        <span class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-md">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                            {{ $page }}
                        </a>
                    @endif

                    {{-- Add ellipsis for large page counts --}}
                    @if ($mataKuliah->lastPage() > 10 && $page == 5 && $mataKuliah->currentPage() <= 5)
                        <span class="px-3 py-2 text-sm text-gray-500">...</span>
                        @php break; @endphp
                    @endif
                @endforeach

                {{-- Next Button --}}
                @if ($mataKuliah->hasMorePages())
                    <a href="{{ $mataKuliah->nextPageUrl() }}" 
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                @else
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Include Modal Components --}}
    @include('components.tambah_matkul')
    @include('components.edit-matkul')
    @include('components.delete-matkul')

</main>
@endsection