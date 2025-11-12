@extends('layouts.app')

@section('title', 'Self-Assessment')

@section('content')
<main class="flex-1 px-3 sm:px-6 py-4 space-y-6">

  {{-- Filter dan Tombol --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div class="flex flex-wrap gap-2 items-center">
      <select class="border bg-white border-gray-300 rounded-md px-2 py-1 text-sm text-gray-700 focus:ring-1 focus:ring-indigo-500 min-w-[140px]">
        <option>Semua Prodi</option>
        <option>Teknik Informatika</option>
        <option>Teknik Geomatika</option>
        <option>Teknologi Rekayasa Multimedia</option>
        <option>Animasi</option>
        <option>Rekayasa Keamanan Siber</option>
        <option>Teknik Rekayasa Perangkat Lunak</option>
        <option>Teknologi Permainan</option>
        <option>S2 Magister Terapan Teknik Komputer</option>
      </select>
    </div>

    {{-- KELOMPOK TOMBOL (Refresh dipindah ke sini) --}}
    <div class="flex flex-wrap gap-2">
      <button class="flex items-center gap-1 border border-gray-300 px-3 py-1.5 rounded-md text-sm text-gray-700 hover:bg-gray-100 transition whitespace-nowrap">
        <i class="fa-solid fa-rotate-right"></i> Refresh
      </button>
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

  {{-- Grid Mata Kuliah --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ([
      ['nama' => 'Pengantar Proyek Perangkat Lunak', 'kode' => 'IF101', 'initial_rating' => 5],
      ['nama' => 'Pengantar Teknologi Informasi', 'kode' => 'IF102', 'initial_rating' => 0], // BELUM TERISI (0)
      ['nama' => 'Dasar Pemrograman Web', 'kode' => 'IF103', 'initial_rating' => 3], 
      ['nama' => 'Dasar Pemrograman', 'kode' => 'IF104', 'initial_rating' => 0], // BELUM TERISI (0)
      ['nama' => 'Sistem Komputer', 'kode' => 'IF105', 'initial_rating' => 7], 
      ['nama' => 'Matematika', 'kode' => 'IF106', 'initial_rating' => 0], // BELUM TERISI (0)
      ['nama' => 'Pendidikan Pancasila', 'kode' => 'PK21F', 'initial_rating' => 4],
      ['nama' => 'Proyek Pembuatan Prototipe', 'kode' => 'IF207', 'initial_rating' => 8],
      ['nama' => 'Dasar Rekayasa Perangkat Lunak', 'kode' => 'IF208', 'initial_rating' => 2], 
    ] as $m)
      
      {{-- Alpine Data: Menggunakan 2 variabel --}}
      <div x-data="{ 
            displayRating: {{ $m['initial_rating'] }}, 
            max_rating: 8,
            // sliderValue: Jika 0, pakai 1 (min slider). Kalau tidak, pakai rating asli.
            sliderValue: {{ $m['initial_rating'] == 0 ? 1 : $m['initial_rating'] }}
          }" 
           class="bg-white rounded-md p-4 border border-gray-200 relative hover:shadow-sm transition text-sm">

        {{-- BADGE STATUS: Logic berdasarkan displayRating > 0 --}}
        <div :class="displayRating > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
             class="absolute top-2 right-2 text-xs px-2 py-0.5 rounded-md font-semibold">
          <span x-text="displayRating > 0 ? 'Terisi' : 'Belum Terisi'"></span>
        </div>

        <h5 class="font-semibold text-gray-800 text-sm">{{ $m['nama'] }}</h5>
        <p class="text-gray-500 text-xs mb-3">{{ $m['kode'] }}</p>

        {{-- Bintang & Slider --}}
        <div class="flex flex-col gap-2">
            
            {{-- Bintang (Abu-abu semua jika displayRating = 0) --}}
            <div class="flex items-center">
                <template x-for="i in max_rating" :key="i">
                    <i 
                        class="fa-solid fa-star text-sm transition-colors"
                        :class="displayRating > 0 && displayRating >= i ? 'text-yellow-400' : 'text-gray-300'"
                    ></i>
                </template>
            </div>

            <div class="flex items-center gap-2 w-full">
                {{-- Slider (Sumber utama rating) --}}
                <input 
                    type="range" 
                    :min="1" 
                    :max="max_rating" 
                    x-model="sliderValue" 
                    
                    {{-- PENTING: Update displayRating. Jika sliderValue kembali ke 1, set displayRating ke 0. --}}
                    @input="displayRating = (sliderValue == 1 && displayRating > 0) ? 0 : sliderValue" 

                    class="w-full accent-green-600 cursor-pointer"
                    x-bind:style="{ backgroundSize: ((sliderValue - 1) / (max_rating - 1)) * 100 + '% 100%' }"
                />
                
                {{-- Nilai Rating dan Max Rating --}}
                <span class="text-sm text-gray-600 font-medium whitespace-nowrap">
                    <span x-text="displayRating"></span> / <span x-text="max_rating"></span>
                </span>

                {{-- Tombol Reset Dihilangkan Sesuai Permintaan --}}
            </div>
        </div>

        {{-- Catatan --}}
        <label class="block text-gray-500 text-xs mt-3 mb-1">Catatan (Opsional)</label>
        <input type="text" 
               class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-xs focus:ring-1 focus:ring-indigo-500 outline-none" 
               placeholder="Tulis catatan...">
      </div>
    @endforeach
  </div>
    
  {{-- Pagination --}}
  <div class="flex justify-center mt-8">
    <nav class="inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
        <a href="#" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">&lt;</a>
        <a href="#" class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-gray-300">1</a>
        <a href="#" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">2</a>
        <a href="#" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">3</a>
        <a href="#" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">&gt;</a>
    </nav>
  </div>
    
  {{-- Tombol Submit --}}
  <div class="mt-4 flex justify-end">
    <button class="bg-red-600 text-white hover:bg-red-700 text-sm px-4 py-2 rounded-lg font-medium transition shadow-md">
        Simpan Penilaian
    </button>
  </div>

</main>

{{-- Alpine.js --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
