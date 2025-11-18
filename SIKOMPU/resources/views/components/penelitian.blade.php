<!-- resources/views/components/form-tambah-penelitian.blade.php -->

{{-- Header modal dengan ikon tutup --}}
<h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 flex justify-between items-center">
    Tambah Penelitian
    <button type="button" @click="$dispatch('close-modal')"
            class="text-gray-400 hover:text-gray-700 text-lg font-bold ml-2">
        &times;
    </button>
</h2>

<form action="{{ url('/penelitian') }}" method="POST" class="space-y-4">
    @csrf

    {{-- Judul Penelitian --}}
    <div class="flex flex-col">
        <label for="judul_penelitian" class="text-sm font-medium text-gray-700 mb-1">Judul Penelitian</label>
        <input type="text" name="judul_penelitian" id="judul_penelitian"
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
               placeholder="Masukkan judul penelitian" required>
    </div>

    {{-- Tahun Publikasi --}}
    <div class="flex flex-col">
        <label for="tahun" class="text-sm font-medium text-gray-700 mb-1">Tahun Diperoleh</label>
        <select name="tahun" id="tahun"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm appearance-none"
                style="max-height:200px; overflow-y:auto;"
                required>
            <option value="">Pilih Tahun</option>
            @php
                $currentYear = date('Y');
                $startYear = 2000;
            @endphp
            @for ($year = $currentYear; $year >= $startYear; $year--)
                <option value="{{ $year }}">{{ $year }}</option>
            @endfor
        </select>
    </div>

    {{-- Peran --}}
    <div class="flex flex-col">
        <label for="peran" class="text-sm font-medium text-gray-700 mb-1">Peran</label>
        <select name="peran" id="peran"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm appearance-none"
                style="max-height:200px; overflow-y:auto;"
                required>
            <option value="">Pilih Peran</option>
            <option value="Ketua">Ketua</option>
            <option value="Anggota">Anggota</option>
            <option value="Kontributor">Kontributor</option>
        </select>
    </div>

    {{-- Link Publikasi --}}
    <div class="flex flex-col">
        <label for="link_publikasi" class="text-sm font-medium text-gray-700 mb-1">Link Publikasi</label>
        <input type="url" name="link_publikasi" id="link_publikasi"
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
               placeholder="https://contoh-publikasi.com">
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex flex-col gap-2 mt-4">
        <button type="submit"
                class="w-full px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Simpan Penelitian
        </button>
        <button type="button" @click="$dispatch('close-modal')"
                class="w-32 self-center px-3 py-1 border rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm">
            Batal
        </button>
    </div>
</form>
