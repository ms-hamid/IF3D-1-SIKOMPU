{{-- Modal Delete Mata Kuliah (Vanilla JS) --}}
<div id="deleteMatkulModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-gray-900 bg-opacity-40" onclick="closeDeleteModal()"></div>

    {{-- Modal Content --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md" onclick="event.stopPropagation()">
            
            {{-- Icon Warning --}}
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Hapus Mata Kuliah?</h3>
                
                <p class="text-gray-600 mb-3">Apakah Anda yakin ingin menghapus mata kuliah:</p>
                
                {{-- Info Mata Kuliah --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4 text-left">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-semibold">Kode:</span>
                        <span class="font-mono" id="delete_kode"></span>
                    </p>
                    <p class="font-semibold text-gray-900 mb-1" id="delete_nama"></p>
                    <p class="text-sm text-gray-600">
                        <span id="delete_sks"></span> SKS - Semester <span id="delete_semester"></span>
                    </p>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-6">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="text-left">
                            <p class="text-sm text-yellow-800 font-medium">Perhatian!</p>
                            <p class="text-xs text-yellow-700 mt-1">Data yang dihapus tidak dapat dikembalikan.</p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                        Batalkan
                    </button>
                    
                    <form id="delete-mk-form" method="POST" class="flex-1">
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