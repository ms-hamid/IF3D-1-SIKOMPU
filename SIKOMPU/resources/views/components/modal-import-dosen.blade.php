{{-- Modal Import Excel Dosen --}}
<div x-show="openImportModal" 
     x-cloak
     @keydown.escape.window="openImportModal = false"
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="openImportModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 transition-opacity"
         @click="openImportModal = false">
    </div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="openImportModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6"
             @click.away="openImportModal = false">
            
            {{-- Header modal --}}
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex justify-between items-center">
                Import Data Dosen
                <button type="button" @click="openImportModal = false"
                        class="text-gray-400 hover:text-gray-700 text-2xl leading-none">
                    &times;
                </button>
            </h2>

            <form action="{{ route('dosen.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Info Box --}}
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Panduan Import:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Download template Excel terlebih dahulu</li>
                                <li>Isi data sesuai format yang tersedia</li>
                                <li>File harus berformat .xlsx atau .xls</li>
                                <li>Maksimal ukuran file 2MB</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Download Template --}}
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md border border-gray-200">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Template Excel</p>
                            <p class="text-xs text-gray-500">template_import_dosen.xlsx</p>
                        </div>
                    </div>
                    <a href="{{ route('dosen.template') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Download
                    </a>
                </div>

                {{-- Upload File --}}
                <div class="flex flex-col" x-data="{ fileName: '' }">
                    <label class="text-sm text-gray-700 font-medium mb-2">
                        Upload File Excel <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500" x-show="!fileName">
                                    <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                </p>
                                <p class="mb-2 text-sm text-gray-700 font-medium" x-show="fileName" x-text="fileName"></p>
                                <p class="text-xs text-gray-500">.XLSX, .XLS (Max. 2MB)</p>
                            </div>
                            <input type="file" 
                                   name="file_import" 
                                   accept=".xlsx,.xls" 
                                   class="hidden" 
                                   @change="fileName = $event.target.files[0]?.name || ''"
                                   required>
                        </label>
                    </div>
                    @error('file_import')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col gap-2 mt-6">
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors font-medium">
                        <i class="fa-solid fa-upload mr-2"></i>Import Data
                    </button>
                    <button type="button" @click="openImportModal = false"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm transition-colors">
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