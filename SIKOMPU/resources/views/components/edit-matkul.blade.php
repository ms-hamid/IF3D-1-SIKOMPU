{{-- Modal Edit Mata Kuliah (Vanilla JS) --}}
<div id="editMatkulModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-white/10 backdrop-blur-[1px]" onclick="closeEditModal()"></div>

    {{-- Modal Content --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-3xl" onclick="event.stopPropagation()">
            
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Edit Mata Kuliah</h3>
            </div>
    
            {{-- Form Body --}}
            <div class="px-6 py-5">
                <form id="edit-mk-form" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    {{-- Nama Mata Kuliah --}}
                    <div>
                        <label for="edit_nama_mk" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Mata Kuliah
                        </label>
                        <input type="text" name="nama_mk" id="edit_nama_mk" required
                               class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Kode Mata Kuliah --}}
                    <div>
                        <label for="edit_kode_mk" class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Mata Kuliah
                        </label>
                        <input type="text" name="kode_mk" id="edit_kode_mk" required
                               class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
        
                    {{-- Jumlah SKS --}}
                    <div>
                        <label for="edit_sks" class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah SKS
                        </label>
                        <input type="number" name="sks" id="edit_sks" min="1" max="6" required
                               class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    {{-- Jumlah Sesi --}}
                    <div>
                        <label for="edit_sesi" class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Sesi
                        </label>
                        <input type="number" name="sesi" id="edit_sesi" min="1" max="20" required
                               class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
        
                    {{-- Program Studi Pemilik --}}
                    <div>
                        <label for="edit_prodi_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Program Studi Pemilik
                        </label>
                        <select name="prodi_id" id="edit_prodi_id" required
                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach($prodiList as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
        
                    {{-- Semester --}}
                    <div>
                        <label for="edit_semester" class="block text-sm font-medium text-gray-700 mb-1">
                            Semester
                        </label>
                        <select name="semester" id="edit_semester" required
                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Ganjil / Genap</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
            </div>
    
            {{-- Footer Actions --}}
            <div class="px-6 py-4 flex justify-between items-center border-t border-gray-200 bg-gray-50">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                    Batalkan
                </button>
                <button type="submit" form="edit-mk-form"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_nama_mk').value = data.nama_mk;
    document.getElementById('edit_kode_mk').value = data.kode_mk;
    document.getElementById('edit_sks').value = data.sks;
    document.getElementById('edit_sesi').value = data.sesi;
    document.getElementById('edit_semester').value = data.semester;
    document.getElementById('edit_prodi_id').value = data.prodi_id;
    
    const form = document.getElementById('edit-mk-form');
    form.action = "{{ route('matakuliah.index') }}/" + data.id;
    
    document.getElementById('editMatkulModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editMatkulModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeTambahModal();
        closeDeleteModal();
    }
});
</script>