@extends('layouts.app')

@section('title', 'Self-Assessment')

@section('content')
<main class="flex-1 px-3 sm:px-6 py-4 space-y-6" x-data="selfAssessmentApp()">

  {{-- Alert Success --}}
  @if(session('success'))
  <div class="bg-green-50 border border-green-300 rounded-md p-4">
    <div class="flex items-center gap-2">
      <i class="fa-solid fa-circle-check text-green-600"></i>
      <span class="text-green-700 text-sm font-medium">{{ session('success') }}</span>
    </div>
  </div>
  @endif

  {{-- Alert Error --}}
  @if($errors->any())
  <div class="bg-red-50 border border-red-300 rounded-md p-4">
    <div class="flex items-start gap-2">
      <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
      <div>
        <p class="text-red-700 text-sm font-medium mb-2">Terjadi kesalahan:</p>
        <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
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

      <div class="flex flex-wrap gap-2">
        <a href="{{ route('self-assessment.index') }}" class="flex items-center gap-1 border border-gray-300 px-3 py-1.5 rounded-md text-sm text-gray-700 hover:bg-gray-100 transition whitespace-nowrap">
          <i class="fa-solid fa-rotate-right"></i> Refresh
        </a>
        @if(in_array(auth()->user()->jabatan, ['Kepala Jurusan', 'Sekretaris Jurusan', 'Kepala Program Studi']))
          <a href="{{ route('self-assessment.import.form') }}" 
            class="flex items-center gap-1 bg-green-600 text-white px-3 py-1.5 rounded-md hover:bg-green-700 transition text-sm whitespace-nowrap">
            <i class="fa-solid fa-file-import"></i> Import
          </a>
        @endif
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
    <p class="text-gray-700 mb-1 text-sm">
        <span class="font-semibold" x-text="countCompleted()"></span> dari 
        <span class="font-semibold">{{ $totalMatakuliah }}</span> mata kuliah selesai
    </p>

    <p class="mb-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-medium text-xs">
        Biarkan skala (Bintang) tetap 1 jika Anda merasa belum kompeten.
        <a href="{{ asset('pdf/skala-penilaian.pdf') }}" target="_blank" 
           class="underline font-semibold text-yellow-900 hover:text-yellow-700 ml-1">
            Lihat skala
        </a>
    </p>

    {{-- Bar Background --}}
    <div class="w-full bg-green-200 rounded-full h-2 mb-1 overflow-hidden">
        {{-- Bar Hijau (Isi) --}}
        <div class="bg-green-600 h-2 rounded-full transition-all duration-500" 
             :style="'width: ' + calculateProgress() + '%'">
        </div>
    </div>
    
    {{-- Angka Persen di Kanan --}}
    <p class="text-right text-green-700 text-xs font-medium" x-text="calculateProgress() + '%'"></p>
    </div>

  {{-- Form Submit --}}
  <form method="POST" action="{{ route('self-assessment.store') }}">
    @csrf
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @forelse($mataKuliahs as $index => $m)
        <div x-data="mataKuliahCard({{ $m->id }}, {{ $ratings[$m->id] ?? 0 }}, '{{ addslashes($assessments[$m->id] ?? '') }}')" 
             class="bg-white rounded-md p-4 border border-gray-200 relative hover:shadow-sm transition text-sm">

          {{-- BADGE STATUS --}}
          <div :class="displayRating > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                class="absolute top-2 right-2 text-xs px-2 py-0.5 rounded-md font-semibold">
            <span x-text="displayRating > 0 ? 'Terisi' : 'Belum Terisi'"></span>
          </div>

          <h5 class="font-semibold text-gray-800 text-sm">{{ $m->nama_mk }}</h5>
          <p class="text-gray-500 text-xs mb-3">{{ $m->kode_mk }} - {{ $m->prodi->nama_prodi }}</p>

          <div class="flex flex-col gap-2">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1">
                <template x-for="i in 8" :key="i">
                  <i @click="setRating(i)"
                     class="fa-solid fa-star text-lg transition-all cursor-pointer hover:scale-125"
                     :class="displayRating >= i ? 'text-yellow-400' : 'text-gray-300'"></i>
                </template>
              </div>
              
              <button type="button" @click="resetRating()" 
                      x-show="displayRating > 0"
                      class="text-xs text-red-500 hover:text-red-700 hover:underline transition">
                Reset
              </button>
            </div>

            <div class="flex items-center gap-2 w-full">
              <input type="range" min="1" max="8" x-model="displayRating" 
                     @input="dispatchUpdate()" 
                     class="w-full accent-green-600 cursor-pointer"/>
              
              {{-- INPUT HIDDEN: Menggunakan $index agar data tidak tertimpa --}}
              <input type="hidden" name="assessments[{{ $index }}][matakuliah_id]" value="{{ $m->id }}">
              <input type="hidden" name="assessments[{{ $index }}][nilai]" :value="displayRating">
              
              <span class="text-sm text-gray-600 font-medium whitespace-nowrap">
                <span x-text="displayRating"></span> / 8
              </span>
            </div>
          </div>

          <label class="block text-gray-500 text-xs mt-3 mb-1">Catatan (Opsional)</label>
          <input type="text" 
                 name="assessments[{{ $index }}][catatan]"
                 x-model="catatan"
                 class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-xs focus:ring-1 focus:ring-indigo-500 outline-none" 
                 placeholder="Tulis catatan...">
        </div>
      @empty
        <div class="col-span-3 text-center py-8">
          <i class="fa-solid fa-inbox text-gray-300 text-5xl mb-3"></i>
          <p class="text-gray-500">Belum ada data matakuliah.</p>
        </div>
      @endforelse
    </div>
      
    {{-- Pagination --}}
@if($mataKuliahs->hasPages())
<div class="mt-8 pt-6 border-t border-gray-200">
    <div class="flex flex-col items-center gap-4">
        {{-- Teks Menampilkan versi kamu tetap ada --}}
        <div class="text-sm text-gray-600">
            Menampilkan 
            <span class="font-semibold text-gray-800">{{ $mataKuliahs->firstItem() }}</span>
            sampai 
            <span class="font-semibold text-gray-800">{{ $mataKuliahs->lastItem() }}</span>
            dari 
            <span class="font-semibold text-gray-800">{{ $mataKuliahs->total() }}</span>
            mata kuliah
        </div>
        
        {{-- Navigasi Angka --}}
        <div class="flex items-center gap-1">
            {{-- Kita hanya mengambil elemen angka/panah saja --}}
            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                {{ $mataKuliahs->onEachSide(1)->links() }}
            </nav>
        </div>
    </div>
</div>
@endif
      
    <div class="mt-6 flex justify-end gap-3">
      <button type="submit" 
              class="bg-red-600 text-white hover:bg-red-700 text-sm px-4 py-2 rounded-lg font-medium transition shadow-md">
        <i class="fa-solid fa-save mr-2"></i> Simpan Data
      </button>
    </div>
  </form>
</main>

<script>
function selfAssessmentApp() {
    return {
        totalMK: {{ $totalMatakuliah }},
        // Inisialisasi daftar ID yang sudah terisi dari database
        completedIds: @json(array_keys(array_filter($ratings))),

        init() {
            window.addEventListener('rating-changed', (e) => {
                const { id, val } = e.detail;
                if (val > 0) {
                    if (!this.completedIds.includes(id)) this.completedIds.push(id);
                } else {
                    this.completedIds = this.completedIds.filter(item => item !== id);
                }
            });
        },

        countCompleted() {
            return this.completedIds.length;
        },

        calculateProgress() {
            return this.totalMK > 0 ? Math.round((this.completedIds.length / this.totalMK) * 100) : 0;
        }
    }
}

function mataKuliahCard(id, initialRating, initialCatatan) {
    return {
        matakuliahId: id,
        displayRating: initialRating,
        catatan: initialCatatan,

        setRating(rating) {
            this.displayRating = rating;
            this.dispatchUpdate();
        },

        resetRating() {
            this.displayRating = 0;
            this.catatan = '';
            this.dispatchUpdate();
        },

        dispatchUpdate() {
            window.dispatchEvent(new CustomEvent('rating-changed', {
                detail: { id: this.matakuliahId, val: this.displayRating }
            }));
        }
    }
}
</script>

<style>
    /* Menghilangkan teks "Showing X to Y..." bawaan Laravel Pagination */
    nav[role="navigation"] p.text-sm.text-gray-700.leading-5 {
        display: none !important;
    }
    /* Menyesuaikan container pagination agar lebih bersih */
    nav[role="navigation"] div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between {
        justify-content: center !important;
    }
</style>
@endsection