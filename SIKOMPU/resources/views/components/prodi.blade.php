<!-- resources/views/components/form-tambah-prodi.blade.php -->
<div class="bg-white shadow rounded-2xl p-8 w-full max-w-md mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Tambah Program Studi Baru</h2>
     <script src="https://cdn.tailwindcss.com"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <form action="{{ url('/prodi') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Nama Program Studi -->
        <div>
            <input type="text" name="nama" id="nama"
                class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Nama Program Studi" required>
        </div>

        <!-- Kode Program Studi -->
        <div>
            <input type="text" name="kode" id="kode"
                class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Kode Program Studi" required>
        </div>

        <!-- Jenjang -->
        <div>
            <select name="jenjang" id="jenjang"
                class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                <option value="">Jenjang</option>
                <option value="D3">D3</option>
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3">S3</option>
            </select>
        </div>

        <!-- Tombol -->
        <div class="flex justify-between pt-4">
            <a href="{{ url()->previous() }}" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">
                Batalkan
            </a>
            <button type="submit"
                class="px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 focus:ring-2 focus:ring-blue-500">
                Simpan Data
            </button>
        </div>
    </form>
</div>
