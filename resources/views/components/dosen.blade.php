<!-- resources/views/components/form-tambah-dosen.blade.php -->
<div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-xl mx-auto mt-10 border border-gray-100">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center border-b pb-4">
        Tambah Data Dosen Baru
    </h2>
     <script src="https://cdn.tailwindcss.com"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <form action="{{ url('/dosen') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" id="nama" name="nama"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-400 placeholder-gray-400"
                placeholder="Masukkan Nama Lengkap" required>
        </div>

        <!-- NIDN / NIP -->
        <div>
            <label for="nidn" class="block text-sm font-medium text-gray-700 mb-2">NIDN / NIP</label>
            <input type="text" id="nidn" name="nidn"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-400 placeholder-gray-400"
                placeholder="NIDN / NIP" required>
        </div>

        <!-- Program Studi -->
        <div>
            <label for="prodi" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
            <select id="prodi" name="prodi"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-400"
                required>
                <option value="">Pilih Prodi</option>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Teknik Geomatika">Teknik Geomatika</option>
                <option value="Teknologi Rekayasa Multimedia">Teknologi Rekayasa Multimedia</option>
                <option value="Animasi">Animasi</option>
                <option value="Rekayasa Keamanan Siber">Rekayasa Keamanan Siber</option>
                <option value="Teknik Rekayasa Perangkat Lunak">Teknik Rekayasa Perangkat Lunak</option>
                <option value="Teknologi Permainan">Teknologi Permainan</option>
                <option value="S2 Magister Terapan Teknik Komputer">S2 Magister Terapan Teknik Komputer</option>
            </select>
        </div>

        <!-- Jabatan Akademik -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan Akademik</label>
            <div class="flex flex-wrap gap-5">
                <label class="inline-flex items-center">
                    <input type="radio" name="jabatan" value="Struktural"
                        class="text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="ml-2 text-gray-700">Dosen Struktural</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="jabatan" value="Biasa"
                        class="text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="ml-2 text-gray-700">Dosen Biasa</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="jabatan" value="Laboran"
                        class="text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="ml-2 text-gray-700">Dosen Laboran</span>
                </label>
            </div>
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username (Login)</label>
            <input type="text" id="username" name="username"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-400 placeholder-gray-400"
                placeholder="Masukkan Username" required>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="flex items-center gap-2">
                <input type="password" id="password" name="password"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-400 placeholder-gray-400"
                    placeholder="Masukkan Password" required>
                <button type="button" id="resetPassword"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 text-sm transition">
                    Reset Password
                </button>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-between mt-8">
            <a href="{{ url()->previous() }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                Batalkan
            </a>
            <button type="submit"
                class="flex items-center gap-2 px-5 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
                Simpan Data Dosen
            </button>
        </div>
    </form>
</div>

<!-- Optional JS untuk Reset Password -->
<script>
    document.getElementById('resetPassword').addEventListener('click', function () {
        document.getElementById('password').value = '';
    });
</script>
