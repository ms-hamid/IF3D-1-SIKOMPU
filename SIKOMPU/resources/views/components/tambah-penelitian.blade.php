{{-- Header modal dengan ikon tutup --}}
<h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 flex justify-between items-center">
    Tambah Penelitian
    <button type="button" @click="openModal = false"
            class="text-gray-400 hover:text-gray-700 text-lg font-bold ml-2">
        &times;
    </button>
</h2>

{{-- Tampilkan pesan error jika ada --}}
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('penelitian.store') }}" method="POST" class="space-y-4">
    @csrf

    {{-- Judul Penelitian --}}
    <div class="flex flex-col">
        <label for="judul_penelitian" class="text-sm  text-gray-700 font-bold mb-1">
            Judul Penelitian <span class="text-red-500">*</span>
        </label>
        <input type="text" name="judul_penelitian" id="judul_penelitian"
               value="{{ old('judul_penelitian') }}"
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('judul_penelitian') @enderror"
               placeholder="Masukkan judul penelitian" required>
        @error('judul_penelitian')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>

    {{-- Tahun Publikasi --}}
    <div class="flex flex-col">
        <label for="tahun" class="text-sm  text-gray-700 font-bold mb-1">
            Tahun Publikasi <span class="text-red-500">*</span>
        </label>
        <select name="tahun" id="tahun"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm @error('tahun')  @enderror"
                required>
            <option value="">Pilih Tahun</option>
            @php
                $currentYear = date('Y');
                $startYear = 2000;
            @endphp
            @for ($year = $currentYear; $year >= $startYear; $year--)
                <option value="{{ $year }}" {{ old('tahun') == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endfor
        </select>
        @error('tahun')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>

    {{-- Peran --}}
    <div class="flex flex-col">
        <label for="peran" class="text-sm  text-gray-700 mb-1 font-bold">
            Peran <span class="text-red-500">*</span>
        </label>
        <select name="peran" id="peran"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm @error('peran')  @enderror"
                required>
            <option value="">Pilih Peran</option>
            <option value="Ketua" {{ old('peran') == 'Ketua' ? 'selected' : '' }}>Ketua</option>
            <option value="Anggota" {{ old('peran') == 'Anggota' ? 'selected' : '' }}>Anggota</option>
        </select>
        @error('peran')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>

    {{-- Link Publikasi --}}
    <div class="flex flex-col">
        <label for="link_publikasi" class="text-sm  text-gray-700 font-bold mb-1">
            Link Publikasi <span class="text-gray-400 text-xs">(Opsional)</span>
        </label>
        <input type="url" name="link_publikasi" id="link_publikasi"
               value="{{ old('link_publikasi') }}"
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm @error('link_publikasi')  @enderror"
               placeholder="https://contoh-publikasi.com">
        @error('link_publikasi')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>

    {{-- Kategori --}}
    <div class="flex flex-col">
        <label for="kategori_id" class="text-sm  text-gray-700 font-bold mb-1">
            Kategori <span class="text-red-500">*</span>
        </label>
        <select name="kategori_id" id="kategori_id"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm @error('kategori_id')  @enderror"
                required onchange="checkKategori(this)">
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama }}
                </option>
            @endforeach
            <option value="other" {{ old('kategori_id') == 'other' ? 'selected' : '' }}>Lainnya</option>
        </select>
        @error('kategori_id')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror

        {{-- Input kategori baru --}}
        <input type="text" name="kategori_baru" id="kategori_baru"
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm mt-2 {{ old('kategori_id') === 'other' ? '' : 'hidden' }}"
               value="{{ old('kategori_baru') }}"
               placeholder="Ketik kategori baru">
        @error('kategori_baru')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>

    <script>
        function checkKategori(select) {
            const input = document.getElementById('kategori_baru');
            if (select.value === 'other') {
                input.classList.remove('hidden');
                input.required = true;
            } else {
                input.classList.add('hidden');
                input.required = false;
            }
        }

        // Supaya ketika reload page karena error, field kategori_baru muncul otomatis
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('kategori_id');
            checkKategori(select);
        });
    </script>

    {{-- Tombol Aksi --}}
    <div class="flex flex-col gap-2 mt-4">
        <button type="submit"
                class="w-full px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
            <i class="fa-solid fa-save mr-2"></i>Simpan Penelitian
        </button>
        <button type="button" @click="openModal = false"
                class="w-full self-center px-3 py-1 border rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm transition-colors">
            Batal
        </button>
    </div>
</form>
