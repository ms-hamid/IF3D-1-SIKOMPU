@extends('layouts.app')

@section('title', 'Self-Assessment')

@section('content')
<main class="flex-1 px-3 sm:px-6 py-4 space-y-6">

  {{-- Alert Success --}}
  @if(session('success'))
  <div class="bg-green-50 border border-green-300 rounded-md p-4">
    <div class="flex items-center gap-2">
      <i class="fa-solid fa-circle-check text-green-600"></i>
      <span class="text-green-700 text-sm font-medium">{{ session('success') }}</span>
    </div>
  </div>
  @endif

  {{-- Filter dan Tombol --}}
  <form method="GET" action="{{ route('self-assessment.index') }}">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div class="flex flex-wrap gap-2 items-center">
        <select name="prodi_id" onchange="this.form.submit()" class="border bg-white border-gray-300 rounded-md px-2 py-1 text-sm text-gray-700 focus:ring-1 focus:ring-indigo-500 min-w-[140px]">
          <option value="">Semua Prodi</option>
          @foreach($prodis as $prodi)
            <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
              {{ $prodi->nama_prodi }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- KELOMPOK TOMBOL --}}
      <div class="flex flex-wrap gap-2">
        <a href="{{ route('self-assessment.index') }}" class="flex items-center gap-1 border border-gray-300 px-3 py-1.5 rounded-md text-sm text-gray-700 hover:bg-gray-100 transition whitespace-nowrap">
          <i class="fa-solid fa-rotate-right"></i> Refresh
        </a>
        @can('admin') {{-- Hanya admin yang bisa import --}}
        <a href="{{ route('self-assessment.import.form') }}" class="flex items-center gap-1 bg-green-600 text-white px-3 py-1.5 rounded-md hover:bg-green-700 transition text-sm whitespace-nowrap">
          <i class="fa-solid fa-file-import"></i> Import
        </a>
        @endcan
        <button class="flex items-center gap-1 bg-indigo-600 text-white px-3 py-1.5 rounded-md hover:bg-indigo-700 transition text-sm whitespace-nowrap">
          <i class="fa-solid fa-file-export"></i> Ekspor
        </button>
      </div>
    </div>
  </form>

  {{-- Progress Penilaian --}}
  <div class="bg-green-50 border border-green-300 rounded-md p-4 text-sm">
    <h6 class="text-green-700 font-semibold mb-1 flex items-center gap-2 text-sm">
      <i class="fa-solid fa-square-check"></i> Penilaian Kemajuan
    </h6>
    <p class="text-gray-700 mb-1 text-sm">{{ $selesai }} dari {{ $totalMatakuliah }} mata kuliah selesai</p>

    <p class="mb-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-medium text-xs">
      Biarkan skala (Bintang) tetap 1 jika Anda merasa belum kompeten.
      <a href="{{ asset('pdf/skala-penilaian.pdf') }}" target="_blank" 
         class="underline font-semibold text-yellow-900 hover:text-yellow-700 ml-1">
        Lihat skala
      </a>
    </p>

    <div class="w-full bg-green-200 rounded-full h-2 mb-1">
      <div class="bg-green-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
    </div>
    <p class="text-right text-green-700 text-xs font-medium">{{ $progress }}%</p>
  </div>

  {{-- Form Submit --}}
  <form method="POST" action="{{ route('self-assessment.store') }}">
    @csrf
    
    {{-- Grid Mata Kuliah --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @forelse($mataKuliahs as $index => $m)
        {{-- Alpine Data --}}
        <div x-data="{ 
              displayRating: {{ $ratings[$m->id] ?? 0 }}, 
              max_rating: 8,
              sliderValue: {{ ($ratings[$m->id] ?? 0) == 0 ? 1 : ($ratings[$m->id] ?? 0) }},
              setRating(rating) {
                this.displayRating = rating;
                this.sliderValue = rating;
              },
              resetRating() {
                this.displayRating = 0;
                this.sliderValue = 1;
              }
            }" 
             class="bg-white rounded-md p-4 border border-gray-200 relative hover:shadow-sm transition text-sm">

          {{-- BADGE STATUS --}}
          <div :class="displayRating > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
               class="absolute top-2 right-2 text-xs px-2 py-0.5 rounded-md font-semibold">
            <span x-text="displayRating > 0 ? 'Terisi' : 'Belum Terisi'"></span>
          </div>

          <h5 class="font-semibold text-gray-800 text-sm">{{ $m->nama_mk }}</h5>
          <p class="text-gray-500 text-xs mb-3">{{ $m->kode_mk }} - {{ $m->prodi->nama_prodi }}</p>

          {{-- Bintang & Slider --}}
          <div class="flex flex-col gap-2">
              
            {{-- Bintang (BISA DIKLIK!) --}}
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1">
                <template x-for="i in max_rating" :key="i">
                  <i @click="setRating(i)"
                     class="fa-solid fa-star text-lg transition-all cursor-pointer hover:scale-125"
                     :class="displayRating >= i ? 'text-yellow-400' : 'text-gray-300'"></i>
                </template>
              </div>
              
              {{-- Tombol Reset --}}
              <button type="button" @click="resetRating()" 
                      x-show="displayRating > 0"
                      class="text-xs text-red-500 hover:text-red-700 hover:underline transition">
                Reset
              </button>
            </div>

            <div class="flex items-center gap-2 w-full">
              {{-- Slider --}}
              <input type="range" :min="1" :max="max_rating" x-model="sliderValue" 
                     @input="displayRating = sliderValue" 
                     class="w-full accent-green-600 cursor-pointer"
                     x-bind:style="{ backgroundSize: ((sliderValue - 1) / (max_rating - 1)) * 100 + '% 100%' }"/>
              
              {{-- Hidden input untuk submit --}}
              <input type="hidden" name="assessments[{{ $index }}][matakuliah_id]" value="{{ $m->id }}">
              <input type="hidden" name="assessments[{{ $index }}][nilai]" :value="displayRating">
              
              <span class="text-sm text-gray-600 font-medium whitespace-nowrap">
                <span x-text="displayRating"></span> / <span x-text="max_rating"></span>
              </span>
            </div>
          </div>

          {{-- Catatan --}}
          <label class="block text-gray-500 text-xs mt-3 mb-1">Catatan (Opsional)</label>
          <input type="text" name="assessments[{{ $index }}][catatan]" 
                 value="{{ $assessments[$m->id] ?? '' }}"
                 class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-xs focus:ring-1 focus:ring-indigo-500 outline-none" 
                 placeholder="Tulis catatan...">
        </div>
      @empty
        <div class="col-span-3 text-center py-8">
          <i class="fa-solid fa-inbox text-gray-300 text-5xl mb-3"></i>
          <p class="text-gray-500">Belum ada data matakuliah. Silakan import data terlebih dahulu.</p>
        </div>
      @endforelse
    </div>
      
    {{-- Pagination --}}
    <div class="flex justify-center mt-8">
      {{ $mataKuliahs->links() }}
    </div>
      
    {{-- Tombol Submit --}}
    <div class="mt-4 flex justify-end">
      <button type="submit" class="bg-red-600 text-white hover:bg-red-700 text-sm px-4 py-2 rounded-lg font-medium transition shadow-md">
        <i class="fa-solid fa-save mr-2"></i> Simpan Penilaian
      </button>
    </div>
  </form>

</main>

{{-- Alpine.js --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection