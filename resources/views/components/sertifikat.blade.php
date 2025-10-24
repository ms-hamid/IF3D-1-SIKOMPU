<!-- resources/views/components/sertifikat.blade.php -->
<div class="bg-white shadow rounded-2xl p-8 w-full max-w-md mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Tambah Sertifikat</h2>
     <script src="https://cdn.tailwindcss.com"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <form action="{{ url('/sertifikat') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Upload File Sertifikat -->
        <div>
            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Upload File Sertifikat</label>
            <input type="file" name="file" id="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer focus:outline-none">
            @error('file')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nama/Judul Sertifikat -->
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama/Judul Sertifikat</label>
            <input type="text" name="nama" id="nama" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        <!-- Institusi Pemberi -->
        <div>
            <label for="institusi" class="block text-sm font-medium text-gray-700 mb-1">Institusi Pemberi</label>
            <input type="text" name="institusi" id="institusi" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        <!-- Tahun Diperoleh -->
        <div>
            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun Diperoleh</label>
            <input type="number" name="tahun" id="tahun" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Contoh: 2024" required>
        </div>

        <!-- Klasifikasi Bidang Kompetensi -->
        <div>
            <label for="klasifikasi" class="block text-sm font-medium text-gray-700 mb-1">Klasifikasi Bidang Kompetensi</label>
            <select name="klasifikasi" id="klasifikasi" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                <option value="">Pilih Klasifikasi</option>
                <option value="Teknologi">Teknologi</option>
                <option value="Manajemen">Manajemen</option>
                <option value="Desain">Desain</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>

        <!-- Tombol -->
        <div class="flex justify-between mt-6">
            <a href="{{ url()->previous() }}" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">BATAL</a>
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800">SIMPAN</button>
        </div>
    </form>
</div>
