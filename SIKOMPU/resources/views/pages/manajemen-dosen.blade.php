@extends('layouts.app')

@section('title', 'Manajemen Dosen')
@section('page_title', 'Manajemen Dosen')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ 
    openModal: false, 
    openEditModal: false,
    openDeleteModal: false,
    openImportModal: false,
    editData: {},
    editAction: '',
    deleteData: { id: null, nama: '' } 
}" 
@close-modal.window="openModal = false"
@open-edit-modal.window="openEditModal = true; editData = window.editDosenData; editAction = window.editDosenAction">

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

    {{-- Alert Warning (Import dengan error) --}}
    @if(session('warning'))
    <div x-data="{ show: true, showDetails: false }" 
         x-show="show"
         x-transition
         class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
            <button @click="show = false" class="text-yellow-600 hover:text-yellow-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @if(session('import_errors'))
        <button @click="showDetails = !showDetails" class="text-sm text-yellow-700 underline mb-2">
            <span x-show="!showDetails">Lihat detail error</span>
            <span x-show="showDetails">Sembunyikan detail</span>
        </button>
        <ul x-show="showDetails" class="list-disc list-inside space-y-1 text-sm bg-white p-3 rounded">
            @foreach(session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif
    </div>
    @endif

    {{-- Alert Error --}}
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

    {{-- Card: Daftar Dosen --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        {{-- HEADER DENGAN TOMBOL --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 p-4 border-b border-gray-200">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Daftar Dosen/Laboran</h2>
                <p class="text-sm text-gray-500">Kelola data dosen dan laboran dalam sistem</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                {{-- Tombol Import --}}
                <button @click="openImportModal = true" 
                        class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2.5 rounded-md transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Import Data
                </button>

                {{-- Dropdown Ekspor --}}
                <div x-data="{ openDropdown: false }" class="relative">
                    <button @click="openDropdown = !openDropdown" 
                            @click.away="openDropdown = false"
                            class="inline-flex items-center bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-md border border-gray-300 transition">
                        Ekspor
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openDropdown"
                         x-transition
                         class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                         style="display: none;">
                        <div class="py-1">
                            <a href="{{ route('dosen.template') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Template Excel Kosong
                            </a>
                            <a href="{{ route('dosen.export.excel') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ekspor Data (Excel)
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tombol Tambah --}}
                <button @click="openModal = true" 
                        class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-md transition">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Tambah Data Baru
                </button>
            </div>
        </div>

        <div class="p-4">
            {{-- Search + Filter --}}
            <form method="GET" action="{{ route('dosen.index') }}" class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari dosen..." 
                       class="w-full sm:w-1/3 border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <select name="prodi" 
                        onchange="this.form.submit()"
                        class="w-full sm:w-1/4 border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="">Semua Prodi</option>
                    <option value="Teknik Informatika" {{ request('prodi') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                    <option value="Teknologi Geomatika" {{ request('prodi') == 'Teknologi Geomatika' ? 'selected' : '' }}>Teknologi Geomatika</option>
                    <option value="Teknologi Rekayasa Multimedia" {{ request('prodi') == 'Teknologi Rekayasa Multimedia' ? 'selected' : '' }}>Teknologi Rekayasa Multimedia</option>
                    <option value="Animasi" {{ request('prodi') == 'Animasi' ? 'selected' : '' }}>Animasi</option>
                    <option value="Rekayasa Keamanan Siber" {{ request('prodi') == 'Rekayasa Keamanan Siber' ? 'selected' : '' }}>Rekayasa Keamanan Siber</option>
                    <option value="Teknologi Rekayasa Perangkat Lunak" {{ request('prodi') == 'Teknologi Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Teknologi Rekayasa Perangkat Lunak</option>
                    <option value="Teknologi Permainan" {{ request('prodi') == 'Teknologi Permainan' ? 'selected' : '' }}>Teknologi Permainan</option>
                    <option value="S2 Magister Terapan Teknik Komputer" {{ request('prodi') == 'S2 Magister Terapan Teknik Komputer' ? 'selected' : '' }}>S2 Magister Terapan Teknik Komputer</option>
                </select>
            </form>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 font-medium">No</th>
                            <th class="px-4 py-3 font-medium">Nama Dosen</th>
                            <th class="px-4 py-3 font-medium">NIDN/NIP</th>
                            <th class="px-4 py-3 font-medium">Prodi</th>
                            <th class="px-4 py-3 font-medium">Beban Mengajar</th>
                            <th class="px-4 py-3 font-medium text-center">Status</th>
                            <th class="px-4 py-3 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse($dosens as $index => $dosen)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $dosens->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-sm">{{ strtoupper(substr($dosen->nama_lengkap, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $dosen->nama_lengkap }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $dosen->jabatan }}
                                            @if(in_array($dosen->jabatan, ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi']))
                                                <span class="text-blue-600">• {{ $dosen->prodi }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $dosen->nidn }}</td>
                            <td class="px-4 py-3">{{ $dosen->prodi }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm">{{ $dosen->beban_mengajar ?? 0 }}/{{ $dosen->max_beban ?? 16 }} SKS</span>
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden max-w-[100px]">
                                        @php
                                            $max = $dosen->max_beban ?? 16;
                                            $percentage = ($dosen->beban_mengajar ?? 0) / $max * 100;
                                            $color = $percentage >= 80 ? 'bg-green-500' : ($percentage >= 50 ? 'bg-yellow-400' : 'bg-blue-500');
                                        @endphp
                                        <div class="{{ $color }} h-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">{{ $dosen->status ?? 'Aktif' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openEditDosen({
                                        id: {{ $dosen->id }},
                                        nama_lengkap: '{{ addslashes($dosen->nama_lengkap) }}',
                                        nidn: '{{ $dosen->nidn }}',
                                        prodi: '{{ $dosen->prodi }}',
                                        jabatan: '{{ $dosen->jabatan }}',
                                        status: '{{ $dosen->status ?? 'Aktif' }}'
                                    })" 
                                    class="text-blue-600 hover:text-blue-800 transition"
                                    title="Edit Dosen">
                                        <i class="fas fa-pen"></i>
                                    </button>
        
                                    <button type="button"
                                            @click="$dispatch('confirm-delete', { id: {{ $dosen->id }}, nama: '{{ addslashes($dosen->nama_lengkap) }}' })"
                                            class="text-red-500 hover:text-red-700 transition"
                                            title="Hapus Dosen">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p>Tidak ada data dosen</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex justify-between items-center mt-4 text-sm text-gray-500">
                <p>Menampilkan {{ $dosens->firstItem() ?? 0 }}–{{ $dosens->lastItem() ?? 0 }} dari {{ $dosens->total() }} data</p>
                <div class="flex items-center space-x-1">
                    {{ $dosens->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modals --}}
    @include('components.tambah-dosen')
    @include('components.edit_dosen')
    @include('components.delete-dosen')
    @include('components.modal-import-dosen')

</main>

<script>
function openEditDosen(dosen) {
    window.editDosenData = dosen;
    window.editDosenAction = `/dosen/${dosen.id}`;
    window.dispatchEvent(new CustomEvent('open-edit-modal'));
}
</script>

@endsection