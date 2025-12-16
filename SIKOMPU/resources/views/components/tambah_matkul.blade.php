{{-- Modal Tambah Mata Kuliah (Vanilla JS) --}}
<div id="tambahMatkulModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/40" onclick="closeTambahModal()"></div>

    {{-- Modal Content --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-3xl" onclick="event.stopPropagation()">
            
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Formulir Tambah Mata Kuliah</h3>
            </div>
    
            {{-- Form Body --}}
            <div class="px-6 py-5">
                <form action="{{ route('matakuliah.store') }}" method="POST" id="add-mk-form" class="space-y-4">
                    @csrf
                    
                    {{-- Nama Mata Kuliah --}}
                    <div>
                        <label for="nama_mk" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Mata Kuliah
                        </label>
                        <input type="text" name="nama_mk" id="nama_mk" value="{{ old('nama_mk') }}" required
                               class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Kode Mata Kuliah --}}
                    <div>
                        <label for="kode_mk" class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Mata Kuliah
                        </label>
                        <input type="text" name="kode_mk" id="kode_mk" value="{{ old('kode_mk') }}" required
                               class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
        
                    {{-- Jumlah SKS --}}
                    <div>
                        <label for="sks" class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah SKS
                        </label>
                        <input type="number" name="sks" id="sks" min="1" max="6" value="{{ old('sks') }}" required
                               class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    {{-- Jumlah Sesi --}}
                    <div>
                        <label for="sesi" class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Sesi
                        </label>
                        <input type="number" name="sesi" id="sesi" min="1" max="20" value="{{ old('sesi') }}" required
                               class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
        
                    {{-- Program Studi Pemilik --}}
                    <div>
                        <label for="prodi_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Program Studi Pemilik
                        </label>
                        <select name="prodi_id" id="prodi_id" required
                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach($prodiList as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    {{-- Semester --}}
                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">
                            Semester
                        </label>
                        <select name="semester" id="semester" required
                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Ganjil / Genap</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
            </div>
    
            {{-- Footer Actions --}}
            <div class="px-6 py-4 flex justify-between items-center border-t border-gray-200 bg-gray-50">
                <button type="button" onclick="closeTambahModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                    Batalkan
                </button>
                <button type="submit" form="add-mk-form"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Mata Kuliah
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openTambahModal() {
    document.getElementById('tambahMatkulModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeTambahModal() {
    document.getElementById('tambahMatkulModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>