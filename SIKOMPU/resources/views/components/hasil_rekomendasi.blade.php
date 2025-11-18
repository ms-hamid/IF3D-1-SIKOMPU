<!-- resources/views/components/form-konfirmasi-rekomendasi.blade.php -->
<div class="bg-white shadow rounded-2xl p-8 w-full max-w-md mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 text-center mb-2">
        Konfirmasi Pembuatan Rekomendasi
    </h2>
    <p class="text-sm text-gray-500 text-center mb-6">
        Silahkan pilih parameter untuk pengajuan rekomendasi terbaru
    </p>
     <script src="https://cdn.tailwindcss.com"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <form action="{{ url('/rekomendasi') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Semester -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
            <div class="flex gap-3">
                <label class="flex items-center">
                    <input type="radio" name="semester" value="Ganjil" class="hidden peer" required>
                    <span
                        class="peer-checked:bg-blue-700 peer-checked:text-white border border-gray-300 rounded-md px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">
                        Ganjil
                    </span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="semester" value="Genap" class="hidden peer" required>
                    <span
                        class="peer-checked:bg-blue-700 peer-checked:text-white border border-gray-300 rounded-md px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">
                        Genap
                    </span>
                </label>
            </div>
        </div>

        <!-- Tahun Akademik -->
        <div>
            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun Akademik</label>
            <select id="tahun" name="tahun"
                class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                <option value="">Pilih Tahun Akademik</option>
                <option value="2024/2025">2024/2025</option>
                <option value="2025/2026">2025/2026</option>
                <option value="2026/2027">2026/2027</option>
            </select>
        </div>

        <!-- Jenis Rekomendasi -->
        <div class="border rounded-lg p-4 bg-gray-50">
            <p class="text-sm font-medium text-gray-700 mb-3">Jenis Rekomendasi</p>
            <div class="flex flex-col gap-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="jenis[]" value="Beban Mengajar Dosen"
                        class="text-blue-600 focus:ring-blue-500 rounded">
                    <span class="ml-2 text-gray-700">Beban Mengajar Dosen</span>
                </label>

                <label class="inline-flex items-center">
                    <input type="checkbox" name="jenis[]" value="Kinerja Dosen"
                        class="text-blue-600 focus:ring-blue-500 rounded">
                    <span class="ml-2 text-gray-700">Kinerja Dosen</span>
                </label>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-between mt-6">
            <a href="{{ url()->previous() }}" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">
                Batalkan
            </a>
            <button type="submit"
                class="px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800">
                Generate Sekarang
            </button>
        </div>
    </form>
</div>
