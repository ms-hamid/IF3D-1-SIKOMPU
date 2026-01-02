<form method="GET" 
        action="{{ route('hasil.rekomendasi') }}"
      class="no-loading bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-6 space-y-4">

    <div class="flex items-center justify-between">
        <h4 class="text-lg font-extrabold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18v2l-7 7v6l-4 2v-8L3 6V4z"/>
            </svg>
            Filter & Pencarian
        </h4>
        @if(request()->anyFilled(['q', 'prodi', 'semester']))
            <a href="{{ route('hasil.rekomendasi') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 uppercase tracking-wider">
                Reset Filter
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
        
        {{-- INPUT PENCARIAN (Lebih Lebar) --}}
        <div class="md:col-span-5 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" name="q" value="{{ request('q') }}"
                placeholder="Cari mata kuliah atau koordinator..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all shadow-sm">
        </div>

        {{-- PROGRAM STUDI --}}
        <div class="md:col-span-3">
            <select name="prodi" class="w-full border border-gray-200 rounded-xl py-2.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700 bg-white shadow-sm transition-all">
                <option value="">Semua Program Studi</option>
                @foreach($listProdi as $prodi)
                    <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>
                        {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- SEMESTER --}}
        <div class="md:col-span-2">
            <select name="semester"
                class="w-full border border-gray-200 rounded-xl py-2.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700 bg-white shadow-sm transition-all">
                <option value="">Semua Semester</option>
                <option value="Ganjil" {{ request('semester')=='Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ request('semester')=='Genap' ? 'selected' : '' }}>Genap</option>
            </select>
        </div>

        {{-- BUTTON --}}
        <div class="md:col-span-2">
            <button type="submit"
                class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-md active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18v2l-7 7v6l-4 2v-8L3 6V4z"/>
                </svg>
                Terapkan
            </button>
        </div>

    </div>
</form>