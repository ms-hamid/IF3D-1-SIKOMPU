@extends('layouts.app')

@section('title', 'Laporan Penugasan')
@section('page_title', 'Laporan Dosen')

@section('content')

<div x-data="{ openDetailModal: false, selectedDosen: null }" class="flex h-screen bg-gray-100">
    <main class="flex-1 overflow-y-auto">
        <div class="p-8">
            
            @if($pesanKosong)
                {{-- Tampilkan pesan jika tidak ada data --}}
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-yellow-800">Informasi</h3>
                            <p class="mt-2 text-sm text-yellow-700">{{ $pesanKosong }}</p>
                        </div>
                    </div>
                </div>
            @else
                {{-- Hasil Lulus & Penugasan --}}
                <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
                    <p class="text-xs text-gray-500 mb-4">Kelola data kompetensi dan penilaian Anda</p>

                    {{-- Status Evaluasi AI --}}
                    <div class="flex items-center {{ $skorAkhir >= 70 ? 'text-green-600' : 'text-yellow-600' }} font-bold mb-6 border-b pb-4 border-gray-200">
                        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="{{ $skorAkhir >= 70 
                                ? 'M5 13l4 4L19 7' 
                                : 'M12 8v4m0 4h.01' }}" />
                        </svg>

                    <span class="text-2xl">
                        {{ $skorAkhir >= 70 
                        ? 'Memenuhi Standar Ideal' 
                        : 'Belum Memenuhi Standar Ideal' }}
                    </span>

                    <span class="ml-4 text-sm text-gray-600 font-normal">
                        Skor Akhir Penilaian: {{ $skorAkhir }}/100
                        <br>
                        Periode Penilaian: {{ $periode }}
                    </span>
                </div>

                    <p class="text-xs text-gray-500 mt-2">
                        Penilaian ini merupakan hasil evaluasi dan peringkat relatif berbasis sistem AI
                        terhadap dosen lain pada mata kuliah yang sama.
                    </p>

                    @if($penugasan)
                        {{-- Detail Penugasan --}}
                        <h2 class="text-3xl font-bold text-blue-800 mb-4">
                            Ditunjuk Sebagai <br> 
                            {{ ucfirst($penugasan->peran_penugasan) }} Mata Kuliah {{ $matakuliah->kode_mk }} - {{ $matakuliah->nama_mk }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            Program Studi: {{ $matakuliah->prodi->nama_prodi ?? 'N/A' }}
                        </p>
                    @endif
                </div>

                {{-- Tabel Hasil Penugasan Dosen di MK yang Sama --}}
                @if($dosenSeMatakuliah->count() > 0)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="p-6 bg-gray-50 border-b">
                        <h3 class="text-lg font-semibold text-gray-700">Dosen Lain di Mata Kuliah {{ $matakuliah->kode_mk }}</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-800 text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Dosen</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">NIDN</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Prodi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Posisi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Skor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($dosenSeMatakuliah as $index => $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900">{{ $detail->user->nama_lengkap }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->user->nidn }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->user->prodi }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $detail->peran_penugasan == 'Koordinator' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($detail->peran_penugasan) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ round($detail->skor_dosen_di_mk) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                        <button 
                                            @click="openDetailModal = true; 
                                                    selectedDosen = { 
                                                        nama: '{{ $detail->user->nama_lengkap }}', 
                                                        nidn: '{{ $detail->user->nidn }}',
                                                        posisi: '{{ ucfirst($detail->peran_penugasan) }}', 
                                                        skor: {{ round($detail->skor_dosen_di_mk) }},
                                                        matakuliah: '{{ $matakuliah->kode_mk }} - {{ $matakuliah->nama_mk }}',
                                                        periode: '{{ $periode }}'
                                                    }" 
                                            class="flex items-center hover:underline focus:outline-none"
                                        >
                                            Lihat detail
                                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </main>

    {{-- MODAL DETAIL --}}
    <div 
        x-show="openDetailModal" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto" 
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div 
                x-show="openDetailModal" 
                x-transition:enter="ease-out duration-300" 
                x-transition:leave="ease-in duration-200" 
                class="fixed inset-0 bg-gray-900 bg-opacity-70 backdrop-blur-md transition-opacity" 
                aria-hidden="true" 
                @click="openDetailModal = false"
            ></div>

            <div 
                x-show="openDetailModal" 
                x-transition:enter="ease-out duration-300" 
                x-transition:leave="ease-in duration-200" 
                class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-xl sm:w-full"
                role="dialog" aria-modal="true"
            >
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
                    <h3 class="text-xl leading-6 font-bold text-blue-800 border-b pb-3 mb-5">
                        Detail Hasil Penugasan
                    </h3>
                    <div class="space-y-4">
                        
                        {{-- Rangkuman Skor --}}
                        <div>
                            <h4 class="font-bold text-lg mb-3 text-gray-700">Skor Penilaian</h4>
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 text-center">
                                <p class="text-4xl font-extrabold text-green-600" x-text="selectedDosen?.skor || 0"></p>
                                <p class="text-xs font-medium text-gray-600 mt-1">SKOR AKHIR</p>
                            </div>
                        </div>
                        
                        {{-- Info Dosen --}}
                        <div>
                            <h4 class="font-bold text-lg mb-3 text-gray-700">Informasi Dosen</h4>
                            <dl class="space-y-2 text-sm">
                                <div class="flex border-b pb-1">
                                    <dt class="font-medium text-gray-900 w-1/3">Nama:</dt>
                                    <dd class="text-gray-700 w-2/3" x-text="selectedDosen?.nama || '...'"></dd>
                                </div>
                                <div class="flex border-b pb-1">
                                    <dt class="font-medium text-gray-900 w-1/3">NIDN:</dt>
                                    <dd class="text-gray-700 w-2/3" x-text="selectedDosen?.nidn || '...'"></dd>
                                </div>
                                <div class="flex border-b pb-1">
                                    <dt class="font-medium text-gray-900 w-1/3">Posisi:</dt>
                                    <dd class="text-blue-600 font-semibold w-2/3" x-text="selectedDosen?.posisi || '...'"></dd>
                                </div>
                                <div class="flex border-b pb-1">
                                    <dt class="font-medium text-gray-900 w-1/3">Mata Kuliah:</dt>
                                    <dd class="text-gray-700 w-2/3" x-text="selectedDosen?.matakuliah || '...'"></dd>
                                </div>
                                <div class="flex border-b pb-1">
                                    <dt class="font-medium text-gray-900 w-1/3">Periode:</dt>
                                    <dd class="text-gray-700 w-2/3" x-text="selectedDosen?.periode || '...'"></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="openDetailModal = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection