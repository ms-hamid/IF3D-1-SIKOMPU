@extends('layouts.app')

@section('title', 'Self-Assessment')
@section('page_title', 'Self-Assessment')

@section('content')
<main class="flex-1 p-6 space-y-8">

  {{-- Filter dan Tombol --}}
  <div class="flex flex-wrap justify-between items-center gap-4">
    {{-- Dropdown Program Studi --}}
    <div>
      <select class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-indigo-500">
        <option value="">Pilih Program Studi</option>
        <option value="if">Informatika</option>
        <option value="si">Sistem Informasi</option>
        <option value="ti">Teknologi Informasi</option>
      </select>
    </div>

    {{-- Tombol Import dan Ekspor --}}
    <div class="flex flex-wrap gap-3">
      <button class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
        <i class="fa-solid fa-file-import"></i> Import dari Excel
      </button>
      <button class="flex items-center gap-2 bg-indigo-900 text-white px-4 py-2 rounded-lg hover:bg-indigo-800 transition">
        <i class="fa-solid fa-file-export"></i> Ekspor Template Excel
      </button>
      <button class="flex items-center gap-2 border border-gray-300 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
        <i class="fa-solid fa-rotate-right"></i> Refresh
      </button>
    </div>
  </div>

  {{-- Progress Penilaian --}}
  <div class="bg-green-50 border border-green-300 rounded-lg p-5">
      <h6 class="text-green-700 font-semibold mb-2 flex items-center gap-2">
        <i class="fa-solid fa-square-check"></i> Penilaian Kemajuan
      </h6>
      <p class="text-gray-700 text-sm mb-1">9 dari 38 mata kuliah selesai</p>

      {{-- Teks pemberitahuan dengan highlight --}}
      <p class="text-sm mb-3 bg-yellow-100 text-yellow-800 px-3 py-2 rounded-md font-medium">
        Biarkan skala (Bintang) tetap 1 jika Anda merasa belum kompeten di mata kuliah tersebut. 
        <a href="{{ asset('pdf/skala-penilaian.pdf') }}" target="_blank" 
          class="underline font-semibold text-yellow-900 hover:text-yellow-700 ml-1">
          Lihat skala penilaian
        </a>
      </p>

      <div class="w-full bg-green-200 rounded-full h-3 mb-1">
        <div class="bg-green-600 h-3 rounded-full" style="width: 20%"></div>
      </div>
      <p class="text-right text-green-700 text-sm font-medium">20%</p>
  </div>


  {{-- Search Box Compact --}}
  <div class="mb-4 flex items-center">
      <x-search-input placeholder="Cari mata kuliah..." width="w-1/4" x-model="search" />
  </div>


  {{-- Grid Mata Kuliah --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ([
      ['nama' => 'Pengantar Proyek Perangkat Lunak', 'kode' => 'IF101'],
      ['nama' => 'Pengantar Teknologi Informasi', 'kode' => 'IF102'],
      ['nama' => 'Dasar Pemrograman Web', 'kode' => 'IF103'],
      ['nama' => 'Dasar Pemrograman', 'kode' => 'IF104'],
      ['nama' => 'Sistem Komputer', 'kode' => 'IF105'],
      ['nama' => 'Matematika', 'kode' => 'IF106'],
    ] as $m)
      <div x-data="{ rating: 1, hoverRating: 0 }" 
           class="bg-white rounded-xl p-5 border border-gray-200 relative hover:shadow-lg transition">

        <div class="absolute top-2 right-2 bg-green-100 text-green-700 text-xs px-2 py-1 rounded-lg font-semibold">
          Terisi
        </div>

        <h5 class="font-semibold text-gray-800">{{ $m['nama'] }}</h5>
        <p class="text-gray-500 text-sm mb-3">{{ $m['kode'] }}</p>

        {{-- Rating bintang 1-8 --}}
        <div class="flex items-center mb-3">
          <template x-for="i in 8" :key="i">
            <i 
              class="fa-solid fa-star cursor-pointer text-gray-300 transition-colors"
              :class="(hoverRating || rating) >= i ? 'text-yellow-400' : 'text-gray-300'"
              @click="rating = i"
              @mouseover="hoverRating = i"
              @mouseleave="hoverRating = 0"
              x-text="''"
            ></i>
          </template>
          <span class="ml-3 text-sm text-gray-600" x-text="rating + ' / 8'"></span>
        </div>

        {{-- Catatan --}}
        <label class="block text-gray-500 text-sm mb-1">Catatan (Opsional)</label>
        <input type="text" 
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" 
               placeholder="Tulis catatan...">
      </div>
    @endforeach
  </div>


  {{-- Tombol Submit --}}
  <div class="mt-6 flex justify-end">
      <x-submit-button label="Simpan Penilaian" color="bg-red-600" hover="hover:bg-red-700" />
  </div>


</main>

{{-- Alpine.js --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
