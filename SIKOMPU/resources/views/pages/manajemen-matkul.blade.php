@extends('layouts.app')

@section('title', 'Manajemen MataKuliah')
@section('page_title', 'Manajemen Matakuliah')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }" @close-modal.window="openModal = false">

    {{-- Header --}}
    <div class="flex justify-between items-center border-b border-gray-300 pb-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Mata Kuliah</h1>
            <p class="text-gray-500 text-sm">Kelola data mata kuliah dan kategori kompetensi</p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center space-x-3">
            {{-- Tombol Refresh --}}
            <button 
                onclick="window.location.reload()" 
                class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition">
                <i class="fa-solid fa-rotate-right mr-1"></i> Refresh
            </button>

            {{-- Tombol Tambah Mata Kuliah --}}
            <button 
                @click="openModal = true"
                class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Mata Kuliah
            </button>
            
            <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
  
              <div class="fixed inset-0 bg-gray-900 bg-opacity-70 transition-opacity" @click="openModal = false"></div> 
              {{-- Saya ubah menjadi bg-gray-900 dan bg-opacity-70 agar sedikit lebih gelap dari sebelumnya, menyerupai gambar Dosen. --}}
            
              <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
                <div x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
            
                  <div class="bg-white px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800" id="modal-title">
                      Formulir Tambah Mata Kuliah
                    </h3>
                  </div>
            
                  <div class="bg-white px-6 pt-6 pb-4 sm:p-6">
                    <form action="#" method="POST" id="add-mk-form">
                      @csrf
                      <div class="space-y-4">
                        
                        {{-- Nama Mata Kuliah --}}
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                          <label for="nama_mk" class="block text-sm font-medium text-gray-700 sm:col-span-1">Nama Mata Kuliah</label>
                          <input type="text" name="nama_mk" id="nama_mk"
                                 class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm sm:col-span-2">
                        </div>
            
                        {{-- Kode Mata Kuliah --}}
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                          <label for="kode_mk" class="block text-sm font-medium text-gray-700 sm:col-span-1">Kode Mata Kuliah</label>
                          <input type="text" name="kode_mk" id="kode_mk"
                                 class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm sm:col-span-2">
                        </div>
            
                        {{-- Jumlah SKS --}}
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                          <label for="sks" class="block text-sm font-medium text-gray-700 sm:col-span-1">Jumlah SKS</label>
                          <input type="number" name="sks" id="sks" min="1"
                                 class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm sm:col-span-2">
                        </div>
                        
                        {{-- Jumlah Sesi --}}
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                          <label for="sesi" class="block text-sm font-medium text-gray-700 sm:col-span-1">Jumlah Sesi</label>
                          <input type="number" name="sesi" id="sesi" min="1"
                                 class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm sm:col-span-2">
                        </div>
            
                        {{-- Program Studi Pemilik --}}
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                          <label for="prodi_pemilik" class="block text-sm font-medium text-gray-700 sm:col-span-1">Program Studi Pemilik</label>
                          <input type="text" name="prodi_pemilik" id="prodi_pemilik"
                                 class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm sm:col-span-2">
                        </div>
            
                        {{-- Semester (Dropdown) --}}
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                          <label for="semester" class="block text-sm font-medium text-gray-700 sm:col-span-1">Semester</label>
                          <select name="semester" id="semester"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm sm:col-span-2">
                              <option>Ganjil / Genap</option>
                              <option>Ganjil</option>
                              <option>Genap</option>
                          </select>
                        </div>
                        
                      </div>
                    </form>
                  </div>
            
                  <div class="px-6 py-4 flex justify-end items-center space-x-4">
                    <button type="button" @click="openModal = false"
                      class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900" style="border: none; background: none;">
                      Batalkan
                    </button>
                    <button type="submit" form="add-mk-form"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                      <i class="fa-solid fa-book mr-2"></i> Simpan Mata Kuliah
                    </button>
                  </div>
            
                </div>
              </div>
            </div>
        </div>
    </div>
 {{-- Statistik ringkas --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="items-center p-6 bg-green-100 rounded-xl shadow-sm border-green-100 text-center rounded-lg hover:bg-gray-100  transition">
        <p class="text-gray-700 text-sm font-semibold">Total Mata Kuliah</p>
        <p class="text-3xl font-bold text-green-700 mt-1">8</p> 
        <p class="text-xs text-gray-500 mt-1">Mata kuliah terdaftar</p>
    </div>
    <div class="items-center p-6 bg-blue-100 rounded-xl shadow-sm border-blue-100 text-center rounded-lg hover:bg-gray-100  transition">
      <p class="text-gray-700 text-sm font-semibold">Total SKS</p>
      <p class="text-3xl font-bold text-blue-700 mt-1">18</p> 
      <p class="text-xs text-gray-500 mt-1">Satuan kredit semester</p>
    </div>
    <div class="items-center p-6 bg-purple-100 rounded-xl shadow-sm border-purple-100 text-center rounded-lg hover:bg-gray-100  transition">
        <p class="text-gray-700 text-sm font-semibold">Kategori Aktif</p>
        <p class="text-3xl font-bold text-purple-700 mt-1">5</p> 
        <p class="text-xs text-gray-500 mt-1">Kategori kompetensi</p>
    </div>
    <div class="items-center p-6 bg-orange-100 rounded-xl shadow-sm border-orange-100 text-center rounded-lg hover:bg-gray-100  transition">
      <p class="text-gray-700 text-sm font-semibold">Semester Aktif</p>
      <p class="text-3xl font-bold text-orange-700 mt-1">5</p> 
      <p class="text-xs text-gray-500 mt-1">Semester Tersedia</p>
  </div>
</div>
{{-- Daftar Mata Kuliah per Semester --}}
<section class="space-y-4">
  {{-- Semester 1 --}}
  <div>
      <div class="flex items-center justify-between mb-3">
          <h2 class="font-semibold text-gray-800">Semester 1  <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-md">2 mata kuliah</span></h2>
      </div>
      <div class="grid sm:grid-cols-2 gap-4">
          {{-- Card Mata Kuliah 1 --}}
          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm 
                      transition ease-in-out duration-300 
                      hover:scale-[1.02] hover:shadow-lg 
                      active:scale-[0.98] active:shadow-md">
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
  
          {{-- Card Mata Kuliah 2 --}}
          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm
                      transition ease-in-out duration-300 
                      hover:scale-[1.02] hover:shadow-lg 
                      active:scale-[0.98] active:shadow-md">
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
          {{-- Card Mata Kuliah 3 --}}
          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm
                      transition ease-in-out duration-300 
                      hover:scale-[1.02] hover:shadow-lg 
                      active:scale-[0.98] active:shadow-md">
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
  
          {{-- Card Mata Kuliah 4 --}}
          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm
                      transition ease-in-out duration-300 
                      hover:scale-[1.02] hover:shadow-lg 
                      active:scale-[0.98] active:shadow-md">
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
          {{-- Card Mata Kuliah 5 --}}
          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm
                      transition ease-in-out duration-300 
                      hover:scale-[1.02] hover:shadow-lg 
                      active:scale-[0.98] active:shadow-md">
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
  
          {{-- Card Mata Kuliah 6 --}}
          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm
                      transition ease-in-out duration-300 
                      hover:scale-[1.02] hover:shadow-lg 
                      active:scale-[0.98] active:shadow-md">
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
          {{-- Card Mata Kuliah 7 --}}
          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm
                      transition ease-in-out duration-300 
                      hover:scale-[1.02] hover:shadow-lg 
                      active:scale-[0.98] active:shadow-md">
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
          {{-- Card Mata Kuliah 8 --}}
          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm
                      transition ease-in-out duration-300 
                      hover:scale-[1.02] hover:shadow-lg 
                      active:scale-[0.98] active:shadow-md">
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
      {{-- Include Form Tambah Dosen --}}
      @include('components.tambah_matkul')
</main>
@endsection
    
