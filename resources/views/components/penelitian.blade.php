<!-- resources/views/components/form-tambah-penelitian.blade.php -->
<div class="bg-white shadow-l rounded-2xl p-8 w-full max-w-lg mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Tambah Data Penelitian</h2>

    <form action="{{ url('/penelitian') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Judul Penelitian -->
        <div>
            <label for="judul_penelitian" class="block text-sm font-medium text-gray-700 mb-1">Judul Penelitian</label>
            <input type="text" name="judul_penelitian" id="judul_penelitian"
                class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Masukkan judul penelitian" required>
        </div>

        <!-- Tahun Publikasi -->
        <div>
            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun Diperoleh</label>
            <select name="tahun" id="tahun"
                    class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
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

        <!-- Peran -->
        <div>
            <label for="peran" class="block text-sm font-medium text-gray-700 mb-1">Peran</label>
            <select name="peran" id="peran"
                class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                <option value="">Pilih Peran</option>
                <option value="Ketua">Ketua</option>
                <option value="Anggota">Anggota</option>
                <option value="Kontributor">Kontributor</option>
            </select>
        </div>

        <!-- Link Publikasi -->
        <div>
            <label for="link_publikasi" class="block text-sm font-medium text-gray-700 mb-1">Link Publikasi</label>
            <input type="url" name="link_publikasi" id="link_publikasi"
                class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="https://contoh-publikasi.com">
        </div>

        <!-- Tombol -->
        <div class="flex justify-end gap-3 pt-4">
            <button type="button" @click="openModal = false"
                class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300">
                Batal
            </button>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Simpan Penelitian
            </button>
        </div>
    </form>
</div>
