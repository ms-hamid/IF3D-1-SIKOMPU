<!-- resources/views/components/delete-dosen.blade.php -->
<div x-show="openDeleteModal" 
     x-cloak
     @keydown.escape.window="openDeleteModal = false"
     @confirm-delete.window="
         openDeleteModal = true; 
         deleteData = {
             id: $event.detail.id,
             nama: $event.detail.nama
         }
     "
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="openDeleteModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 transition-opacity"
         @click="openDeleteModal = false">
    </div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="openDeleteModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6"
             @click.away="openDeleteModal = false">
            
            {{-- Header modal dengan ikon tutup --}}
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Konfirmasi Hapus Dosen
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus dosen berikut?
                    </p>
                    <div class="mt-3 p-3 bg-gray-50 rounded-md border border-gray-200">
                        <p class="text-sm font-medium text-gray-700">Nama Dosen:</p>
                        <p class="text-base font-semibold text-gray-900" x-text="deleteData.nama"></p>
                    </div>
                    <p class="mt-3 text-sm text-red-600 font-medium">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Data yang dihapus tidak dapat dikembalikan!
                    </p>
                </div>
                <button type="button" @click="openDeleteModal = false"
                        class="text-gray-400 hover:text-gray-700 text-lg font-bold ml-2">
                    &times;
                </button>
            </div>

            {{-- Form Delete --}}
            <form :action="`/dosen/${deleteData.id}`" method="POST" class="mt-5">
                @csrf
                @method('DELETE')

                {{-- Tombol Aksi --}}
                <div class="flex flex-col-reverse sm:flex-row gap-2">
                    <button type="button" @click="openDeleteModal = false"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                        <i class="fas fa-trash mr-2"></i>Ya, Hapus Dosen
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>