@extends('layouts.app')

@section('title', 'Laporan')
@section('page_title', 'Laporan')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false, deleteTarget: '' }" @close-modal.window="openModal = false">
    {{-- Header "Pusat Laporan" --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pusat Laporan</h2>
            <p class="text-sm text-gray-500">Generate dan unduh berbagai jenis laporan sistem</p>
        </div>
        <div class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full shadow-sm">
            Semester Ganjil 2024/2025
        </div>
    </div>

    {{-- Grid Laporan Utama (Laporan Akademik & Laporan Manajemen) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 lg:col-span-2"> 
            
            {{-- Kiri: Laporan Akademik --}}
            <div class="bg-white p-6 shadow-xl rounded-xl border border-gray-100 space-y-4">
                <div class="flex items-center space-x-3 text-green-600">
                    {{-- Icon for Academic Report --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.918 12.083 12.083 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.918 12.083 12.083 0 01.665-6.479L12 14zM12 14v4.5"></path></svg>
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Akademik</h3>
                </div>
                <p class="text-sm text-gray-500 mb-4">Laporan terkait kegiatan akademik</p>

                {{-- Rekap Final Pengampu --}}
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <p class="text-md font-medium text-gray-800">Rekap Final Pengampu</p>
                        <span class="text-xs text-gray-500 flex items-center">
                            {{-- Clock Icon --}}
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            5 menit
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">Laporan lengkap daftar pengampu mata kuliah per semester</p>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 mt-2">
                        <select class="flex-1 border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            <option>Ganjil 2024/2025</option>
                        </select>
                        {{-- PERUBAHAN Jarak PDF dan Excel --}}
                        <div class="flex flex-row space-x-2 mt-2 sm:mt-0">
                            <button class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 active:bg-red-800 transition duration-150 shadow-md">
                                {{-- Download Icon --}}
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                PDF
                            </button>
                            <button class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 active:bg-green-800 transition duration-150 shadow-md">
                                {{-- Download Icon --}}
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Excel
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Laporan Beban Mengajar Dosen --}}
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <p class="text-md font-medium text-gray-800">Laporan Beban Mengajar Dosen</p>
                        <span class="text-xs text-gray-500 flex items-center">
                            {{-- Clock Icon --}}
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            3 menit
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">Detail beban mengajar setiap dosen per semester</p>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 mt-2">
                        <select class="flex-1 border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            <option>Ganjil 2024/2025</option>
                        </select>
                        {{-- PERUBAHAN Jarak PDF dan Excel --}}
                        <div class="flex flex-row space-x-2 mt-2 sm:mt-0">
                            <button class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 active:bg-red-800 transition duration-150 shadow-md">
                                {{-- Download Icon --}}
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                PDF
                            </button>
                            <button class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 active:bg-green-800 transition duration-150 shadow-md">
                                {{-- Download Icon --}}
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Laporan Manajemen --}}
            <div class="bg-white p-6 shadow-xl rounded-xl border border-gray-100 space-y-4">
                <div class="flex items-center space-x-3 text-blue-600">
                    {{-- Icon for Management Report --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6m4 0a7 7 0 100-14 7 7 0 000 14zM12 11h.01M17 11h.01M12 16h.01M17 16h.01M9 16h.01"></path></svg>
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Manajemen</h3>
                </div>
                <p class="text-sm text-gray-500 mb-4">Laporan untuk keperluan manajemen</p>

                {{-- Statistik Koordinator --}}
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <p class="text-md font-medium text-gray-800">Statistik Koordinator</p>
                        <span class="text-xs text-gray-500 flex items-center">
                            {{-- Clock Icon --}}
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            2 menit
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">Laporan statistik penunjukan koordinator mata kuliah</p>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 mt-2">
                        <select class="flex-1 border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            <option>Ganjil 2024/2025</option>
                        </select>
                        {{-- PERUBAHAN Jarak PDF dan Excel --}}
                        <div class="flex flex-row space-x-2 mt-2 sm:mt-0">
                            <button class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 active:bg-red-800 transition duration-150 shadow-md">
                                {{-- Download Icon --}}
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                PDF
                            </button>
                            <button class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 active:bg-green-800 transition duration-150 shadow-md">
                                {{-- Download Icon --}}
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Excel
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Laporan Aktivitas Sistem --}}
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <p class="text-md font-medium text-gray-800">Laporan Aktivitas Sistem</p>
                        <span class="text-xs text-gray-500 flex items-center">
                            {{-- Clock Icon --}}
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            1 menit
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">Log aktivitas pengguna dan perubahan data</p>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 mt-2">
                        <select class="flex-1 border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            <option>30 Hari Terakhir</option>
                        </select>
                        {{-- PERUBAHAN Jarak PDF dan Excel --}}
                        <div class="flex flex-row space-x-2 mt-2 sm:mt-0">
                            <button class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 active:bg-red-800 transition duration-150 shadow-md">
                                {{-- Download Icon --}}
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                PDF
                            </button>
                            <button class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 active:bg-green-800 transition duration-150 shadow-md">
                                {{-- Download Icon --}}
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Laporan Terbaru (Bagian Tabel) --}}
    <div class="bg-white p-6 shadow-xl rounded-xl border border-gray-100 mt-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Laporan Terbaru</h3>
        <p class="text-sm text-gray-500 mb-6">History laporan yang telah di-generate</p>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            {{-- Kunci: table-fixed dan w-full --}}
            <table class="min-w-full divide-y divide-gray-200 table-fixed w-full"> 
                <thead class="bg-gray-50">
                    <tr>
                        {{-- Nama Laporan: 4/12 (33.3%) --}}
                        <th class="w-4/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Laporan
                        </th>
                        {{-- Periode: 3/12 (25%) --}}
                        <th class="w-3/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Periode
                        </th>
                        {{-- Dibuat: 3/12 (25%) --}}
                        <th class="w-3/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dibuat
                        </th>
                        {{-- Status: 1/12 (8.3%) --}}
                        <th class="w-1/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        {{-- Aksi: 1/12 (8.3%) --}}
                        <th class="w-1/12 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- Data Laporan Statis (Menggunakan Blade PHP) --}}
                    @php
                        $recentReports = [
                            ['icon_type' => 'pdf', 'name' => 'Rekap Final Pengampu', 'periode' => 'Ganjil 2024/2025', 'dibuat' => '15 Des 2024, 14:30', 'status' => 'Selesai'],
                            ['icon_type' => 'excel', 'name' => 'Beban Mengajar Dosen', 'periode' => 'Ganjil 2024/2025', 'dibuat' => '14 Des 2024, 09:15', 'status' => 'Selesai'],
                        ];
                    @endphp

                    @foreach ($recentReports as $report)
                    <tr>
                        <td class="w-4/12 px-6 py-4 text-sm font-medium text-gray-900">
                            <div class="flex items-center">
                                @if ($report['icon_type'] == 'pdf')
                                    <span class="mr-2 text-red-500">
                                        {{-- Icon File PDF (ganti dengan icon yang sesuai jika menggunakan library) --}}
                                        <i class="fas fa-file-pdf"></i>
                                    </span>
                                @elseif ($report['icon_type'] == 'excel')
                                    <span class="mr-2 text-green-600">
                                        {{-- Icon File Excel (ganti dengan icon yang sesuai jika menggunakan library) --}}
                                        <i class="fas fa-file-excel"></i>
                                    </span>
                                @endif
                                {{ $report['name'] }}
                            </div>
                        </td>
                        <td class="w-3/12 px-6 py-4 text-sm text-gray-500">
                            {{ $report['periode'] }}
                        </td>
                        <td class="w-3/12 px-6 py-4 text-sm text-gray-500">
                            {{ $report['dibuat'] }}
                        </td>
                        <td class="w-1/12 px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $report['status'] }}
                            </span>
                        </td>
                        <td class="w-1/12 px-6 py-4 text-sm font-medium text-right">
                            <div class="flex space-x-2 justify-end">
                                <button class="text-blue-600 hover:text-blue-800 transition duration-150" title="Unduh">
                                    {{-- Download Icon --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </button>
                                {{-- Tombol Hapus: Menampilkan Modal --}}
                                <button 
                                    class="text-red-600 hover:text-red-800 transition duration-150" 
                                    title="Hapus" 
                                    x-on:click="openModal = true; deleteTarget = '{{ $report['name'] }}'">
                                    {{-- Trash Icon --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Peringatan Hapus --}}
    <div 
        class="fixed inset-0 z-50 overflow-y-auto" 
        style="display: none;" 
        x-show="openModal" 
        x-cloak 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0">
        
        {{-- Flex container untuk centering --}}
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            {{-- Background Overlay --}}
            <div 
                class="fixed inset-0 transition-opacity" 
                aria-hidden="true"
                x-on:click="openModal = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            {{-- Modal Panel: Ubah sm:max-w-lg menjadi sm:max-w-xs dan tambahkan flex-col items-center untuk centering konten --}}
            <div 
                class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-xs sm:w-full" 
                role="dialog" 
                aria-modal="true" 
                aria-labelledby="modal-headline"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 flex flex-col items-center text-center">
                    {{-- Icon Warning --}}
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    
                    {{-- Konten Modal --}}
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-headline">
                            Konfirmasi Hapus
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Yakin ingin menghapus laporan <span x-text="deleteTarget" class="font-semibold text-gray-700"></span>?
                            </p>
                            <p class="text-xs text-red-600 mt-1 font-medium">
                                Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- Tombol Aksi --}}
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between space-x-2">
                    <button 
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm transition duration-150" 
                        x-on:click="openModal = false">
                        Batal
                    </button>
                    <button 
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm transition duration-150"
                        x-on:click="openModal = false; console.log('Hapus Laporan: ' + deleteTarget)"> {{-- Ganti console.log dengan aksi hapus sebenarnya --}}
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
