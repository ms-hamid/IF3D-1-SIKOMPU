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
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <span class="text-2xl">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <span class="text-2xl">&times;</span>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
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

    {{-- Header actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-lg sm:text-xl font-semibold text-gray-700">Daftar Sertifikat</h1>
        <div class="flex flex-wrap gap-3">
            <button onclick="window.location.reload()" class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg shadow-sm text-gray-700 text-sm sm:text-base">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
            <button @click="openModal = true" class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-blue-800 hover:bg-blue-700 rounded-lg text-white shadow-sm text-sm sm:text-base">
                <i class="fa-solid fa-plus"></i> Tambah Sertifikat
            </button>
        </div>
    </div>

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
    <div
        x-cloak
        x-show="openEditModal"
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
            @click.away="openEditModal = false" 
            class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 sm:p-8 relative max-h-[90vh] overflow-y-auto"
        >
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

    {{-- Modal Konfirmasi Hapus --}}
    @include('components.hapus-sertifikat')

    {{-- Statistik ringkas --}}
    @php
        $totalSertifikat = $sertifikats->count();
        $totalVerified = $sertifikats->where('status_verifikasi', 'Disetujui')->count();
        $totalPending = $sertifikats->where('status_verifikasi', 'Menunggu')->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-blue-100 p-4 rounded border border-blue-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Total Sertifikat</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-blue-700">{{ $totalSertifikat }}</p>
            <p class="text-gray-500 text-xs sm:text-sm">Sertifikat terdaftar</p>
        </div>

        <div class="bg-green-100 p-4 rounded border border-green-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Disetujui</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-green-700">{{ $totalVerified }}</p>
            <p class="text-gray-500 text-xs sm:text-sm">Sertifikat terverifikasi</p>
        </div>

        <div class="bg-yellow-100 p-4 rounded border border-yellow-300 text-center">
            <h3 class="text-xs sm:text-sm text-gray-500">Menunggu Verifikasi</h3>
            <p class="text-2xl sm:text-3xl font-semibold text-yellow-700">{{ $totalPending }}</p>
            <p class="text-gray-500 text-xs sm:text-sm">Sedang diproses</p>
        </div>
    </div>

    {{-- Daftar sertifikat --}}
    @if($sertifikats->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
        <i class="fa-solid fa-certificate text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum ada sertifikat</h3>
        <p class="text-gray-500 mb-4">Mulai tambahkan sertifikat kompetensi Anda</p>
        <button @click="openModal = true" class="px-4 py-2 bg-blue-800 hover:bg-blue-700 rounded-lg text-white">
            <i class="fa-solid fa-plus"></i> Tambah Sertifikat
        </button>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach($sertifikats as $sertifikat)
        <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2 text-sm sm:text-base flex-1">
                        🏅 {{ $sertifikat->nama_sertifikat }}
                    </h3>
                    @if($sertifikat->status_verifikasi === 'Disetujui')
                        <span class="text-green-500 text-xs px-2 py-1 bg-green-50 rounded-full flex-shrink-0">✓ Disetujui</span>
                    @elseif($sertifikat->status_verifikasi === 'Menunggu')
                        <span class="text-yellow-500 text-xs px-2 py-1 bg-yellow-50 rounded-full flex-shrink-0">⏱ Menunggu</span>
                    @else
                        <span class="text-red-500 text-xs px-2 py-1 bg-red-50 rounded-full flex-shrink-0">✗ Ditolak</span>
                    @endif
                </div>
                
                <div class="text-gray-500 text-xs sm:text-sm mb-3 space-y-1">
                    <p><i class="fa-regular fa-building"></i> {{ $sertifikat->institusi_pemberi }}</p>
                    <p><i class="fa-regular fa-calendar"></i> Tahun: {{ $sertifikat->tahun_diperoleh }}</p>
                </div>
            </div>

            <div class="flex gap-2 mt-4 pt-3 border-t border-gray-100">
                <a href="{{ route('sertifikasi.download', $sertifikat->id) }}" 
                   class="flex-1 text-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm rounded-lg transition">
                    <i class="fa-solid fa-download"></i> Download
                </a>
                <button 
                   @click="editId = {{ $sertifikat->id }}; openEditModal = true"
                   class="flex-1 text-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs sm:text-sm rounded-lg transition">
                    <i class="fa-solid fa-edit"></i> Edit
                </button>
                <button 
                    @click="
                        openDeleteModal = true;
                        deleteData = {
                            id: {{ $sertifikat->id }},
                            nama: '{{ $sertifikat->nama_sertifikat }}',
                            institusi: '{{ $sertifikat->institusi_pemberi }}',
                            tahun: '{{ $sertifikat->tahun_diperoleh }}'
                        }
                    "
                   class="flex-1 text-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm rounded-lg transition">
                    <i class="fa-solid fa-trash"></i> Hapus
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</main>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection