@extends('layouts.app')

@section('title', 'Edit Data Dosen')
@section('page_title', 'Edit Data Dosen')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-600">
        <a href="{{ route('dosen.index') }}" class="hover:text-blue-600 transition">
            <i class="fas fa-users mr-1"></i>Manajemen Dosen
        </a>
        <span>/</span>
        <span class="text-gray-900 font-medium">Edit Dosen</span>
    </nav>

    {{-- Alert Error --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <div class="flex items-center gap-2 mb-2">
            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium">Terjadi kesalahan:</span>
        </div>
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Edit --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Edit Data Dosen</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi dosen {{ $dosen->nama_lengkap }}</p>
        </div>

        <form action="{{ route('dosen.update', $dosen->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nama Lengkap --}}
                <div class="md:col-span-2">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_lengkap" 
                           name="nama_lengkap"
                           value="{{ old('nama_lengkap', $dosen->nama_lengkap) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('nama_lengkap') border-red-500 @enderror"
                           placeholder="Masukkan Nama Lengkap" 
                           required>
                    @error('nama_lengkap')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NIDN / NIP --}}
                <div>
                    <label for="nidn" class="block text-sm font-medium text-gray-700 mb-1">
                        NIDN / NIP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nidn" 
                           name="nidn"
                           value="{{ old('nidn', $dosen->nidn) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('nidn') border-red-500 @enderror"
                           placeholder="Contoh: 0123456789" 
                           required>
                    @error('nidn')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            required>
                        <option value="Aktif" {{ old('status', $dosen->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status', $dosen->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                {{-- Program Studi --}}
                <div>
                    <label for="prodi" class="block text-sm font-medium text-gray-700 mb-1">
                        Program Studi <span class="text-red-500">*</span>
                    </label>
                    <select id="prodi" 
                            name="prodi"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('prodi') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Program Studi</option>
                        <option value="Teknik Informatika" {{ old('prodi', $dosen->prodi) == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                        <option value="Teknik Geomatika" {{ old('prodi', $dosen->prodi) == 'Teknik Geomatika' ? 'selected' : '' }}>Teknik Geomatika</option>
                        <option value="Teknologi Rekayasa Multimedia" {{ old('prodi', $dosen->prodi) == 'Teknologi Rekayasa Multimedia' ? 'selected' : '' }}>Teknologi Rekayasa Multimedia</option>
                        <option value="Animasi" {{ old('prodi', $dosen->prodi) == 'Animasi' ? 'selected' : '' }}>Animasi</option>
                        <option value="Rekayasa Keamanan Siber" {{ old('prodi', $dosen->prodi) == 'Rekayasa Keamanan Siber' ? 'selected' : '' }}>Rekayasa Keamanan Siber</option>
                        <option value="Teknik Rekayasa Perangkat Lunak" {{ old('prodi', $dosen->prodi) == 'Teknik Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Teknik Rekayasa Perangkat Lunak</option>
                        <option value="Teknologi Permainan" {{ old('prodi', $dosen->prodi) == 'Teknologi Permainan' ? 'selected' : '' }}>Teknologi Permainan</option>
                        <option value="S2 Magister Terapan Teknik Komputer" {{ old('prodi', $dosen->prodi) == 'S2 Magister Terapan Teknik Komputer' ? 'selected' : '' }}>S2 Magister Terapan Teknik Komputer</option>
                    </select>
                    @error('prodi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jabatan --}}
                <div>
                    <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">
                        Jabatan <span class="text-red-500">*</span>
                    </label>
                    <select id="jabatan" 
                            name="jabatan"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('jabatan') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Jabatan</option>
                        <option value="Kepala Jurusan" {{ old('jabatan', $dosen->jabatan) == 'Kepala Jurusan' ? 'selected' : '' }}>Kepala Jurusan</option>
                        <option value="Sekretaris Jurusan" {{ old('jabatan', $dosen->jabatan) == 'Sekretaris Jurusan' ? 'selected' : '' }}>Sekretaris Jurusan</option>
                        <option value="Kepala Program Studi" {{ old('jabatan', $dosen->jabatan) == 'Kepala Program Studi' ? 'selected' : '' }}>Kepala Program Studi</option>
                        <option value="Dosen" {{ old('jabatan', $dosen->jabatan) == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="Laboran" {{ old('jabatan', $dosen->jabatan) == 'Laboran' ? 'selected' : '' }}>Laboran</option>
                    </select>
                    @error('jabatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Struktural: max 12 SKS | Dosen/Laboran: max 16 SKS</p>
                </div>

                {{-- Password --}}
                <div class="md:col-span-2" x-data="{ showPassword: false }">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru <span class="text-gray-400">(Kosongkan jika tidak ingin mengubah)</span>
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               id="password" 
                               name="password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('password') border-red-500 @enderror"
                               placeholder="Masukkan password baru">
                        <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimal 6 karakter</p>
                </div>

                {{-- Foto --}}
                <div class="md:col-span-2" x-data="{ 
                    fileName: '{{ $dosen->foto ? basename($dosen->foto) : '' }}', 
                    preview: '{{ $dosen->foto ? asset('storage/' . $dosen->foto) : null }}' 
                }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Foto Profil <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <div class="flex items-start gap-4">
                        <!-- Preview -->
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50">
                                <template x-if="preview">
                                    <img :src="preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!preview">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </template>
                            </div>
                        </div>
                        <!-- Input -->
                        <div class="flex-1">
                            <input type="file" 
                                   id="foto" 
                                   name="foto"
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="hidden"
                                   @change="
                                       fileName = $event.target.files[0]?.name || '';
                                       if ($event.target.files[0]) {
                                           const reader = new FileReader();
                                           reader.onload = (e) => preview = e.target.result;
                                           reader.readAsDataURL($event.target.files[0]);
                                       }
                                   ">
                            <label for="foto" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Ubah Foto
                            </label>
                            <p x-show="fileName" x-text="fileName" class="mt-1 text-xs text-gray-600"></p>
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG. Max 2MB</p>
                        </div>
                    </div>
                    @error('foto')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('dosen.index') }}" 
                   class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</main>
@endsection