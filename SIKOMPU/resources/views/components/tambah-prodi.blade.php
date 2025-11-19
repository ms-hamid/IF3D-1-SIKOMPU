<div x-show="openModal" 
     x-cloak
     @keydown.escape.window="openModal = false"
     class="fixed inset-0 z-50 overflow-y-auto">
    
    {{-- Backdrop --}}
    <div x-show="openModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-50"
         @click="openModal = false">
    </div>

    {{-- Modal Content --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="openModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl shadow-xl w-full max-w-md">
            
            {{-- Header --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>
                        Tambah Program Studi
                    </h3>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('prodi.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="space-y-4">
                    {{-- Kode Prodi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Prodi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="kode_prodi"
                               value="{{ old('kode_prodi') }}"
                               placeholder="Contoh: IF"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    {{-- Nama Prodi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Program Studi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_prodi"
                               value="{{ old('nama_prodi') }}"
                               placeholder="Contoh: Teknik Informatika"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    {{-- Jenjang --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jenjang <span class="text-red-500">*</span>
                        </label>
                        <select name="jenjang"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Pilih Jenjang</option>
                            <option value="D3" {{ old('jenjang') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="D4" {{ old('jenjang') == 'D4' ? 'selected' : '' }}>D4</option>
                            <option value="S1" {{ old('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('jenjang') == 'S2' ? 'selected' : '' }}>S2</option>
                        </select>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 justify-end mt-6 pt-6 border-t border-gray-200">
                    <button type="button"
                            @click="openModal = false"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>