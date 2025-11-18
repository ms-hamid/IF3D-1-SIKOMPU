<!-- resources/views/components/form-tambah-mata-kuliah.blade.php -->
<div class="bg-white shadow rounded-2xl p-8 w-full max-w-lg mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Formulir Tambah Mata Kuliah</h2>
     <script src="https://cdn.tailwindcss.com"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <form action="{{ url('/matakuliah') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Nama Mata Kuliah -->
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Kuliah</label>
            <input type="text" name="nama" id="nama" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        <!-- Kode Mata Kuliah -->
        <div>
            <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode Mata Kuliah</label>
            <input type="text" name="kode" id="kode" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        <!-- Jumlah SKS -->
        <div>
            <label for="sks" class="block text-sm font-medium text-gray-700 mb-1">Jumlah SKS</label>
            <input type="number" name="sks" id="sks" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        <!-- Jumlah Sesi -->
        <div>
            <label for="sesi" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Sesi</label>
            <input type="number" name="sesi" id="sesi" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        <!-- Program Studi Pemilik -->
        <div>
            <label for="prodi" class="block text-sm font-medium text-gray-700 mb-1">Program Studi Pemilik</label>
            <input type="text" name="prodi" id="prodi" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        <!-- Semester -->
        <div>
            <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
            <select name="semester" id="semester" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Ganjil / Genap</option>
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
            </select>
        </div>

        <!-- Tombol -->
        <div class="flex justify-between mt-6">
            <a href="{{ url()->previous() }}" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">Batalkan</a>
            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Mata Kuliah
            </button>
        </div>
    </form>
</div>
