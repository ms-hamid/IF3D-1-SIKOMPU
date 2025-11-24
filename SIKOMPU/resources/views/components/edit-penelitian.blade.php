{{-- resources/views/components/edit-penelitian.blade.php --}}

<h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 flex justify-between items-center">
    Edit Penelitian
    <button type="button" @click="openEditModal = false"
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

<form :action="`/penelitian/${editData.id}`" method="POST" class="space-y-4">
    @csrf
    @method('PATCH')

    {{-- Judul Penelitian --}}
    <div class="flex flex-col">
        <label for="edit_judul_penelitian" class="text-sm font-medium text-gray-700 mb-1">
            Judul Penelitian <span class="text-red-500">*</span>
        </label>
        <input type="text" name="judul_penelitian" id="edit_judul_penelitian"
               x-model="editData.judul_penelitian"
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
               placeholder="Masukkan judul penelitian" required>
    </div>

    {{-- Tahun Publikasi --}}
    <div class="flex flex-col">
        <label for="edit_tahun" class="text-sm font-medium text-gray-700 mb-1">
            Tahun Publikasi <span class="text-red-500">*</span>
        </label>
        <select name="tahun" id="edit_tahun" x-model="editData.tahun_publikasi"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
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
        <label for="edit_peran" class="text-sm font-medium text-gray-700 mb-1">
            Peran <span class="text-red-500">*</span>
        </label>
        <select name="peran" id="edit_peran" x-model="editData.peran"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                required>
            <option value="">Pilih Peran</option>
            <option value="Ketua">Ketua</option>
            <option value="Anggota">Anggota</option>
        </select>
    </div>

    {{-- Link Publikasi --}}
    <div class="flex flex-col">
        <label for="edit_link_publikasi" class="text-sm font-medium text-gray-700 mb-1">
            Link Publikasi <span class="text-gray-400 text-xs">(Opsional)</span>
        </label>
        <input type="url" name="link_publikasi" id="edit_link_publikasi"
               x-model="editData.link_publikasi"
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
               placeholder="https://contoh-publikasi.com">
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex flex-col gap-2 mt-4">
        <button type="submit"
                class="w-full px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
            <i class="fa-solid fa-save mr-2"></i>Update Penelitian
        </button>
        <button type="button" @click="openEditModal = false"
                class="w-32 self-center px-3 py-1 border rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm transition-colors">
            Batal
        </button>
    </div>
</form>