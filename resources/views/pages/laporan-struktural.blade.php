@extends('layouts.app')


@section('title', 'Hasil Rekomendasi')


@section('title', 'Laporan')
@section('page_title', 'Laporan')


@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }" @close-modal.window="openModal = false">
    {{-- Header "Pusat Laporan" --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pusat Laporan</h2>
            <p class="text-sm text-gray-500">Generate dan unduh berbagai jenis laporan sistem</p>
        </div>
        <div class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">
            Semester Ganjil 2024/2025
        </div>
    </div>

    {{-- Grid Laporan Utama (Laporan Akademik & Laporan Manajemen) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6"> {{-- Ganti grid-cols-2 lg:grid-cols-2 dengan grid-cols-1 sm:grid-cols-2 dan tambahkan gap-6 --}}
        
        {{-- Kiri: Laporan Akademik --}}
        <div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100 space-y-4">
            <div class="flex items-center space-x-3 text-blue-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 12H1V4h8V12zm-4 0v4h8V8H5v4z"></path><path fill-rule="evenodd" d="M19 12H9v8h10v-8zm-4 0v4h4v-4h-4z" clip-rule="evenodd"></path></svg>
                <h3 class="text-lg font-semibold text-gray-800">Laporan Akademik</h3>
            </div>
            <p class="text-sm text-gray-500 mb-4">Laporan terkait kegiatan akademik</p>

            {{-- Rekap Final Pengampu --}}
            <div class="border-t border-gray-200 pt-4 space-y-3">
                <div class="flex justify-between items-center">
                    <p class="text-md font-medium text-gray-800">Rekap Final Pengampu</p>
                    <span class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        5 menit
                    </span>
                </div>
                <p class="text-xs text-gray-500">Laporan lengkap daftar pengampu mata kuliah per semester</p>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 mt-2">
                    <select class="flex-1 border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option>Ganjil 2024/2025</option>
                    </select>
                    <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        PDF
                    </button>
                    <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Excel
                    </button>
                </div>
            </div>

            {{-- Laporan Beban Mengajar Dosen --}}
            <div class="border-t border-gray-200 pt-4 space-y-3">
                <div class="flex justify-between items-center">
                    <p class="text-md font-medium text-gray-800">Laporan Beban Mengajar Dosen</p>
                    <span class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        3 menit
                    </span>
                </div>
                <p class="text-xs text-gray-500">Detail beban mengajar setiap dosen per semester</p>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 mt-2">
                    <select class="flex-1 border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option>Ganjil 2024/2025</option>
                    </select>
                    <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        PDF
                    </button>
                    <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Excel
                    </button>
                </div>
            </div>
        </div>

        {{-- Kanan: Laporan Manajemen --}}
        <div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100 space-y-4">
            <div class="flex items-center space-x-3 text-blue-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M12 0H8C6.67 0 6 0.67 6 2v16c0 1.33 0.67 2 2 2h4c1.33 0 2-0.67 2-2V2c0-1.33-0.67-2-2-2zm0 18H8V2h4v16z"></path><path d="M9 10a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 00-1-1H9z"></path></svg>
                <h3 class="text-lg font-semibold text-gray-800">Laporan Manajemen</h3>
            </div>
            <p class="text-sm text-gray-500 mb-4">Laporan untuk keperluan manajemen</p>

            {{-- Statistik Koordinator --}}
            <div class="border-t border-gray-200 pt-4 space-y-3">
                <div class="flex justify-between items-center">
                    <p class="text-md font-medium text-gray-800">Statistik Koordinator</p>
                    <span class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        2 menit
                    </span>
                </div>
                <p class="text-xs text-gray-500">Laporan statistik penunjukan koordinator mata kuliah</p>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 mt-2">
                    <select class="flex-1 border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option>Ganjil 2024/2025</option>
                    </select>
                    <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        PDF
                    </button>
                    <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Excel
                    </button>
                </div>
            </div>

            {{-- Laporan Aktivitas Sistem --}}
            <div class="border-t border-gray-200 pt-4 space-y-3">
                <div class="flex justify-between items-center">
                    <p class="text-md font-medium text-gray-800">Laporan Aktivitas Sistem</p>
                    <span class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        1 menit
                    </span>
                </div>
                <p class="text-xs text-gray-500">Log aktivitas pengguna dan perubahan data</p>
                {{-- PERBAIKAN JARAK: Menyeragamkan space-x menjadi 3 --}}
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 mt-2">
                    <select class="flex-1 border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option>30 Hari Terakhir</option>
                    </select>
                    <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        PDF
                    </button>
                    <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>


 {{-- Laporan Terbaru (Bagian Tabel) --}}
<div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100 mt-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Laporan Terbaru</h3>
    <p class="text-sm text-gray-500 mb-6">History laporan yang telah di-generate</p>

    <div class="overflow-x-auto">
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
                @php
                    $recentReports = [
                        ['icon' => '📄', 'name' => 'Rekap Final Pengampu', 'periode' => 'Ganjil 2024/2025', 'dibuat' => '15 Des 2024, 14:30', 'status' => 'Selesai'],
                        ['icon' => '📄', 'name' => 'Beban Mengajar Dosen', 'periode' => 'Ganjil 2024/2025', 'dibuat' => '14 Des 2024, 09:15', 'status' => 'Selesai'],
                    ];
                @endphp

                @foreach ($recentReports as $report)
                <tr>
                    {{-- Tambahkan kelas lebar w-X/12 pada <td> untuk memastikan lebar baris data sesuai header --}}
                    <td class="w-4/12 px-6 py-4 text-sm font-medium text-gray-900">
                        <div class="flex items-center">
                            <span class="mr-2">{{ $report['icon'] }}</span>
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
                            <button class="text-blue-600 hover:text-blue-900" title="Unduh">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </button>
                            <button class="text-red-600 hover:text-red-900" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>
@endsection
