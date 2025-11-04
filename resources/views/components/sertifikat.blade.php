<!-- resources/views/components/sertifikat.blade.php -->

<div>
    {{-- Header modal dengan ikon tutup --}}
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 text-center flex justify-between items-center">
        Tambah Sertifikat
        <button type="button" @click="$dispatch('close-modal')" class="text-gray-400 hover:text-gray-700 ml-2 text-lg font-bold">&times;</button>
    </h2>

    <form action="{{ url('/sertifikat') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- Upload File Sertifikat --}}
        <div class="flex flex-col">
            <label for="file" class="text-sm font-medium text-gray-700 mb-1">Upload File Sertifikat</label>
            <input type="file" name="file" id="file"
                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
            @error('file')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nama/Judul Sertifikat --}}
        <div class="flex flex-col">
            <label for="nama" class="text-sm font-medium text-gray-700 mb-1">Nama/Judul Sertifikat</label>
            <input type="text" name="nama" id="nama"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   required>
        </div>

        {{-- Institusi Pemberi --}}
        <div class="flex flex-col">
            <label for="institusi" class="text-sm font-medium text-gray-700 mb-1">Institusi Pemberi</label>
            <input type="text" name="institusi" id="institusi"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   required>
        </div>

        {{-- Tahun Diperoleh --}}
        <div class="flex flex-col">
            <label for="tahun" class="text-sm font-medium text-gray-700 mb-1">Tahun Diperoleh</label>
            <select name="tahun" id="tahun"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm appearance-none"
                    style="max-height:200px; overflow-y:auto;"
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

        {{-- Klasifikasi Bidang Kompetensi --}}
        <div class="flex flex-col" x-data="{ isCustom: false, value: '' }">
            <label for="klasifikasi" class="text-sm font-medium text-gray-700 mb-1">Klasifikasi Bidang Kompetensi</label>

                <!-- Dropdown / input dinamis -->
                <template x-if="!isCustom">
                    <select name="klasifikasi" id="klasifikasi"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm appearance-none"
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
                        class="mt-2 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                        placeholder="Masukkan jenis kompetensi"
                        x-model="value"
                        required>
                </template>
            </div>


        {{-- Tombol SIMPAN --}}
        <div class="mt-6">
            <button type="submit"
                    class="w-full px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800">
                SIMPAN
            </button>
        </div>

        {{-- Tombol BATAL di bawah, lebih kecil --}}
        <div class="mt-2">
            <button type="button" @click="$dispatch('close-modal')"
                    class="w-full sm:w-auto px-3 py-1 text-sm border rounded-md text-gray-700 hover:bg-gray-100">
                BATAL
            </button>
        </div>
    </form>
</div>
