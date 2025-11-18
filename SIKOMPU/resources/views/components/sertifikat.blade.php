<!-- resources/views/components/sertifikat.blade.php -->
<div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="bg-white rounded-2xl shadow-lg w-11/12 sm:w-3/4 md:w-2/3 lg:w-1/2 p-8 border border-gray-100">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 text-center w-full">Tambah Sertifikat</h2>
            <button type="button" @click="$dispatch('close-modal')" class="absolute right-8 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <!-- Form -->
        <form action="{{ url('/sertifikat') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Upload File -->
            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Upload File Sertifikat</label>
                <input type="file" name="file" id="file"
                    class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition"
                    required>
            </div>

            <!-- Nama Sertifikat -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama/Judul Sertifikat</label>
                <input type="text" name="nama" id="nama"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                    placeholder="Masukkan nama sertifikat"
                    required>
            </div>

            <!-- Institusi -->
            <div>
                <label for="institusi" class="block text-sm font-medium text-gray-700 mb-1">Institusi Pemberi</label>
                <input type="text" name="institusi" id="institusi"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                    placeholder="Masukkan nama institusi"
                    required>
            </div>

            <!-- Tahun -->
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun Diperoleh</label>
                <select name="tahun" id="tahun"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white text-gray-800"
                    required>
                    <option value="">Pilih Tahun</option>
                    @php
                        $currentYear = date('Y');
                        $startYear = 2000;
                    @endphp
                    @for ($year = $currentYear; $year >= $startYear; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <!-- Klasifikasi -->
            <div x-data="{ isCustom: false, value: '' }">
                <label for="klasifikasi" class="block text-sm font-medium text-gray-700 mb-1">Klasifikasi Bidang Kompetensi</label>

                <template x-if="!isCustom">
                    <select name="klasifikasi" id="klasifikasi"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white text-gray-800"
                        @change="if($event.target.value === 'Lainnya') { isCustom = true; $nextTick(() => $refs.customInput.focus()) }"
                        required>
                        <option value="">Pilih Klasifikasi</option>
                        <option value="Teknologi">Teknologi</option>
                        <option value="Manajemen">Manajemen</option>
                        <option value="Desain">Desain</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </template>

                <template x-if="isCustom">
                    <input type="text" name="klasifikasi" x-ref="customInput"
                        class="mt-2 w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                        placeholder="Masukkan jenis kompetensi"
                        x-model="value"
                        required>
                </template>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button"
                    @click="$dispatch('close-modal')"
                    class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    BATAL
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md transition">
                    SIMPAN
                </button>
            </div>
        </form>
    </div>
</div>
