<div
    x-cloak
    x-show="openDeleteModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 flex items-center justify-center bg-black/60 z-50 p-4"
    style="display: none;"
>
    <div 
        @click.away="openDeleteModal = false" 
        class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative"
    >
        {{-- HEADER --}}
        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200">
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-red-50">
                <i class="fa-solid fa-trash text-red-600"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-500">Anda yakin ingin menghapus sertifikat ini?</p>
            </div>
        </div>

        {{-- DATA SERTIFIKAT YANG MAU DIHAPUS --}}
        <template x-if="deleteData">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fa-solid fa-certificate text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-900 text-sm mb-1" x-text="deleteData.nama"></h4>
                        <div class="text-xs text-gray-600 space-y-1">
                            <p><i class="fa-regular fa-building w-4"></i> <span x-text="deleteData.institusi"></span></p>
                            <p><i class="fa-regular fa-calendar w-4"></i> <span x-text="deleteData.tahun"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- WARNING --}}
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-6">
            <p class="text-sm text-red-800">
                <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                Data yang dihapus tidak dapat dikembalikan
            </p>
        </div>

        {{-- ACTION BUTTON --}}
        <div class="flex gap-3 justify-end">
            <button 
                type="button"
                @click="openDeleteModal = false"
                class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Batal
            </button>

            <form :action="`/sertifikasi/${deleteData.id}`" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Sertifikat
                </button>
            </form>
        </div>
    </div>
</div>
