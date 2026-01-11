@extends('layouts.app')

@section('title', 'Sertifikasi')
@section('page_title', 'Sertifikasi')

@section('content')
<main 
    class="flex-1 p-4 sm:p-6 space-y-6" 
    x-data="{ 
        openModal: false, 
        openEditModal: false, 
        openDeleteModal: false,
        editId: null,
        deleteData: null
    }" 
    x-effect="(openModal || openEditModal || openDeleteModal) ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')"
    @close-modal.window="openModal = false; openEditModal = false; openDeleteModal = false"
>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <span class="text-2xl">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <span class="text-2xl">&times;</span>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
        <strong class="font-bold">Terjadi kesalahan!</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <span class="text-2xl">&times;</span>
        </button>
    </div>
    @endif

    {{-- Header dan Tombol Aksi --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-lg sm:text-xl font-semibold text-gray-700">Daftar Sertifikasi</h1>
        <div class="flex flex-wrap gap-2">
            {{-- Tombol Refresh --}}
            <button onclick="window.location.reload();" 
                    class="flex items-center gap-2 border border-gray-300 px-4 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition whitespace-nowrap">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
            
            <button @click="openModal = true" 
                    class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm whitespace-nowrap shadow-md">
                <i class="fa-solid fa-plus"></i> Tambah Sertifikat
            </button>
        </div>
    </div>

    {{-- Statistik ringkas --}}
    @php
        $totalSertifikat = $sertifikats->count();
        // Perhitungan bonus (sesuaikan dengan logika Anda)
        $totalBonus = $totalSertifikat * 3.5; 
        // Hitung jumlah bidang/klasifikasi unik
        $bidangAktif = $sertifikats->pluck('kategori_id')->unique()->filter()->count();
    @endphp
    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
        {{-- Total Sertifikat (Biru) --}}
        <div class="p-6 rounded-xl shadow-md flex flex-col justify-center items-center space-y-1" style="background-color: #f0f4ff; border: 1px solid #d4e0ff;">
            <h3 class="text-base font-semibold text-blue-700">Total Sertifikat</h3>
            <p class="text-3xl font-bold text-blue-900">{{ $totalSertifikat }}</p>
            <p class="text-sm text-blue-600">Sertifikat terdaftar</p>
        </div>

        {{-- Total Bonus (Hijau) --}}
        <div class="p-6 rounded-xl shadow-md flex flex-col justify-center items-center space-y-1" style="background-color: #f0fff4; border: 1px solid #d4ffd4;">
            <h3 class="text-base font-semibold text-green-700">Total Bonus</h3>
            <p class="text-3xl font-bold text-green-900">+{{ number_format($totalBonus, 1) }}</p>
            <p class="text-sm text-green-600">Nilai bonus sertifikasi</p>
        </div>

        {{-- Bidang Aktif (Ungu/Pink) --}}
        <div class="p-6 rounded-xl shadow-md flex flex-col justify-center items-center space-y-1" style="background-color: #fff0ff; border: 1px solid #ffd4ff;">
            <h3 class="text-base font-semibold text-purple-700">Bidang Aktif</h3>
            <p class="text-3xl font-bold text-purple-900">{{ $bidangAktif }}</p>
            <p class="text-sm text-purple-600">Bidang Kompetensi</p>
        </div>
    </div>
    
    <hr class="border-gray-200">

    {{-- Modal Form Upload Sertifikasi --}}
    <div
        x-cloak
        x-show="openModal"
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
            @click.away="openModal = false" 
            class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 sm:p-8 relative max-h-[90vh] overflow-y-auto"
        >
            @include('components.tambah-sertifikat')
        </div>
    </div>

    {{-- Modal Edit Sertifikat --}}
    <div x-show="openEditModal" 
     x-cloak
     @keydown.escape.window="openEditModal = false"
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="openEditModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 transition-opacity"
         @click="openEditModal = false">
    </div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="openEditModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6"
             @click.away="openEditModal = false">
            
            <template x-if="editId">
                <div>
                    @foreach($sertifikats as $sertifikat)
                        <div x-show="editId == {{ $sertifikat->id }}">
                            @include('components.edit-sertifikat', ['sertifikat' => $sertifikat])
                        </div>
                    @endforeach
                </div>
            </template>

        </div>
    </div>
</div>

    {{-- Modal Konfirmasi Hapus --}}
    @include('components.hapus-sertifikat')

    {{-- Daftar Sertifikat (Card Grid) --}}
    @if($sertifikats->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sertifikats as $sertifikat)
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 space-y-3 hover:shadow-xl transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-medal text-xl text-yellow-600 drop-shadow-sm"></i>
                            <h4 class="font-bold text-lg text-gray-800">{{ Str::limit($sertifikat->nama_sertifikat, 30) }}</h4>
                        </div>
                        {{-- Rating bintang (dummy - bisa disesuaikan jika ada di database) --}}
                        <div class="text-yellow-500 text-sm flex items-center gap-1">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    
                    {{-- Badge Klasifikasi dengan warna dinamis --}}
                    @php
                        $badgeColors = [
                            'Teknologi Informasi' => 'bg-blue-100 text-blue-700',
                            'Rekayasa Perangkat Lunak' => 'bg-red-100 text-red-700',
                            'Jaringan Komputer' => 'bg-indigo-100 text-indigo-700',
                            'Data Science' => 'bg-purple-100 text-purple-700',
                            'Keamanan Siber' => 'bg-orange-100 text-orange-700',
                            'Cloud Computing' => 'bg-cyan-100 text-cyan-700',
                            'Pengembangan Web' => 'bg-green-100 text-green-700',
                            'Manajemen Proyek' => 'bg-yellow-100 text-yellow-700',
                            'Desain UI/UX' => 'bg-pink-100 text-pink-700',
                        ];
                        $badgeClass = $badgeColors[$sertifikat->kategori->nama ?? ''] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    
                    <span class="inline-block {{ $badgeClass }} text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $sertifikat->kategori->nama ?? 'Tidak ada kategori' }}
                    </span>
                    
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><i class="fa-solid fa-building text-gray-400 w-4"></i> {{ $sertifikat->institusi_pemberi }}</p>
                        <p><i class="fa-solid fa-calendar text-gray-400 w-4"></i> Tahun: {{ $sertifikat->tahun_diperoleh }}</p>
                    </div>
                    
                    {{-- Tombol Aksi --}}
                    <div class="mt-4 pt-3 border-t border-gray-100 flex gap-2">
                        {{-- Tombol Download --}}
                        <a href="{{ route('sertifikasi.download', $sertifikat->id) }}" 
                           class="flex-1 text-center px-3 py-2 bg-green-600 text-white text-xs sm:text-sm rounded-lg hover:bg-green-700 transition shadow-sm">
                            <i class="fa-solid fa-download"></i> Download
                        </a>
                        
                        {{-- Tombol Edit --}}
                        <button 
                           @click="editId = {{ $sertifikat->id }}; openEditModal = true"
                           class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-xs sm:text-sm rounded-lg hover:bg-blue-700 transition shadow-sm">
                            <i class="fa-solid fa-edit"></i> Edit
                        </button>
                        
                        {{-- Tombol Hapus --}}
                        <button 
                            @click="
                                openDeleteModal = true;
                                deleteData = {
                                    id: {{ $sertifikat->id }},
                                    nama: '{{ addslashes($sertifikat->nama_sertifikat) }}',
                                    institusi: '{{ addslashes($sertifikat->institusi_pemberi) }}',
                                    tahun: '{{ $sertifikat->tahun_diperoleh }}'
                                }
                            "
                           class="flex-1 text-center px-3 py-2 bg-red-600 text-white text-xs sm:text-sm rounded-lg hover:bg-red-700 transition shadow-sm">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Tampilan Kosong --}}
        <div class="bg-white rounded-xl shadow-lg p-12 text-center border border-gray-100">
            <i class="fa-solid fa-certificate text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Sertifikat</h3>
            <p class="text-gray-500 mb-6">Klik tombol "Tambah Sertifikat" untuk menambahkan data pertama Anda</p>
            <button @click="openModal = true" 
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                <i class="fa-solid fa-plus"></i> Tambah Sertifikat Pertama
            </button>
        </div>
    @endif

</main>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection