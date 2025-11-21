{{-- Modal Tambah Mata Kuliah --}}
<div 
    x-data="{ open: false }"
    @open-add-matkul-modal.window="open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 px-4"
>

    <div 
        @click.away="open = false"
        class="bg-white w-full max-w-lg rounded-xl p-6 shadow-lg"
    >
        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            Formulir Tambah Mata Kuliah
        </h2>

        <form action="{{ route('matakuliah.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm text-gray-700">Nama Mata Kuliah</label>
                <input type="text" name="nama_mk"
                    class="w-full border rounded-lg px-3 py-2 mt-1"
                    value="{{ old('nama_mk') }}">
            </div>

            <div>
                <label class="text-sm text-gray-700">Kode Mata Kuliah</label>
                <input type="text" name="kode_mk"
                    class="w-full border rounded-lg px-3 py-2 mt-1"
                    value="{{ old('kode_mk') }}">
            </div>

            <div>
                <label class="text-sm text-gray-700">Jumlah SKS</label>
                <input type="number" name="sks"
                    class="w-full border rounded-lg px-3 py-2 mt-1"
                    value="{{ old('sks') }}">
            </div>

            <div>
                <label class="text-sm text-gray-700">Jumlah Sesi</label>
                <input type="number" name="sesi"
                    class="w-full border rounded-lg px-3 py-2 mt-1"
                    value="{{ old('sesi') }}">
            </div>

            <div>
                <label class="text-sm text-gray-700">Program Studi Pemilik</label>
                <select name="prodi_id" class="w-full border rounded-lg px-3 py-2 mt-1">
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach($prodiList as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-700">Semester</label>
                <select name="semester" class="w-full border rounded-lg px-3 py-2 mt-1" required>
                    <option value="">-- Pilih Semester --</option>
                    <option value="Ganjil">Semester Ganjil</option>
                    <option value="Genap">Semester Genap</option>
                </select>
            </div>

            <div class="flex justify-between pt-4">
                <button type="button"
                    @click="open = false"
                    class="px-4 py-2 border rounded-lg">
                    Batalkan
                </button>

                <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan Mata Kuliah
                </button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
<script>
    window.dispatchEvent(new CustomEvent('open-add-matkul-modal'));
</script>
@endif
