<!-- resources/views/components/tambah-sertifikat.blade.php -->
<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Tambah Sertifikat</h2>
        <button type="button" @click="$dispatch('close-modal')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
    </div>

    <!-- Form -->
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

            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                Upload File Sertifikat <span class="text-red-500">*</span>
            </label>

            <div class="relative bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center gap-3">
            
                <!-- Tombol Pilih File (warna biru seperti Edit) -->
                <label for="file"
                    class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition inline-block">
                    <i class="fa-solid fa-upload"></i> Pilih File
                </label>

                <!-- Input File Hidden -->
                <input type="file"
                    name="file"
                    id="file"
                    x-ref="fileInput"
                    class="hidden"
                    accept=".pdf,.jpg,.jpeg,.png"
                    required
                    @change="fileName = $event.target.files[0] ? $event.target.files[0].name : 'Tidak ada file dipilih'">
            
                <!-- Nama File -->
                <span class="text-sm text-gray-700" x-text="fileName"></span>

                <!-- Tombol Batalkan -->
                <button type="button"
                    class="ml-2 text-red-600 text-xs hover:text-red-800"
                    x-show="fileName !== 'Tidak ada file dipilih'"
                    @click="clearFile()">
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
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                Nama/Judul Sertifikat <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama" id="nama"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                placeholder="Contoh: Certified AWS Solutions Architect"
                value="{{ old('nama') }}"
                required>
        </div>

        <!-- Institusi -->
        <div>
            <label for="institusi" class="block text-sm font-medium text-gray-700 mb-1">
                Institusi Pemberi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="institusi" id="institusi"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                placeholder="Contoh: Amazon Web Services"
                value="{{ old('institusi') }}"
                required>
        </div>

        <!-- Tahun -->
        <div>
            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">
                Tahun Diperoleh <span class="text-red-500">*</span>
            </label>
            <select name="tahun" id="tahun"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white text-gray-800"
                required>
                <option value="">Pilih Tahun</option>
                @php
                    $currentYear = date('Y');
                    $startYear = 2000;
                    $selectedYear = old('tahun');
                @endphp
                @for ($year = $currentYear; $year >= $startYear; $year--)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>

        <!-- Klasifikasi -->
        <div x-data="{ isCustom: false, value: '{{ old('klasifikasi', '') }}' }">
            <label for="klasifikasi" class="block text-sm font-medium text-gray-700 mb-1">
                Klasifikasi Bidang Kompetensi <span class="text-red-500">*</span>
            </label>

            <template x-if="!isCustom">
                <select name="klasifikasi" id="klasifikasi"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white text-gray-800"
                    @change="if($event.target.value === 'Lainnya') { isCustom = true; $nextTick(() => $refs.customInput.focus()) }"
                    required>
                    <option value="">Pilih Klasifikasi</option>
                    <option value="Teknologi Informasi" {{ old('klasifikasi') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                    <option value="Rekayasa Perangkat Lunak" {{ old('klasifikasi') == 'Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                    <option value="Jaringan Komputer" {{ old('klasifikasi') == 'Jaringan Komputer' ? 'selected' : '' }}>Jaringan Komputer</option>
                    <option value="Data Science" {{ old('klasifikasi') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                    <option value="Keamanan Siber" {{ old('klasifikasi') == 'Keamanan Siber' ? 'selected' : '' }}>Keamanan Siber</option>
                    <option value="Cloud Computing" {{ old('klasifikasi') == 'Cloud Computing' ? 'selected' : '' }}>Cloud Computing</option>
                    <option value="Pengembangan Web" {{ old('klasifikasi') == 'Pengembangan Web' ? 'selected' : '' }}>Pengembangan Web</option>
                    <option value="Manajemen Proyek" {{ old('klasifikasi') == 'Manajemen Proyek' ? 'selected' : '' }}>Manajemen Proyek</option>
                    <option value="Desain UI/UX" {{ old('klasifikasi') == 'Desain UI/UX' ? 'selected' : '' }}>Desain UI/UX</option>
                    <option value="Lainnya">Lainnya (Ketik Manual)</option>
                </select>
            </template>

            <template x-if="isCustom">
                <div>
                    <input type="text" name="klasifikasi" x-ref="customInput"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                        placeholder="Masukkan jenis kompetensi"
                        x-model="value"
                        required>
                    <button type="button" 
                        @click="isCustom = false; value = ''"
                        class="mt-2 text-xs text-indigo-600 hover:text-indigo-800">
                        ← Kembali ke pilihan
                    </button>
                </div>
            </template>
        </div>

        <!-- Tombol -->
        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
            <button type="button"
                @click="$dispatch('close-modal')"
                class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                BATAL
            </button>

            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md transition">
                <i class="fa-solid fa-save"></i> SIMPAN
            </button>
        </div>
    </form>
</div>