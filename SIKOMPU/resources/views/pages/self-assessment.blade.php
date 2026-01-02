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

  {{-- Auto-Save Indicator --}}
  <div x-show="autoSaveStatus !== ''" 
       x-transition
       class="fixed top-20 right-4 z-40 px-4 py-2 rounded-lg shadow-lg text-sm font-medium"
       :class="{
         'bg-blue-100 text-blue-700': autoSaveStatus === 'saving',
         'bg-green-100 text-green-700': autoSaveStatus === 'saved',
         'bg-red-100 text-red-700': autoSaveStatus === 'error'
       }">
    <div class="flex items-center gap-2">
      <template x-if="autoSaveStatus === 'saving'">
        <div>
          <i class="fa-solid fa-spinner fa-spin"></i>
          <span>Menyimpan sementara...</span>
        </div>
      </template>
      <template x-if="autoSaveStatus === 'saved'">
        <div>
          <i class="fa-solid fa-check-circle"></i>
          <span>Tersimpan sementara</span>
        </div>
      </template>
      <template x-if="autoSaveStatus === 'error'">
        <div>
          <i class="fa-solid fa-exclamation-circle"></i>
          <span>Gagal menyimpan</span>
        </div>
      </template>
    </div>
  </div>
  
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
      <span x-text="calculateProgress().completed"></span> dari 
      <span x-text="calculateProgress().total"></span> mata kuliah selesai
    </p>

    <p class="mb-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-medium text-xs">
      Biarkan skala (Bintang) tetap 1 jika Anda merasa belum kompeten.
      <a href="{{ asset('pdf/skala-penilaian.pdf') }}" target="_blank" 
         class="underline font-semibold text-yellow-900 hover:text-yellow-700 ml-1">
        Lihat skala
      </a>
    </p>

    <div class="w-full bg-green-200 rounded-full h-2 mb-1">
      <div class="bg-green-600 h-2 rounded-full transition-all duration-300" 
           :style="`width: ${calculateProgress().percentage}%`"></div>
    </div>
    <p class="text-right text-green-700 text-xs font-medium" x-text="`${calculateProgress().percentage}%`"></p>
  </div>

  {{-- Form Submit --}}
  <form method="POST" action="{{ route('self-assessment.store') }}" @submit="handleSubmit">
    @csrf
    
    {{-- Grid Mata Kuliah --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @forelse($mataKuliahs as $index => $m)
        {{-- Alpine Data --}}
        <div x-data="mataKuliahCard({{ $m->id }}, {{ $ratings[$m->id] ?? 0 }}, '{{ $assessments[$m->id] ?? '' }}')" 
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
                <template x-for="i in 8" :key="i">
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
              <input type="range" min="1" max="8" x-model="sliderValue" 
                     @input="updateRating()" 
                     class="w-full accent-green-600 cursor-pointer"
                     x-bind:style="{ backgroundSize: ((sliderValue - 1) / 7) * 100 + '% 100%' }"/>
              
              {{-- Hidden input untuk submit --}}
              <input type="hidden" :name="`assessments[${cardIndex}][matakuliah_id]`" :value="matakuliahId">
              <input type="hidden" :name="`assessments[${cardIndex}][nilai]`" :value="displayRating">
              
              <span class="text-sm text-gray-600 font-medium whitespace-nowrap">
                <span x-text="displayRating"></span> / 8
              </span>
            </div>
          </div>

          {{-- Catatan --}}
          <label class="block text-gray-500 text-xs mt-3 mb-1">Catatan (Opsional)</label>
          <input type="text" 
                 :name="`assessments[${cardIndex}][catatan]`"
                 x-model="catatan"
                 @input="updateCatatan()"
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
      
    {{-- Custom Pagination --}}
    @if($mataKuliahs->hasPages())
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="flex flex-col items-center gap-4">
            {{-- Info Text --}}
            <div class="text-sm text-gray-600">
                Menampilkan 
                <span class="font-semibold text-gray-800">{{ $mataKuliahs->firstItem() }}</span>
                sampai 
                <span class="font-semibold text-gray-800">{{ $mataKuliahs->lastItem() }}</span>
                dari 
                <span class="font-semibold text-gray-800">{{ $mataKuliahs->total() }}</span>
                mata kuliah
            </div>

            {{-- Tombol Pagination --}}
            <div class="flex items-center gap-1">
                @if ($mataKuliahs->onFirstPage())
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $mataKuliahs->previousPageUrl() }}" 
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                @endif

                @php
                    $currentPage = $mataKuliahs->currentPage();
                    $lastPage = $mataKuliahs->lastPage();
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($lastPage, $currentPage + 2);
                @endphp

                @if($startPage > 1)
                    <a href="{{ $mataKuliahs->url(1) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        1
                    </a>
                    @if($startPage > 2)
                        <span class="px-3 py-2 text-sm text-gray-500">...</span>
                    @endif
                @endif

                @for($page = $startPage; $page <= $endPage; $page++)
                    @if ($page == $currentPage)
                        <span class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $mataKuliahs->url($page) }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                @if($endPage < $lastPage)
                    @if($endPage < $lastPage - 1)
                        <span class="px-3 py-2 text-sm text-gray-500">...</span>
                    @endif
                    <a href="{{ $mataKuliahs->url($lastPage) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        {{ $lastPage }}
                    </a>
                @endif

                @if ($mataKuliahs->hasMorePages())
                    <a href="{{ $mataKuliahs->nextPageUrl() }}" 
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                @else
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif
      
    {{-- Tombol Submit --}}
    <div class="mt-6 flex justify-end gap-3">
      <button type="button" 
              @click="clearAllData()"
              class="bg-gray-500 text-white hover:bg-gray-600 text-sm px-4 py-2 rounded-lg font-medium transition shadow-md">
        <i class="fa-solid fa-trash mr-2"></i> Hapus Data Sementara
      </button>
      <button type="submit" 
              class="bg-red-600 text-white hover:bg-red-700 text-sm px-4 py-2 rounded-lg font-medium transition shadow-md">
        <i class="fa-solid fa-save mr-2"></i> Simpan ke Database
      </button>
    </div>
  </form>

</main>

{{-- Alpine.js Script --}}
<script>
// Global store untuk localStorage
window.assessmentStore = {
    data: {},
    
    init() {
        const saved = localStorage.getItem('selfAssessmentDraft');
        if (saved) {
            try {
                this.data = JSON.parse(saved);
            } catch (e) {
                console.error('Error loading data:', e);
                this.data = {};
            }
        }
    },
    
    save() {
        try {
            localStorage.setItem('selfAssessmentDraft', JSON.stringify(this.data));
            return true;
        } catch (e) {
            console.error('Error saving data:', e);
            return false;
        }
    },
    
    update(matakuliahId, nilai, catatan) {
        this.data[matakuliahId] = { nilai: parseInt(nilai) || 0, catatan: catatan || '' };
        this.save();
    },
    
    get(matakuliahId) {
        return this.data[matakuliahId] || null;
    },
    
    clear() {
        this.data = {};
        localStorage.removeItem('selfAssessmentDraft');
    },
    
    getProgress(totalMatakuliah) {
        const allData = Object.values(this.data);
        const completed = allData.filter(item => item.nilai > 0).length;
        const total = totalMatakuliah || Object.keys(this.data).length;
        const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
        return { completed, total, percentage };
    }
};

// Initialize store
window.assessmentStore.init();

function selfAssessmentApp() {
    return {
        autoSaveStatus: '',
        autoSaveTimeout: null,
        totalMatakuliah: {{ $mataKuliahs->total() }},

        init() {
            // Watch for auto-save status changes
            this.$watch('autoSaveStatus', (value) => {
                if (value === 'saved' || value === 'error') {
                    setTimeout(() => {
                        this.autoSaveStatus = '';
                    }, 2000);
                }
            });
            
            // Trigger initial progress calculation
            this.$nextTick(() => {
                this.calculateProgress();
            });
        },

        showAutoSave(status) {
            this.autoSaveStatus = status;
            clearTimeout(this.autoSaveTimeout);
            
            if (status === 'saving') {
                this.autoSaveTimeout = setTimeout(() => {
                    this.autoSaveStatus = 'saved';
                }, 500);
            }
        },

        calculateProgress() {
            return window.assessmentStore.getProgress(this.totalMatakuliah);
        },

        clearAllData() {
            if (confirm('Yakin ingin menghapus semua data sementara? Data ini belum tersimpan ke database.')) {
                window.assessmentStore.clear();
                location.reload();
            }
        },

        handleSubmit(e) {
            // Clear localStorage after successful submit
            window.assessmentStore.clear();
        }
    }
}

function mataKuliahCard(matakuliahId, initialRating, initialCatatan) {
    return {
        matakuliahId: matakuliahId,
        cardIndex: 0,
        displayRating: 0,
        sliderValue: 1,
        catatan: '',

        init() {
            // Load dari localStorage dulu (prioritas tertinggi)
            const saved = window.assessmentStore.get(this.matakuliahId);
            
            if (saved && saved.nilai > 0) {
                // Ada data di localStorage
                this.displayRating = saved.nilai;
                this.sliderValue = saved.nilai;
                this.catatan = saved.catatan || '';
            } else if (initialRating > 0) {
                // Fallback ke data dari database
                this.displayRating = initialRating;
                this.sliderValue = initialRating;
                this.catatan = initialCatatan || '';
            } else {
                // Default
                this.displayRating = 0;
                this.sliderValue = 1;
                this.catatan = '';
            }
        },

        setRating(rating) {
            this.displayRating = rating;
            this.sliderValue = rating;
            this.save();
        },

        updateRating() {
            this.displayRating = parseInt(this.sliderValue);
            this.save();
        },

        updateCatatan() {
            this.save();
        },

        resetRating() {
            this.displayRating = 0;
            this.sliderValue = 1;
            this.catatan = '';
            this.save();
        },

        save() {
            // Save ke localStorage
            window.assessmentStore.update(this.matakuliahId, this.displayRating, this.catatan);
            
            // Trigger auto-save indicator di parent
            const appElement = document.querySelector('[x-data*="selfAssessmentApp"]');
            if (appElement && appElement.__x) {
                const app = appElement.__x.$data;
                if (app.showAutoSave) {
                    app.showAutoSave('saving');
                }
            }
        }
    }
}
</script>

{{-- Fix Topbar Overlap --}}
<style>
.sticky {
    z-index: 50;
}
</style>

@endsection