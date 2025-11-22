<!-- resources/views/components/edit-sertifikat.blade.php -->
<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Edit Sertifikat</h2>
        <button type="button" @click="$dispatch('close-modal')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
    </div>

    <!-- Form -->
    <form action="{{ route('sertifikasi.update', $sertifikat->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- File Sertifikat Saat Ini -->
        @if($sertifikat->file_path)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <p class="text-sm text-gray-700 mb-2">
                <i class="fa-solid fa-file-pdf text-blue-600"></i> 
                <strong>File saat ini:</strong> {{ basename($sertifikat->file_path) }}
            </p>
            <a href="{{ route('sertifikasi.download', $sertifikat->id) }}" 
               class="text-xs text-blue-600 hover:text-blue-800">
                <i class="fa-solid fa-download"></i> Download file
            </a>
        </div>
        @endif

        <!-- Upload File Baru (Opsional) -->
        <div x-data="{ 
            fileName: 'Tidak ada file dipilih',
            clearFile() {
                this.fileName = 'Tidak ada file dipilih';
                $refs.fileInput.value = null;
            }
        }">
            <label for="file_edit" class="block text-sm font-medium text-gray-700 mb-1">
                Upload File Baru (Opsional)
            </label>
            <div class="relative">
                <input type="file" 
                       name="file" 
                       id="file_edit"
                       class="hidden"
                       accept=".pdf,.jpg,.jpeg,.png"
                       x-ref="fileInput"
                       @change="fileName = $event.target.files[0] ? $event.target.files[0].name : 'Tidak ada file dipilih'">
                
                <div class="flex items-center gap-2">
                    <label for="file_edit" 
                           class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition inline-block">
                        <i class="fa-solid fa-upload"></i> Pilih File
                    </label>

                    <span class="text-sm text-gray-500" x-text="fileName"></span>
                    <button type="button"
                            class="ml-2 text-red-600 text-xs hover:text-red-800"
                            x-show="fileName !== 'Tidak ada file dipilih'"
                            @click="clearFile()">
                        Batalkan
                    </button>
                </div>
            </div>
            <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, JPEG, PNG. Maksimal 5MB. Kosongkan jika tidak ingin mengubah file.</p>
        </div>

        <!-- Nama Sertifikat -->
        <div>
            <label for="nama_edit" class="block text-sm font-medium text-gray-700 mb-1">
                Nama/Judul Sertifikat <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama" id="nama_edit"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                placeholder="Contoh: Certified AWS Solutions Architect"
                value="{{ old('nama', $sertifikat->nama_sertifikat) }}"
                required>
        </div>

        <!-- Institusi -->
        <div>
            <label for="institusi_edit" class="block text-sm font-medium text-gray-700 mb-1">
                Institusi Pemberi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="institusi" id="institusi_edit"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                placeholder="Contoh: Amazon Web Services"
                value="{{ old('institusi', $sertifikat->institusi_pemberi) }}"
                required>
        </div>

        <!-- Tahun -->
        <div>
            <label for="tahun_edit" class="block text-sm font-medium text-gray-700 mb-1">
                Tahun Diperoleh <span class="text-red-500">*</span>
            </label>
            <select name="tahun" id="tahun_edit"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white text-gray-800"
                required>
                <option value="">Pilih Tahun</option>
                @php
                    $currentYear = date('Y');
                    $startYear = 2000;
                    $selectedYear = old('tahun', $sertifikat->tahun_diperoleh);
                @endphp
                @for ($year = $currentYear; $year >= $startYear; $year--)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>

        <!-- Klasifikasi -->
        <div x-data="{ 
            isCustom: {{ in_array(old('klasifikasi', $sertifikat->klasifikasi ?? ''), ['Teknologi Informasi', 'Rekayasa Perangkat Lunak', 'Jaringan Komputer', 'Data Science', 'Keamanan Siber', 'Cloud Computing', 'Pengembangan Web', 'Manajemen Proyek', 'Desain UI/UX']) ? 'false' : 'true' }}, 
            value: '{{ old('klasifikasi', $sertifikat->klasifikasi ?? '') }}' 
        }">
            <label for="klasifikasi_edit" class="block text-sm font-medium text-gray-700 mb-1">
                Klasifikasi Bidang Kompetensi <span class="text-red-500">*</span>
            </label>

            <template x-if="!isCustom">
                <select name="klasifikasi" id="klasifikasi_edit"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white text-gray-800"
                    @change="if($event.target.value === 'Lainnya') { isCustom = true; $nextTick(() => $refs.customInput.focus()) }"
                    required>
                    <option value="">Pilih Klasifikasi</option>
                    <option value="Teknologi Informasi" {{ old('klasifikasi', $sertifikat->klasifikasi ?? '') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                    <option value="Rekayasa Perangkat Lunak" {{ old('klasifikasi', $sertifikat->klasifikasi ?? '') == 'Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                    <option value="Jaringan Komputer" {{ old('klasifikasi', $sertifikat->klasifikasi ?? '') == 'Jaringan Komputer' ? 'selected' : '' }}>Jaringan Komputer</option>
                    <option value="Data Science" {{ old('klasifikasi', $sertifikat->klasifikasi ?? '') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                    <option value="Keamanan Siber" {{ old('klasifikasi', $sertifikat->klasifikasi ?? '') == 'Keamanan Siber' ? 'selected' : '' }}>Keamanan Siber</option>
                    <option value="Cloud Computing" {{ old('klasifikasi', $sertifikat->klasifikasi ?? '') == 'Cloud Computing' ? 'selected' : '' }}>Cloud Computing</option>
                    <option value="Pengembangan Web" {{ old('klasifikasi', $sertifikat->klasifikasi ?? '') == 'Pengembangan Web' ? 'selected' : '' }}>Pengembangan Web</option>
                    <option value="Manajemen Proyek" {{ old('klasifikasi', $sertifikat->klasifikasi ?? '') == 'Manajemen Proyek' ? 'selected' : '' }}>Manajemen Proyek</option>
                    <option value="Desain UI/UX" {{ old('klasifikasi', $sertifikat->klasifikasi ?? '') == 'Desain UI/UX' ? 'selected' : '' }}>Desain UI/UX</option>
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
                <i class="fa-solid fa-save"></i> UPDATE
            </button>
        </div>
    </form>
</div>