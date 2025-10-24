@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="flex min-h-screen bg-white">

    {{-- Main content --}}
    <main class="flex-1 p-6">

        {{-- Banner --}}
        <div class="bg-blue-600 text-white rounded-2xl p-5 flex justify-between items-center mb-6">
            <div>
                <h3 class="font-semibold text-lg">Sistem Penentuan Koordinator & Pengampu Dosen</h3>
                <p class="text-sm text-blue-100">Kelola dan optimalkan distribusi beban mengajar dosen dengan algoritma cerdas</p>
            </div>
            <button class="bg-white text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-50">
                Generate Rekomendasi Semester Ini
            </button>
        </div>

        {{-- Greeting --}}
        <div class="w-full border-b border-gray-300 pb-3 mb-6">
            <div class="flex items-center space-x-3">
                <div class="bg-green-100 p-2 rounded-lg">
                    <i class="fa-solid fa-chart-line text-green-600"></i>
                </div>
                <div class="flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Selamat Datang, {{ auth()->user()->name ?? 'Nama Dosen' }}
                    </h3>
                    <p class="text-gray-500 text-sm mt-1 flex items-center">
                        <i class="fa-regular fa-calendar text-gray-400 mr-1"></i>
                        Minggu, 28 September 2025
                    </p>
                </div>
            </div>
        </div>

        {{-- Data Dosen --}}
        <div class="bg-white shadow-md rounded-xl p-6 flex items-start space-x-8 w-full mb-6">
            <!-- Foto / Ilustrasi -->
            <div class="w-60 h-60 flex-shrink-0">
                <img src="{{ asset('images/foto-dosen.png') }}" alt="Foto Dosen" class="rounded-xl w-full h-full object-cover border">
            </div>

            <!-- Data Diri -->
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-700 uppercase tracking-wide mb-2">Data Diri Dosen</h2>
                <hr class="border-gray-300 mb-3">

                <table class="text-sm text-gray-700">
                    <tr>
                        <td class="pr-3 py-1 font-medium">Nama</td>
                        <td class="pr-2">:</td>
                        <td>Dr. Mega Sari</td>
                    </tr>
                    <tr>
                        <td class="pr-3 py-1 font-medium">NIDN</td>
                        <td class="pr-2">:</td>
                        <td>1122334455</td>
                    </tr>
                    <tr>
                        <td class="pr-3 py-1 font-medium">Program Studi</td>
                        <td class="pr-2">:</td>
                        <td>Teknik Informatika</td>
                    </tr>
                    <tr>
                        <td class="pr-3 py-1 font-medium">Email</td>
                        <td class="pr-2">:</td>
                        <td>mega.sari@polibatam.ac.id</td>
                    </tr>
                    <tr>
                        <td class="pr-3 py-1 font-medium">Status</td>
                        <td class="pr-2">:</td>
                        <td><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Sudah Isi Self-Assessment</span></td>
                    </tr>
                </table>
            </div>
        </div>



        {{-- Aktivitas Terbaru --}}
        <div class="bg-white rounded-xl shadow p-5 mb-6">
            <h4 class="font-semibold text-gray-700 mb-3">Aktivitas Terbaru</h4>
            <ul class="space-y-3 text-sm">
                <li class="border-b border-gray-100 pb-2">
                    <span class="text-green-600 font-medium">Dr. Mega Sari</span> telah mengupdate self-assessment
                    <span class="text-gray-500">untuk mata kuliah Algoritma dan Pemrograman</span>
                    <span class="float-right text-gray-400">2 jam yang lalu</span>
                </li>
                <li class="border-b border-gray-100 pb-2">
                    <span class="text-blue-600 font-medium">Prof. Andi Wijaya</span> mengupload sertifikat baru:
                    <span class="text-gray-500">“Certified Scrum Master”</span>
                    <span class="float-right text-gray-400">5 jam yang lalu</span>
                </li>
                <li>
                    <span class="text-gray-700 font-medium">Generate rekomendasi semester genap 2024/2025</span>
                    <span class="text-gray-400">telah selesai</span>
                    <span class="float-right text-gray-400">1 hari yang lalu</span>
                </li>
            </ul>
        </div>

        {{-- Rata-rata Self Assessment --}}
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <h4 class="font-semibold text-gray-700 mb-4">Rata-Rata Dosen Self-Assessment</h4>
            <div class="relative w-40 h-40">
                <svg class="w-full h-full">
                    <circle cx="50%" cy="50%" r="70" stroke="#e5e7eb" stroke-width="10" fill="none" />
                    <circle cx="50%" cy="50%" r="70" stroke="#2563eb" stroke-width="10" fill="none"
                            stroke-dasharray="440" stroke-dashoffset="88"
                            stroke-linecap="round" transform="rotate(-90 80 80)" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-2xl font-bold text-gray-800">80%</span>
                </div>
            </div>
            <div class="flex justify-center mt-3 text-sm text-gray-500">
                <span class="flex items-center mr-4"><span class="w-3 h-3 bg-blue-600 rounded-full mr-1"></span> Sudah Mengisi</span>
                <span class="flex items-center"><span class="w-3 h-3 bg-gray-200 rounded-full mr-1"></span> Belum Mengisi</span>
            </div>
        </div>
    </main>
</div>
@endsection
