<!-- resources/views/components/edit-prodi.blade.php -->
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
                Edit Program Studi
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

                {{-- Kode Prodi --}}
                <div class="flex flex-col">
                    <label for="edit_kode_prodi" class="text-sm text-gray-700 font-bold mb-1">
                        Kode Prodi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="edit_kode_prodi"
                           name="kode_prodi"
                           x-model="editData.kode_prodi"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                           placeholder="Contoh: IF"
                           required>
                </div>

                {{-- Nama Program Studi --}}
                <div class="flex flex-col">
                    <label for="edit_nama_prodi" class="text-sm text-gray-700 font-bold mb-1">
                        Nama Program Studi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="edit_nama_prodi"
                           name="nama_prodi"
                           x-model="editData.nama_prodi"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                           placeholder="Contoh: Teknik Informatika"
                           required>
                </div>

                {{-- Jenjang --}}
                <div class="flex flex-col">
                    <label for="edit_jenjang" class="text-sm text-gray-700 font-bold mb-1">
                        Jenjang <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_jenjang"
                            name="jenjang"
                            x-model="editData.jenjang"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                            required>
                        <option value="">Pilih Jenjang</option>
                        <option value="D3">D3</option>
                        <option value="D4">D4</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                    </select>
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