<!-- resources/views/components/form-tambah-penelitian.blade.php -->
<div class="bg-white shadow rounded-2xl p-8 w-full max-w-lg mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Tambah Data Penelitian</h2>
     <script src="https://cdn.tailwindcss.com"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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
            <label for="tahun_publikasi" class="block text-sm font-medium text-gray-700 mb-1">Tahun Publikasi</label>
            <input type="number" name="tahun_publikasi" id="tahun_publikasi"
                class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Contoh: 2025" required>
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
        <div class="flex justify-between pt-4">
            <a href="{{ url()->previous() }}" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">
                Batal
            </a>
            <button type="submit"
                class="px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 focus:ring-2 focus:ring-blue-500">
                Simpan Penelitian
            </button>
        </div>
    </form>
</div>
