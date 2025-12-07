<!-- resources/views/components/tambah-sertifikat.blade.php -->
<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Tambah Sertifikat</h2>
        <button type="button" @click="$dispatch('close-modal')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
    </div>

    <form action="{{ route('sertifikasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- Upload File -->
        <div x-data="{
                fileName: 'Tidak ada file dipilih',
                clearFile() {
                    this.fileName = 'Tidak ada file dipilih';
                    $refs.fileInput.value = null;
                }
            }">

            <label class="block text-sm  text-gray-700 mb-1 font-bold">
                Upload File Sertifikat <span class="text-red-500">*</span>
            </label>

            <div class="relative bg-blue-50 border border-blue-200 rounded-lg p-3">
                <div class="flex items-center gap-3">
                    
                    <label for="file"
                        class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                        <i class="fa-solid fa-upload"></i> Pilih File
                    </label>

                    <input type="file" name="file" id="file" x-ref="fileInput" class="hidden"
                        accept=".pdf,.jpg,.jpeg,.png" required
                        @change="fileName = $event.target.files[0] ? $event.target.files[0].name : 'Tidak ada file dipilih'">

                    <span class="text-sm text-gray-700" x-text="fileName"></span>

                    <button type="button" class="ml-2 text-red-600 text-xs hover:text-red-800"
                        x-show="fileName !== 'Tidak ada file dipilih'" @click="clearFile()">
                        Batalkan
                    </button>
                </div>
            </div>

            <p class="mt-1 text-xs text-gray-500">
                Format: PDF, JPG, JPEG, PNG. Maksimal 5MB
            </p>
        </div>

        <!-- Nama Sertifikat -->
        <div>
            <label class="block text-sm  text-gray-700 mb-1 font-bold">
                Nama/Judul Sertifikat <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400"
                value="{{ old('nama') }}" placeholder="Contoh: Certified AWS Solutions Architect" required>
        </div>

        <!-- Institusi -->
        <div>
            <label class="block text-sm  text-gray-700 mb-1 font-bold">
                Institusi Pemberi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="institusi" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400"
                value="{{ old('institusi') }}" placeholder="Contoh: Amazon Web Services" required>
        </div>

        <!-- Tahun -->
        <div>
            <label class="block text-sm  text-gray-700 mb-1 font-bold">
                Tahun Diperoleh <span class="text-red-500">*</span>
            </label>

            <select name="tahun" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400" required>
                <option value="">Pilih Tahun</option>
                @for ($year = date('Y'); $year >= 2000; $year--)
                    <option value="{{ $year }}" {{ old('tahun') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>

    {{-- Kategori --}}
    <div class="flex flex-col">
        <label for="kategori_id" class="text-sm  text-gray-700 font-bold mb-1">
            Kategori <span class="text-red-500">*</span>
        </label>
        <select name="kategori_id" id="kategori_id"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm @error('kategori_id')  @enderror"
                required onchange="checkKategori(this)">
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama }}
                </option>
            @endforeach
            <option value="other" {{ old('kategori_id') == 'other' ? 'selected' : '' }}>Lainnya</option>
        </select>
        @error('kategori_id')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror

        {{-- Input kategori baru --}}
        <input type="text" name="kategori_baru" id="kategori_baru"
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm mt-2 {{ old('kategori_id') === 'other' ? '' : 'hidden' }}"
               value="{{ old('kategori_baru') }}"
               placeholder="Ketik kategori baru">
        @error('kategori_baru')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>

    <script>
        function checkKategori(select) {
            const input = document.getElementById('kategori_baru');
            if (select.value === 'other') {
                input.classList.remove('hidden');
                input.required = true;
            } else {
                input.classList.add('hidden');
                input.required = false;
            }
        }

        // Supaya ketika reload page karena error, field kategori_baru muncul otomatis
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('kategori_id');
            checkKategori(select);
        });
    </script>

        <!-- Tombol -->
       <div class="flex flex-col gap-2 mt-4">
        <button type="submit"
                class="w-full px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
            <i class="fa-solid fa-save mr-2"></i>Simpan Sertifikat
        </button>
        <button type="button" @click="openModal = false"
                class="w-full self-center px-3 py-1 border rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm transition-colors">
            Batal
        </button>
    </div>
    </form>
</div>
