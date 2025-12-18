@extends('layouts.app')

@section('title', 'Manajemen Program Studi')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ 
    openModal: false,
    openEditModal: false,
    openDeleteModal: false,
    editData: {},
    editAction: '',
    deleteData: { id: null, nama: '' }
    }" 
    @open-edit-modal.window="openEditModal = true; editData = window.editProdiData; editAction = window.editProdiAction">

    {{-- Alert Success --}}
    @if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show"
         x-transition
         class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button @click="show = false" class="text-green-600 hover:text-green-800">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
    <div x-data="{ show: true }" 
         x-show="show"
         x-transition
         class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button @click="show = false" class="text-red-600 hover:text-red-800">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
    <div x-data="{ show: true }" 
         x-show="show"
         x-transition
         class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Terjadi kesalahan:</span>
            </div>
            <button @click="show = false" class="text-red-600 hover:text-red-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Card: Daftar Program Studi --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 border-b border-gray-200">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Daftar Program Studi</h2>
                <p class="text-sm text-gray-500">Kelola data program studi dan jenjang</p>
            </div>
            
            {{-- Tombol Tambah Data Baru --}}
            <button @click="openModal = true" 
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Data Baru</span>
            </button>
        </div>

        {{-- Search & Filter --}}
        <div class="p-4">
            <form method="GET" action="{{ route('prodi.index') }}" class="flex flex-col sm:flex-row gap-3 mb-4">
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari program studi..." 
                       class="w-full sm:w-1/3 border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <select name="jenjang"
                        onchange="this.form.submit()"
                        class="w-full sm:w-1/4 border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="">Semua Jenjang</option>
                    <option value="D3" {{ request('jenjang') == 'D3' ? 'selected' : '' }}>D3</option>
                    <option value="D4" {{ request('jenjang') == 'D4' ? 'selected' : '' }}>D4</option>
                    <option value="S1" {{ request('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
                    <option value="S2" {{ request('jenjang') == 'S2' ? 'selected' : '' }}>S2</option>
                    <option value="S2 Terapan" {{ request('jenjang') == 'S2 Terapan' ? 'selected' : '' }}>S2 Terapan</option>
                </select>
            </form>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 border-b">
                        <tr>
                            <th class="px-4 py-3 font-medium w-12">No</th>
                            <th class="px-4 py-3 font-medium">Nama Program Studi</th>
                            <th class="px-4 py-3 font-medium text-center">Kode Prodi</th>
                            <th class="px-4 py-3 font-medium text-center">Jenjang</th>
                            <th class="px-4 py-3 font-medium text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divide-y divide-gray-100">
                        @forelse($prodis as $index => $prodi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $prodis->firstItem() + $index }}</td>
                            <td class="px-4 py-3 font-medium">{{ $prodi->nama_prodi }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                    {{ $prodi->kode_prodi }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs font-medium px-2 py-1 rounded
                                    @if($prodi->jenjang == 'D3') bg-orange-100 text-orange-700
                                    @elseif($prodi->jenjang == 'D4') bg-blue-100 text-blue-700
                                    @elseif($prodi->jenjang == 'S1') bg-green-100 text-green-700
                                    @elseif($prodi->jenjang == 'S2') bg-purple-100 text-purple-700
                                    @elseif($prodi->jenjang == 'S2 Terapan') bg-pink-100 text-pink-700
                                    @endif">
                                    {{ $prodi->jenjang }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- ✅ Edit Button - Pakai Alpine.js --}}
                                    <button @click="openEditProdi({
                                        id: {{ $prodi->id }},
                                        kode_prodi: '{{ addslashes($prodi->kode_prodi) }}',
                                        nama_prodi: '{{ addslashes($prodi->nama_prodi) }}',
                                        jenjang: '{{ $prodi->jenjang }}'
                                    })" 
                                    class="text-blue-600 hover:text-blue-800 transition"
                                    title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    
                                    {{-- ✅ Delete Button - Pakai Alpine.js --}}
                                    <button type="button"
                                            @click="$dispatch('confirm-delete', { id: {{ $prodi->id }}, nama: '{{ addslashes($prodi->nama_prodi) }}' })"
                                            class="text-red-500 hover:text-red-700 transition"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p>Tidak ada data program studi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex justify-between items-center mt-4 text-sm text-gray-500">
                <p>Menampilkan {{ $prodis->firstItem() ?? 0 }}–{{ $prodis->lastItem() ?? 0 }} dari {{ $prodis->total() }} data</p>
                <div class="flex items-center space-x-1">
                    {{ $prodis->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Include All Modals --}}
    @include('components.tambah-prodi')
    @include('components.edit-prodi')
    @include('components.delete-prodi')

</main>

{{-- ✅ Script untuk Edit Prodi --}}
<script>
function openEditProdi(prodi) {
    window.editProdiData = prodi;
    window.editProdiAction = `/prodi/${prodi.id}`;
    window.dispatchEvent(new CustomEvent('open-edit-modal'));
}
</script>

<style>
    [x-cloak] { display: none !important; }
</style>

@endsection