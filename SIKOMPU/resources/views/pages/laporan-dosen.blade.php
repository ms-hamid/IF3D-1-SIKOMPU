@extends('layouts.app')

@section('title', 'Laporan Penugasan')
@section('page_title', 'Laporan')

@section('content')

<div x-data="{ openDetailModal: false }" class="min-h-screen bg-gray-50">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if($pesanKosong)
            {{-- Tampilkan pesan jika tidak ada data --}}
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-sm">
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
            {{-- Status Card --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8 mb-6">
                
                <p class="text-sm text-gray-500 mb-6">Kelola data kompetensi dan penilaian Anda</p>

                {{-- Status Warning --}}
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 {{ $skorAkhir >= 70 ? 'text-green-600' : 'text-yellow-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $skorAkhir >= 70 ? 'M5 13l4 4L19 7' : 'M12 9v2m0 4h.01' }}" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-lg md:text-xl font-bold {{ $skorAkhir >= 70 ? 'text-green-700' : 'text-yellow-600' }}">
                            {{ $skorAkhir >= 70 ? 'Memenuhi Standar Ideal' : 'Belum Memenuhi Standar Ideal' }}
                        </h3>
                        <div class="mt-2 text-sm text-gray-600 space-y-1">
                            <p>Skor Akhir Penilaian: <span class="font-semibold">{{ $skorAkhir }}/100</span></p>
                            <p>Periode Penilaian: {{ $periode }}</p>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mb-8 pb-6 border-b border-gray-200">
                    Penilaian ini merupakan hasil evaluasi dan peringkat relatif berbasis sistem AI
                    terhadap dosen lain pada mata kuliah yang sama.
                </p>

                @if($penugasan)
                    {{-- Detail Penugasan --}}
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-blue-800 mb-2">
                            Ditunjuk Sebagai
                        </h2>
                        <h3 class="text-xl md:text-2xl font-bold text-blue-800 mb-3">
                            {{ ucfirst($penugasan->peran_penugasan) }} Mata Kuliah {{ $matakuliah->kode_mk }} - {{ $matakuliah->nama_mk }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            Program Studi: {{ $matakuliah->prodi->nama_prodi ?? 'N/A' }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- Tabel Hasil Penugasan Dosen --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 md:p-6 bg-gray-50 border-b">
                    <h3 class="text-base md:text-lg font-semibold text-gray-800">Hasil Penugasan Dosen</h3>
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nama Dosen</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">NIDN/NIP</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Prodi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Kode MK</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Posisi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Hasil</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($penugasan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">1</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if(auth()->user()->foto_profil)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="{{ auth()->user()->nama_lengkap }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ auth()->user()->nama_lengkap }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ auth()->user()->nidn ?? auth()->user()->nip }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ auth()->user()->prodi }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $matakuliah->kode_mk }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $penugasan->peran_penugasan == 'koordinator' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($penugasan->peran_penugasan) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button 
                                        @click="openDetailModal = true" 
                                        class="text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center font-medium"
                                    >
                                        Lihat detail
                                        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @if($penugasan)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                @if(auth()->user()->foto_profil)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="{{ auth()->user()->nama_lengkap }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-lg">
                                        {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 mb-1">{{ auth()->user()->nama_lengkap }}</p>
                                <p class="text-xs text-gray-500 mb-2">{{ auth()->user()->nidn ?? auth()->user()->nip }}</p>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $penugasan->peran_penugasan == 'koordinator' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($penugasan->peran_penugasan) }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                                        {{ $matakuliah->kode_mk }}
                                    </span>
                                </div>
                                <button 
                                    @click="openDetailModal = true" 
                                    class="text-sm text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center font-medium"
                                >
                                    Lihat detail
                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @endif
    </main>

    {{-- Modal Component --}}
    <x-modal-detail-skor 
        :skorAkhir="$skorAkhir"
        :skorSelfAssessment="$skorSelfAssessment ?? 0"
        :skorPenelitian="$skorPenelitian ?? 0"
        :skorPendidikan="$skorPendidikan ?? 0"
        :skorSertifikat="$skorSertifikat ?? 0"
        :periode="$periode"
        :matakuliah="$matakuliah"
    />
</div>

@endsection