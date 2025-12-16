@extends('layouts.app')

@section('title', 'Hasil Penugasan')
@section('page_title', 'Laporan Dosen')

@section('content')

{{-- Inisialisasi Alpine.js untuk mengelola status modal --}}
<div x-data="{ openDetailModal: false, selectedDosen: null }" class="flex h-screen bg-gray-100">

    {{-- Konten Utama --}}
    <main class="flex-1 overflow-y-auto">
        <div class="p-8">
            {{-- Hasil Lulus & Penugasan --}}
            <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
                <p class="text-xs text-gray-500 mb-4">Kelola data kompetensi dan penilaian Anda</p>

                {{-- Status LULUS --}}
                <div class="flex items-center text-green-600 font-bold mb-6 border-b pb-4 border-gray-200">
                    <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-2xl">LULUS</span>
                    <span class="ml-4 text-sm text-gray-600 font-normal">
                        Skor Akhir Penilaian: 88/100
                        <br>
                        Periode Penilaian: Semester Ganjil 2025/2026
                    </span>
                </div>

                {{-- Detail Penugasan --}}
                <h2 class="text-3xl font-bold text-blue-800 mb-4">
                    Ditunjuk Sebagai <br> Koordinator Mata Kuliah IF101- Algoritma dan Struktur Data
                </h2>
                <p class="text-sm text-gray-500">
                    Jangka Waktu: 1 September 2025 - 28 Februari 2026
                </p>
            </div>


            {{-- Tabel Hasil Penugasan Dosen --}}
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 bg-gray-50 border-b">
                    <h3 class="text-lg font-semibold text-gray-700">Hasil Penugasan Dosen</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-800 text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Dosen</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">NIDN/NIP</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Prodi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Kode MK</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Posisi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hasil</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Baris Data Dosen (Contoh) --}}
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">1</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        {{-- Tambahkan gambar jika ada --}}
                                        <p class="text-sm font-medium text-gray-900">Sri Fatmawati, M.Kom.</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">0123456789</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Teknik Informatika</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">IF101</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dosen Laboran</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                    {{-- MODIFIKASI: Menggunakan Alpine.js untuk membuka modal --}}
                                    <button 
                                        @click="openDetailModal = true; selectedDosen = { nama: 'Sri Fatmawati, M.Kom.', posisi: 'Dosen Laboran', hasil: 'LULUS (88/100)' }" 
                                        class="flex items-center hover:underline focus:outline-none"
                                    >
                                        Lihat detail
                                        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

  {{-- ***************************************************************** --}}
    {{-- POP-UP MODAL (POSISI DI TENGAH) --}}
    {{-- ***************************************************************** --}}
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

            {{-- Panel Modal (max-w-xl) --}}
            <div 
                x-show="openDetailModal" 
                x-transition:enter="ease-out duration-300" 
                x-transition:leave="ease-in duration-200" 
                class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-xl sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline"
            >
                
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
                    <h3 class="text-xl leading-6 font-bold text-blue-800 border-b pb-3 mb-5" id="modal-headline">
                        Detail Hasil Penugasan Koordinator Mata Kuliah
                    </h3>
                    <div class="space-y-4">
                        
                        {{-- 1. Rangkuman Skor (Tetap 3 Kolom Horizontal) --}}
                        <div>
                            <h4 class="font-bold text-lg mb-3 text-gray-700">Rangkuman Penilaian Total</h4>
                            
                            {{-- Kartu Skor --}}
                            <div class="grid grid-cols-3 gap-3 bg-blue-50 p-3 rounded-lg border border-blue-200">
                                
                                {{-- SKOR AKHIR --}}
                                <div class="text-center p-1 border-r border-blue-200">
                                <p class="text-3xl font-extrabold text-green-600">88</p> 
                                <p class="text-xs font-extrabold text-gray-600">SKOR AKHIR</p>
                                </div>
                             
                            <p class="text-xs mt-3 italic text-gray-500 text-center">
                                Dosen LULUS dan direkomendasikan untuk Koordinator MK.
                            </p>
                        </div>
                        
                        {{-- 2. Detail Penugasan & Dosen (List Vertikal) --}}
                        <div>
                            <h4 class="font-bold text-lg mb-3 text-gray-700">Informasi Dosen & Penugasan</h4>
                            <dl class="space-y-2 text-sm">
                                <div class="flex border-b pb-1">
                                    <dt class="font-medium text-gray-900 w-1/3">Nama Dosen:</dt>
                                    <dd class="text-gray-700 w-2/3" x-text="selectedDosen ? selectedDosen.nama : '...'"></dd>
                                </div>
                                <div class="flex border-b pb-1">
                                    <dt class="font-medium text-gray-900 w-1/3">Posisi Ditunjuk:</dt>
                                    <dd class="text-blue-600 font-semibold w-2/3" x-text="selectedDosen ? selectedDosen.posisi : '...'"></dd>
                                </div>
                                <div class="flex border-b pb-1">
                                    <dt class="font-medium text-gray-900 w-1/3">Mata Kuliah:</dt>
                                    <dd class="text-gray-700 w-2/3">IF101 - Algoritma dan Struktur Data</dd>
                                </div>
                                <div class="flex border-b pb-1">
                                    <dt class="font-medium text-gray-900 w-1/3">Periode:</dt>
                                    <dd class="text-gray-700 w-2/3">Ganjil 2025/2026</dd>
                                </div>
                            </dl>
                        </div>

                        {{-- 3. Rincian Kompetensi --}}
                        <div>
                             <h4 class="font-bold text-lg mb-3 text-gray-700">Rincian Kompetensi</h4>
                            <ul class="space-y-1 text-sm bg-gray-50 p-3 rounded-md">
                                <li class="flex justify-between"><span>Pendidikan:</span> <span class="font-medium text-green-600">92</span></li>
                                <li class="flex justify-between"><span>Self Assesment:</span> <span class="font-medium text-green-600">85</span></li>
                                <li class="flex justify-between"><span>Penelitian:</span> <span class="font-medium text-green-600">90</span></li>
                                <li class="flex justify-between"><span>Sertifikat:</span> <span class="font-medium text-green-600">88</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
                    {{-- ... (Konten detail skor dan dosen) ... --}}
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="openDetailModal = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- ***************************************************************** --}}
    {{-- AKHIR POP-UP MODAL --}}
    {{-- ***************************************************************** --}}
</div>
@endsection
