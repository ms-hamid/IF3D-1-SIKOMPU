<!-- resources/views/components/edit-dosen.blade.php -->
<div x-show="openEditModal" 
     x-cloak
     @keydown.escape.window="openEditModal = false"
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="openEditModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 transition-opacity"
         @click="openEditModal = false">
    </div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="openEditModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6"
             @click.away="openEditModal = false">
            
            {{-- Header modal dengan ikon tutup --}}
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 flex justify-between items-center">
                Edit Data Dosen
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

            <!-- Form -->
            <form :action="editAction" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Nama Lengkap -->
                <div class="flex flex-col">
                    <label for="edit_nama_lengkap" class="text-sm text-gray-700 font-bold mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="edit_nama_lengkap" 
                           name="nama_lengkap"
                           x-model="editData.nama_lengkap"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           placeholder="Masukkan Nama Lengkap" 
                           required>
                </div>

                <!-- NIDN / NIP -->
                <div class="flex flex-col">
                    <label for="edit_nidn" class="text-sm text-gray-700 font-bold mb-1">
                        NIDN / NIP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="edit_nidn" 
                           name="nidn"
                           x-model="editData.nidn"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                           placeholder="Contoh: 0123456789" 
                           required>
                    <p class="text-xs text-gray-500 mt-1">NIDN akan digunakan sebagai username untuk login</p>
                </div>

                <!-- Program Studi -->
                <div class="flex flex-col">
                    <label for="edit_prodi" class="text-sm text-gray-700 font-bold mb-1">
                        Program Studi <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_prodi" 
                            name="prodi"
                            x-model="editData.prodi"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                            required>
                        <option value="">Pilih Program Studi</option>
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

                <!-- Jabatan -->
                <div class="flex flex-col">
                    <label for="edit_jabatan" class="text-sm text-gray-700 font-bold mb-1">
                        Jabatan <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_jabatan" 
                            name="jabatan"
                            x-model="editData.jabatan"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                            required>
                        <option value="">Pilih Jabatan</option>
                        <option value="Kepala Jurusan">Kepala Jurusan</option>
                        <option value="Sekretaris Jurusan">Sekretaris Jurusan</option>
                        <option value="Kepala Program Studi">Kepala Program Studi</option>
                        <option value="Dosen">Dosen</option>
                        <option value="Laboran">Laboran</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Struktural: max 12 SKS | Dosen/Laboran: max 16 SKS</p>
                </div>

                <!-- Status -->
                <div class="flex flex-col">
                    <label for="edit_status" class="text-sm text-gray-700 font-bold mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_status" 
                            name="status"
                            x-model="editData.status"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                            required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="flex flex-col" x-data="{ showPassword: false }">
                    <label for="edit_password" class="text-sm text-gray-700 font-bold mb-1">
                        Password Baru <span class="text-gray-400 text-xs">(Kosongkan jika tidak diubah)</span>
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               id="edit_password" 
                               name="password"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                               placeholder="Masukkan Password Baru">
                        <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col gap-2 mt-4">
                    <button type="submit"
                            class="w-full px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                    </button>
                    <button type="button" @click="openEditModal = false"
                            class="w-full self-center px-3 py-1 border rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
// Fungsi untuk membuka modal edit dengan data dosen
function openEditDosen(dosen) {
    window.editDosenData = {
        id: dosen.id,
        nama_lengkap: dosen.nama_lengkap,
        nidn: dosen.nidn,
        prodi: dosen.prodi,
        jabatan: dosen.jabatan,
        status: dosen.status
    };
    
    // Set action URL untuk form
    window.editDosenAction = `/dosen/${dosen.id}`;
    
    // Trigger Alpine.js untuk buka modal
    window.dispatchEvent(new CustomEvent('open-edit-modal'));
}
</script>