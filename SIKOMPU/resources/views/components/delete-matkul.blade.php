{{-- Modal Delete Mata Kuliah --}}
<div id="deleteMatkulModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    {{-- Backdrop - PERBAIKAN DI SINI ✅ --}}
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeDeleteModal()"></div>

    {{-- Modal Content --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6" onclick="event.stopPropagation()">
            
            {{-- Header dengan Icon Warning --}}
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Konfirmasi Hapus Mata Kuliah
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus mata kuliah berikut?
                    </p>
                    
                    {{-- Info Mata Kuliah --}}
                    <div class="mt-3 p-3 bg-gray-50 rounded-md border border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">
                            <span class="font-semibold">Kode:</span>
                            <span class="font-mono" id="delete_kode"></span>
                        </p>
                        <p class="text-sm font-medium text-gray-700 mb-1">Nama Mata Kuliah:</p>
                        <p class="text-base font-semibold text-gray-900 mb-2" id="delete_nama"></p>
                        <p class="text-sm text-gray-600">
                            <span id="delete_sks"></span> SKS • Semester <span id="delete_semester"></span>
                        </p>
                    </div>
                    
                    <p class="mt-3 text-sm text-red-600 font-medium">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Data yang dihapus tidak dapat dikembalikan!
                    </p>
                </div>
                <button type="button" onclick="closeDeleteModal()"
                        class="text-gray-400 hover:text-gray-700 text-lg font-bold ml-2">
                    &times;
                </button>
            </div>

            {{-- Form Delete --}}
            <form id="delete-mk-form" method="POST" class="mt-5">
                @csrf
                @method('DELETE')

                {{-- Tombol Aksi --}}
                <div class="flex flex-col-reverse sm:flex-row gap-2">
                    <button type="button" onclick="closeDeleteModal()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                        <i class="fas fa-trash mr-2"></i>Ya, Hapus Mata Kuliah
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function openDeleteModal(data) {
    document.getElementById('delete_kode').textContent = data.kode_mk;
    document.getElementById('delete_nama').textContent = data.nama_mk;
    document.getElementById('delete_sks').textContent = data.sks;
    document.getElementById('delete_semester').textContent = data.semester;
    
    const form = document.getElementById('delete-mk-form');
    form.action = "{{ route('matakuliah.index') }}/" + data.id;
    
    document.getElementById('deleteMatkulModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteMatkulModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>