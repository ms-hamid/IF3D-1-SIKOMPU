@extends('layouts.app')

@section('title', 'Self-Assessment')

@section('content')
<main class="flex-1 px-3 sm:px-6 py-4 space-y-6">

  {{-- Filter dan Tombol --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div class="flex flex-wrap gap-2 items-center">
      <select class="border bg-white border-gray-300 rounded-md px-2 py-1 text-sm text-gray-700 focus:ring-1 focus:ring-indigo-500 min-w-[140px]">
        <option value="">Pilih Program Studi</option>
        <option value="if">Informatika</option>
        <option value="si">Sistem Informasi</option>
        <option value="ti">Teknologi Informasi</option>
      </select>

      <button class="flex items-center gap-1 border border-gray-300 px-2 py-1 rounded-md text-sm text-gray-700 hover:bg-gray-100 transition whitespace-nowrap">
        <i class="fa-solid fa-rotate-right"></i> Refresh
      </button>
    </div>

    <div class="flex flex-wrap gap-2">
      <button class="flex items-center gap-1 bg-green-600 text-white px-3 py-1.5 rounded-md hover:bg-green-700 transition text-sm whitespace-nowrap">
        <i class="fa-solid fa-file-import"></i> Import
      </button>
      <button class="flex items-center gap-1 bg-indigo-600 text-white px-3 py-1.5 rounded-md hover:bg-indigo-700 transition text-sm whitespace-nowrap">
        <i class="fa-solid fa-file-export"></i> Ekspor
      </button>
    </div>
  </div>

  {{-- Progress Penilaian --}}
  <div class="bg-green-50 border border-green-300 rounded-md p-4 text-sm">
    <h6 class="text-green-700 font-semibold mb-1 flex items-center gap-2 text-sm">
      <i class="fa-solid fa-square-check"></i> Penilaian Kemajuan
    </h6>
    <p class="text-gray-700 mb-1 text-sm">9 dari 38 mata kuliah selesai</p>

    <p class="mb-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-medium text-xs">
      Biarkan skala (Bintang) tetap 1 jika Anda merasa belum kompeten.
      <a href="{{ asset('pdf/skala-penilaian.pdf') }}" target="_blank" 
         class="underline font-semibold text-yellow-900 hover:text-yellow-700 ml-1">
        Lihat skala
      </a>
    </p>

    <div class="w-full bg-green-200 rounded-full h-2 mb-1">
      <div class="bg-green-600 h-2 rounded-full" style="width: 20%"></div>
    </div>
    <p class="text-right text-green-700 text-xs font-medium">20%</p>
  </div>

  {{-- Search Box --}}
  <div class="mb-3 flex bg-white justify-start sm:justify-end">
    <x-search-input placeholder="Cari mata kuliah..." width="w-full sm:w-1/4" x-model="search" class="text-sm" />
  </div>

  {{-- Grid Mata Kuliah --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ([
      ['nama' => 'Pengantar Proyek Perangkat Lunak', 'kode' => 'IF101'],
      ['nama' => 'Pengantar Teknologi Informasi', 'kode' => 'IF102'],
      ['nama' => 'Dasar Pemrograman Web', 'kode' => 'IF103'],
      ['nama' => 'Dasar Pemrograman', 'kode' => 'IF104'],
      ['nama' => 'Sistem Komputer', 'kode' => 'IF105'],
      ['nama' => 'Matematika', 'kode' => 'IF106'],
    ] as $m)
      <div x-data="{ rating: 1, hoverRating: 0 }" 
           class="bg-white rounded-md p-4 border border-gray-200 relative hover:shadow-sm transition text-sm">

        <div class="absolute top-2 right-2 bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-md font-semibold">
          Terisi
        </div>

        <h5 class="font-semibold text-gray-800 text-sm">{{ $m['nama'] }}</h5>
        <p class="text-gray-500 text-xs mb-2">{{ $m['kode'] }}</p>

        {{-- Rating bintang --}}
        <div class="flex items-center mb-2 flex-wrap">
          <template x-for="i in 8" :key="i">
            <i 
              class="fa-solid fa-star cursor-pointer text-gray-300 text-sm transition-colors"
              :class="(hoverRating || rating) >= i ? 'text-yellow-400' : 'text-gray-300'"
              @click="rating = i"
              @mouseover="hoverRating = i"
              @mouseleave="hoverRating = 0"
              x-text="''"
            ></i>
          </template>
          <span class="ml-2 text-xs text-gray-600" x-text="rating + ' / 8'"></span>
        </div>

        {{-- Catatan --}}
        <label class="block text-gray-500 text-xs mb-1">Catatan (Opsional)</label>
        <input type="text" 
               class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-indigo-500 outline-none" 
               placeholder="Tulis catatan...">
      </div>
    @endforeach
  </div>

  {{-- Tombol Submit --}}
  <div class="mt-4 flex justify-end">
    <x-submit-button label="Simpan Penilaian" color="bg-red-600" hover="hover:bg-red-700" class="text-sm px-4 py-2" />
  </div>

</main>

{{-- Alpine.js --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
