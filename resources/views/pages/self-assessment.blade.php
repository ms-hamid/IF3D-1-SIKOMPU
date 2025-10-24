@extends('layouts.app')

@section('title', 'Self-Assessment')
@section('page_title', 'Self-Assessment')

@section('content')
<div class="space-y-6">

  {{-- Filter dan tombol --}}
  <div class="flex flex-wrap justify-between items-center gap-3">
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
    <div class="flex gap-3">
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
  <div class="bg-green-50 border border-green-300 rounded-lg p-4">
    <h6 class="text-green-700 font-semibold mb-2 flex items-center gap-2">
      <i class="fa-solid fa-square-check"></i> Penilaian Kemajuan
    </h6>
    <p class="text-gray-700 text-sm mb-3">9 dari 38 mata kuliah selesai</p>
    <div class="w-full bg-green-200 rounded-full h-3 mb-1">
      <div class="bg-green-600 h-3 rounded-full" style="width: 20%"></div>
    </div>
    <p class="text-right text-green-700 text-sm font-medium">20%</p>
  </div>

  {{-- Grid Mata Kuliah --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    
    @foreach ([
      ['nama' => 'Pengantar Proyek Perangkat Lunak', 'kode' => 'IF101', 'rating' => 5],
      ['nama' => 'Pengantar Teknologi Informasi', 'kode' => 'IF102', 'rating' => 4],
      ['nama' => 'Dasar Pemrograman Web', 'kode' => 'IF103', 'rating' => 4],
      ['nama' => 'Dasar Pemrograman', 'kode' => 'IF104', 'rating' => 2],
      ['nama' => 'Sistem Komputer', 'kode' => 'IF105', 'rating' => 5],
      ['nama' => 'Matematika', 'kode' => 'IF106', 'rating' => 5],
    ] as $m)
      <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200 relative">
        <div class="absolute top-2 right-2 bg-green-100 text-green-700 text-xs px-2 py-1 rounded-lg font-semibold">
          Sudah di isi
        </div>

        <h5 class="font-semibold text-gray-800">{{ $m['nama'] }}</h5>
        <p class="text-gray-500 text-sm mb-3">{{ $m['kode'] }}</p>

        {{-- Rating bintang --}}
        <div class="flex items-center mb-3">
          @for ($i = 1; $i <= 5; $i++)
            <i class="fa-solid fa-star {{ $i <= $m['rating'] ? 'text-yellow-400' : 'text-gray-300' }}"></i>
          @endfor
        </div>

        {{-- Catatan --}}
        <label class="block text-gray-500 text-sm mb-1">Catatan (Opsional)</label>
        <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Tulis catatan...">
      </div>
    @endforeach

  </div>

</div>
@endsection
