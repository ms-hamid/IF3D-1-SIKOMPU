<form method="GET" action="{{ route('hasil.rekomendasi') }}"
      class="bg-white p-6  rounded-xl border border-gray-300 mt-6">

    <h4 class="text-xl font-bold text-gray-800 mb-4">Filter & Pencarian</h4>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">

      
        <div class="md:col-span-2">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari mata kuliah atau koordinator..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg
                       focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- PROGRAM STUDI --}}
        <select name="prodi"
            class="w-full border border-gray-300 rounded-lg py-2 px-4
                   focus:ring-blue-500 focus:border-blue-500 text-gray-700">

            <option value="">Semua Program Studi</option>
            <option value="IF" {{ request('prodi')=='IF' ? 'selected' : '' }}>Teknik Informatika</option>
            <option value="TG" {{ request('prodi')=='TG' ? 'selected' : '' }}>Teknik Geomatika</option>
            <option value="TRM" {{ request('prodi')=='TRM' ? 'selected' : '' }}>Multimedia</option>
            <option value="ANI" {{ request('prodi')=='ANI' ? 'selected' : '' }}>Animasi</option>
            <option value="RKS" {{ request('prodi')=='RKS' ? 'selected' : '' }}>Keamanan Siber</option>
            <option value="TRPL" {{ request('prodi')=='TRPL' ? 'selected' : '' }}>TRPL</option>
        </select>

        {{-- SEMESTER --}}
        <select name="semester"
            class="w-full border border-gray-300 rounded-lg py-2 px-4
                   focus:ring-blue-500 focus:border-blue-500 text-gray-700">

            <option value="">Semua Semester</option>
            <option value="Ganjil" {{ request('semester')=='Ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="Genap" {{ request('semester')=='Genap' ? 'selected' : '' }}>Genap</option>
        </select>

        {{-- BUTTON --}}
        <button type="submit"
            class="w-full flex items-center justify-center px-4 py-2
                   text-white bg-blue-700 rounded-lg hover:bg-blue-800
                   transition shadow-md whitespace-nowrap">

            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 4h18v2l-7 7v6l-4 2v-8L3 6V4z"/>
            </svg>

            Terapkan Filter
        </button>

    </div>
</form>
