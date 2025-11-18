<!-- resources/views/components/modal-delete-dosen.blade.php -->
<div x-data="{ 
        showDeleteModal: false, 
        dosenId: null, 
        dosenNama: '' 
     }"
     @confirm-delete.window="
        showDeleteModal = true;
        dosenId = $event.detail.id;
        dosenNama = $event.detail.nama;
     "
     x-cloak>
    
    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         @keydown.escape.window="showDeleteModal = false"
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="delete-modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="showDeleteModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"
             @click="showDeleteModal = false">
        </div>

        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-xl w-full max-w-md">
                
                <!-- Icon Warning -->
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-2" id="delete-modal-title">
                        Hapus Data Dosen?
                    </h3>
                    
                    <p class="text-gray-600 mb-1">
                        Apakah Anda yakin ingin menghapus data dosen:
                    </p>
                    <p class="font-semibold text-gray-900 mb-4" x-text="dosenNama"></p>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-6">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="text-left">
                                <p class="text-sm text-yellow-800 font-medium">Perhatian!</p>
                                <p class="text-xs text-yellow-700 mt-1">
                                    Data yang dihapus tidak dapat dikembalikan. Pastikan Anda yakin sebelum melanjutkan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 justify-center">
                        <button type="button"
                                @click="showDeleteModal = false"
                                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                            Batalkan
                        </button>
                        
                        <form :action="`{{ route('dosen.index') }}/${dosenId}`" 
                              method="POST" 
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>