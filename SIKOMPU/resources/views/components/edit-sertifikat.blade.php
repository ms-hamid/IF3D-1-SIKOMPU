{{-- resources/views/components/edit-sertifikat.blade.php --}}

<h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 flex justify-between items-center">
    Edit Sertifikat
    <button type="button" @click="openEditModal = false"
            class="text-gray-400 hover:text-gray-700 text-lg font-bold ml-2">
        &times;
    </button>
</h2>

{{-- Tampilkan pesan error jika ada --}}
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('sertifikasi.update', $sertifikat->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @method('PUT')

    {{-- File Sertifikat Saat Ini --}}
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

    {{-- Upload File Baru (Opsional) --}}
    <div class="flex flex-col" x-data="{ 
        fileName: 'Tidak ada file dipilih',
        clearFile() {
            this.fileName = 'Tidak ada file dipilih';
            $refs.fileInput.value = null;
        }
    }">
        <label for="file_edit" class="text-sm font-medium text-gray-700 mb-1">
            Upload File Baru <span class="text-gray-400 text-xs">(Opsional)</span>
        </label>
        <div class="relative">
            <input type="file" 
                   name="file" 
                   id="file_edit"
                   class="hidden"
                   accept=".pdf,.jpg,.jpeg,.png"
                   x-ref="fileInput"
                   @change="fileName = $event.target.files[0] ? $event.target.files[0].name : 'Tidak ada file dipilih'">
            
            <div class="flex items-center gap-2 flex-wrap">
                <label for="file_edit" 
                       class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition inline-block">
                    <i class="fa-solid fa-upload"></i> Pilih File
                </label>

                <span class="text-sm text-gray-500 flex-1 truncate" x-text="fileName"></span>
                <button type="button"
                        class="text-red-600 text-xs hover:text-red-800"
                        x-show="fileName !== 'Tidak ada file dipilih'"
                        @click="clearFile()">
                    Batalkan
                </button>
            </div>
        </div>
        <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, JPEG, PNG. Maksimal 5MB. Kosongkan jika tidak ingin mengubah file.</p>
    </div>

    {{-- Nama Sertifikat --}}
    <div class="flex flex-col">
        <label for="nama_edit" class="text-sm font-medium text-gray-700 mb-1">
            Nama/Judul Sertifikat <span class="text-red-500">*</span>
        </label>
        <input type="text" name="nama" id="nama_edit"
            class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Contoh: Certified AWS Solutions Architect"
            value="{{ old('nama', $sertifikat->nama_sertifikat) }}"
            required>
    </div>

    {{-- Institusi --}}
    <div class="flex flex-col">
        <label for="institusi_edit" class="text-sm font-medium text-gray-700 mb-1">
            Institusi Pemberi <span class="text-red-500">*</span>
        </label>
        <input type="text" name="institusi" id="institusi_edit"
            class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Contoh: Amazon Web Services"
            value="{{ old('institusi', $sertifikat->institusi_pemberi) }}"
            required>
    </div>

    {{-- Tahun --}}
    <div class="flex flex-col">
        <label for="tahun_edit" class="text-sm font-medium text-gray-700 mb-1">
            Tahun Diperoleh <span class="text-red-500">*</span>
        </label>
        <select name="tahun" id="tahun_edit"
            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white text-gray-800 text-sm"
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

    {{-- Kategori --}}
    <div class="flex flex-col" x-data="{ 
        showCustomInput: {{ old('kategori_id') === 'custom' || (!old('kategori_id') && !in_array($sertifikat->kategori_id ?? 0, $kategori->pluck('id')->toArray())) ? 'true' : 'false' }}
    }">
        <label for="kategori_id_edit" class="text-sm font-medium text-gray-700 mb-1">
            Kategori Sertifikat <span class="text-red-500">*</span>
        </label>

        {{-- Dropdown Kategori --}}
        <div x-show="!showCustomInput">
            <select name="kategori_id" id="kategori_id_edit"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white text-gray-800 text-sm"
                @change="if($event.target.value === 'custom') { showCustomInput = true; $nextTick(() => $refs.customInput.focus()) }">
                <option value="">Pilih Kategori</option>
                @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}" {{ old('kategori_id', $sertifikat->kategori_id) == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama }}
                    </option>
                @endforeach
                <option value="custom">+ Tambah Kategori Baru</option>
            </select>
        </div>

        {{-- Input Manual Kategori Baru --}}
        <div x-show="showCustomInput" style="display: none;">
            <input type="text" 
                name="kategori_baru" 
                id="kategori_baru_edit"
                x-ref="customInput"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                placeholder="Masukkan nama kategori baru"
                value="{{ old('kategori_baru', !in_array($sertifikat->kategori_id ?? 0, $kategori->pluck('id')->toArray()) ? ($sertifikat->kategori->nama ?? '') : '') }}">
            
            <button type="button" 
                @click="showCustomInput = false; document.getElementById('kategori_baru_edit').value = '';"
                class="mt-2 text-xs text-blue-600 hover:text-blue-800">
                ← Kembali ke pilihan kategori
            </button>
        </div>

        <p class="mt-1 text-xs text-gray-500">Pilih kategori dari daftar atau tambahkan kategori baru</p>
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex flex-col gap-2 mt-4">
        <button type="submit"
                class="w-full px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
            <i class="fa-solid fa-save mr-2"></i>Update Sertifikat
        </button>
        <button type="button" @click="openEditModal = false"
                class="w-32 self-center px-3 py-1 border rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm transition-colors">
            Batal
        </button>
    </div>
</form>