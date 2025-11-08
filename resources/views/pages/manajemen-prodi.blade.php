@extends('layouts.app')

@section('title', 'Manajemen Program Studi')
@section('page_title', 'Manajemen Program Studi')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }" @close-modal.window="openModal = false">

      {{-- Header --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5 gap-20">
        <div>
          <h1 class="text-xl font-semibold text-gray-800">Dashboard Utama</h1>
          <p class="text-sm text-gray-500">Kelola data kompetensi dan penilaian Anda</p>
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
      <div class="bg-white rounded-md shadow-md overflow-hidden">
        <table class="min-w-full text-sm text-center text-gray-700">
          <thead class="bg-gray-100 text-gray-600 border-b">
            <tr>
              <th scope="col" class="px-6 py-3 font-medium">No</th>
              <th scope="col" class="px-6 py-3 font-medium">Nama Program Studi</th>
              <th scope="col" class="px-6 py-3 font-medium">Kode Program Studi</th>
              <th scope="col" class="px-6 py-3 font-medium">Prodi</th>
              <th scope="col" class="px-6 py-3 font-medium">Jenjang</th>
              <th scope="col" class="px-6 py-3 font-medium">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr>
              <td class="px-6 py-3">1</td>
              <td class="px-6 py-3">Teknik Informatika</td>
              <td class="px-6 py-3">CS3101</td>
              <td class="px-6 py-3">TI</td>
              <td class="px-6 py-3">D3</td>
              <td class="px-6 py-3 flex items-center gap-3">
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
              <td class="px-6 py-3">Sistem Informasi</td>
              <td class="px-6 py-3">CS3102</td>
              <td class="px-6 py-3">SI</td>
              <td class="px-6 py-3">D4</td>
              <td class="px-6 py-3 flex items-center gap-3">
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
              <td class="px-6 py-3">Teknik Elektro</td>
              <td class="px-6 py-3">ES3104</td>
              <td class="px-6 py-3">TE</td>
              <td class="px-6 py-3">D4</td>
              <td class="px-6 py-3 flex items-center gap-3">
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
    