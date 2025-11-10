@extends('layouts.app')

@section('title', 'Manajemen Program Studi')
@section('page_title', 'Manajemen Program Studi')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }" @close-modal.window="openModal = false">

      {{-- Header --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5 gap-20">
        <div>
          <h1 class="text-xl font-semibold text-gray-800">Kelola Data Program Studi</h1>
          <p class="text-sm text-gray-500">Lakukan pengelolaan menyeluruh terhadap daftar Program Studi.</p>
        </div>
    
        <div class="flex flex-wrap items-center gap-3">
          <a href="{{ route('prodi.create') }}" 
             class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-md text-sm font-medium shadow-sm transition">
            + Tambah Data Baru
          </a>
          <button 
             class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium shadow-sm transition">
            Import Data
          </button>
    
          <div class="relative">
            <button id="dropdownButton" 
              class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium flex items-center gap-1 border">
              Ekspor Template
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    
      {{-- Table --}}
      <div class="bg-white rounded-md shadow-md overflow-hidden overflow-x-auto">
        
        <table class="w-full text-sm text-center text-gray-700 table-fixed">
          <thead class="bg-gray-100 text-gray-600 border-b">
            <tr>
              <th scope="col" class="w-1/12 px-6 py-3 font-medium">No</th>
              <th scope="col" class="w-4/12 px-6 py-3 font-medium text-left">Nama Program Studi</th>
              <th scope="col" class="w-2/12 px-6 py-3 font-medium">Kode Program Studi</th>
              <th scope="col" class="w-1/12 px-6 py-3 font-medium">Prodi</th>
              <th scope="col" class="w-2/12 px-6 py-3 font-medium">Jenjang</th>
              <th scope="col" class="w-2/12 px-6 py-3 font-medium">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr>
                <td class="px-6 py-3">1</td>
                <td class="px-6 py-3 text-left">Teknik Informatika</td>
                <td class="px-6 py-3">CS3101</td>
                <td class="px-6 py-3">TI</td>
                <td class="px-6 py-3">D3</td>
                <td class="px-6 py-3 flex items-center justify-center gap-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-3">2</td>
                <td class="px-6 py-3 text-left">Sistem Informasi</td>
                <td class="px-6 py-3">CS3102</td>
                <td class="px-6 py-3">SI</td>
                <td class="px-6 py-3">D4</td>
                <td class="px-6 py-3 flex items-center justify-center gap-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-3">3</td>
                <td class="px-6 py-3 text-left">Rekayasa Perangkat Lunak</td>
                <td class="px-6 py-3">CS3103</td>
                <td class="px-6 py-3">RPL</td>
                <td class="px-6 py-3">D4</td>
                <td class="px-6 py-3 flex items-center justify-center gap-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-3">4</td>
                <td class="px-6 py-3 text-left">Teknologi Rekayasa Komputer Jaringan</td>
                <td class="px-6 py-3">CS4104</td>
                <td class="px-6 py-3">TRKJ</td>
                <td class="px-6 py-3">D4</td>
                <td class="px-6 py-3 flex items-center justify-center gap-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-3">5</td>
                <td class="px-6 py-3 text-left">Keamanan Siber</td>
                <td class="px-6 py-3">CS4105</td>
                <td class="px-6 py-3">KS</td>
                <td class="px-6 py-3">D4</td>
                <td class="px-6 py-3 flex items-center justify-center gap-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-3">6</td>
                <td class="px-6 py-3 text-left">Bisnis Digital</td>
                <td class="px-6 py-3">CS4106</td>
                <td class="px-6 py-3">BD</td>
                <td class="px-6 py-3">D4</td>
                <td class="px-6 py-3 flex items-center justify-center gap-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-3">7</td>
                <td class="px-6 py-3 text-left">Informatika Medis</td>
                <td class="px-6 py-3">CS4107</td>
                <td class="px-6 py-3">IM</td>
                <td class="px-6 py-3">D3</td>
                <td class="px-6 py-3 flex items-center justify-center gap-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-3">8</td>
                <td class="px-6 py-3 text-left">Animasi dan Desain Game</td>
                <td class="px-6 py-3">CS4108</td>
                <td class="px-6 py-3">ADG</td>
                <td class="px-6 py-3">D4</td>
                <td class="px-6 py-3 flex items-center justify-center gap-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
          </tbody>
        </table>
      </div>

    </main>
    @endsection
    
