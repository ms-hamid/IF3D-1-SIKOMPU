@extends('layouts.app')

@section('title', 'Manajemen MataKuliah')


@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }" @close-modal.window="openModal = false">

      {{-- Header --}}
      <div class="flex justify-between items-center border-b border-gray-300 pb-3">
        <div>
          <h1 class="text-xl font-bold text-gray-800">Mata Kuliah</h1>
          <p class="text-gray-500 text-sm">Kelola data mata kuliah dan kategori kompetensi</p>
        </div>
    
        <div class="flex items-center gap-3">
          <button 
            class="px-4 py-2 bg-white-100 text-gray rounded-md text-sm hover:bg-white-700 flex items-center gap-2">
            <i class="fa-solid fa-rotate-right"></i> Refresh
          </button>
    
          @if (Route::has('matakuliah.create'))
            <a href="{{ route('matakuliah.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 flex items-center gap-2">
              <i class="fa-solid fa-plus"></i> Tambah Mata Kuliah
            </a>
          @endif
        </div>
      </div>
    {{-- Statistik ringkas --}}
 <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-2 mb-4">
    <div class="items-center p-6 bg-green-100 rounded-xl shadow-sm border-green-100 text-center">
        <p class="text-gray-700 text-sm font-semibold">Total Mata Kuliah</p>
        <p class="text-3xl font-bold text-green-700 mt-1">8</p> 
        <p class="text-xs text-gray-500 mt-1">Mata kuliah terdaftar</p>
    </div>
    <div class="items-center p-6 bg-blue-100 rounded-xl shadow-sm border-blue-100 text-center">
      <p class="text-gray-700 text-sm font-semibold">Total SKS</p>
      <p class="text-3xl font-bold text-blue-700 mt-1">18</p> 
      <p class="text-xs text-gray-500 mt-1">Satuan kredit semester</p>
    </div>
    <div class="items-center p-6 bg-purple-100 rounded-xl shadow-sm border-purple-100 text-center">
        <p class="text-gray-700 text-sm font-semibold">Kategori Aktif</p>
        <p class="text-3xl font-bold text-purple-700 mt-1">5</p> 
        <p class="text-xs text-gray-500 mt-1">Kategori kompetensi</p>
    </div>
  </div>
{{-- Daftar Mata Kuliah per Semester --}}
      <section class="space-y-8">
        {{-- Semester 1 --}}
        <div>
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-gray-800">Semester 1  <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">2 mata kuliah</span></h2>
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
              <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                  <i class="fa-solid fa-book-open text-blue-500"></i> Pemrograman Dasar
                </h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">Semester 1</span>
              </div>
              <p class="text-sm text-gray-500 mb-1">IF201</p>
              <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-medium px-2 py-0.5 rounded-md">Software Engineering</span>
              <p class="text-sm text-gray-600 mt-2">3 SKS</p>
              <p class="text-s text-gray-400 mt-1 faa-study">Kategori : Software Engineering</p>
              <p class="text-xs text-gray-400 mt-1">Dibuat: 22/09/2025</p>
            </div>
    
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
              <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                  <i class="fa-solid fa-book-open text-blue-500"></i> Pemrograman Dasar
                </h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">Semester 1</span>
              </div>
              <p class="text-sm text-gray-500 mb-1">IF202</p>
              <span class="inline-block bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded-md">Basis Data</span>
              <p class="text-sm text-gray-600 mt-2">3 SKS</p>
              <p class="text-s text-gray-400 mt-1 faa-study">Kategori : Basis Data</p>
              <p class="text-xs text-gray-400 mt-1">Dibuat: 22/09/2025</p>
            </div>
          </div>
        </div>
    
        {{-- Semester 3 --}}
        <div>
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-gray-800">Semester 3  <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">2 mata kuliah</span></h2>
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
              <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                  <i class="fa-solid fa-book-open text-blue-500"></i> Struktur Data
                </h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">Semester 3</span>
              </div>
              <p class="text-sm text-gray-500 mb-1">IF301</p>
              <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-medium px-2 py-0.5 rounded-md">Software Engineering</span>
              <p class="text-sm text-gray-600 mt-2">3 SKS</p>
              <p class="text-s text-gray-400 mt-1 faa-study">Kategori : Software Engineering</p>
              <p class="text-xs text-gray-400 mt-1">Dibuat: 22/09/2025</p>
            </div>
    
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
              <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                  <i class="fa-solid fa-book-open text-blue-500"></i> Pemrograman Dasar
                </h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">Semester 3</span>
              </div>
              <p class="text-sm text-gray-500 mb-1">IF305</p>
              <span class="inline-block bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded-md">Basis Data</span>
              <p class="text-sm text-gray-600 mt-2">3 SKS</p>
              <p class="text-s text-gray-400 mt-1 faa-study">Kategori : Basis Data</p>
              <p class="text-xs text-gray-400 mt-1">Dibuat: 22/09/2025</p>
            </div>
          </div>
        </div>
         {{-- Semester 4 --}}
         <div>
            <div class="flex items-center justify-between mb-3">
              <h2 class="font-semibold text-gray-800">Semester 4  <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">2 mata kuliah</span></h2>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
              <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                  <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-book-open text-blue-500"></i> Struktur Data
                  </h3>
                  <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">Semester 4</span>
                </div>
                <p class="text-sm text-gray-500 mb-1">IF301</p>
                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-medium px-2 py-0.5 rounded-md">Software Engineering</span>
                <p class="text-sm text-gray-600 mt-2">3 SKS</p>
                <p class="text-s text-gray-400 mt-1 faa-study">Kategori : Software Engineering</p>
                <p class="text-xs text-gray-400 mt-1">Dibuat: 22/09/2025</p>
              </div>
      
              <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                  <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-book-open text-blue-500"></i> Pemrograman Dasar
                  </h3>
                  <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">Semester 4</span>
                </div>
                <p class="text-sm text-gray-500 mb-1">IF305</p>
                <span class="inline-block bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded-md">Basis Data</span>
                <p class="text-sm text-gray-600 mt-2">3 SKS</p>
                <p class="text-s text-gray-400 mt-1 faa-study">Kategori : Basis Data</p>
                <p class="text-xs text-gray-400 mt-1">Dibuat: 22/09/2025</p>
              </div>
            </div>
          </div>
           {{-- Semester 5 --}}
        <div>
            <div class="flex items-center justify-between mb-3">
              <h2 class="font-semibold text-gray-800">Semester 5  <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">1 mata kuliah</span></h2>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
              <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                  <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-book-open text-blue-500"></i> Kecerdasan Buatan
                  </h3>
                  <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">Semester 5</span>
                </div>
                <p class="text-sm text-gray-500 mb-1">IF205</p>
                <span class="inline-block bg-purple-100 text-purple-700 text-xs font-medium px-2 py-0.5 rounded-md">AI & Pembelajaran Mesin</span>
                <p class="text-sm text-gray-600 mt-2">3 SKS</p>
                <p class="text-s text-gray-400 mt-1 faa-study">Kategori : AI & Pembelajaran Mesin</p>
                <p class="text-xs text-gray-400 mt-1">Dibuat: 22/09/2025</p>
              </div>
            </div>
          </div>
           {{-- Semester 6 --}}
        <div>
            <div class="flex items-center justify-between mb-3">
              <h2 class="font-semibold text-gray-800">Semester 6  <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">1 mata kuliah</span></h2>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
              <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                  <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-book-open text-blue-500"></i> Kecerdasan Buatan
                  </h3>
                  <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">Semester 6</span>
                </div>
                <p class="text-sm text-gray-500 mb-1">IF205</p>
                <span class="inline-block bg-purple-100 text-purple-700 text-xs font-medium px-2 py-0.5 rounded-md">AI & Pembelajaran Mesin</span>
                <p class="text-sm text-gray-600 mt-2">3 SKS</p>
                <p class="text-s text-gray-400 mt-1 faa-study">Kategori : AI & Pembelajaran Mesin</p>
                <p class="text-xs text-gray-400 mt-1">Dibuat: 22/09/2025</p>
              </div>
            </div>
          </div>
      </section>
    
    </main>
    @endsection
    