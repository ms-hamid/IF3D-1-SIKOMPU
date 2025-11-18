<!-- resources/views/components/tambah-dosen.blade.php -->
<div x-show="openModal" 
     x-cloak
     @keydown.escape.window="openModal = false"
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="openModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
         @click="openModal = false">
    </div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="openModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl">
            
            <!-- Header -->
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900" id="modal-title">
                        Tambah Data Dosen Baru
                    </h3>
                    <button @click="openModal = false" 
                            type="button"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('dosen.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-4">
                @csrf

                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                    
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nama_lengkap" 
                               name="nama_lengkap"
                               value="{{ old('nama_lengkap') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('nama_lengkap') border-red-500 @enderror"
                               placeholder="Masukkan Nama Lengkap" 
                               required>
                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIDN / NIP -->
                    <div>
                        <label for="nidn" class="block text-sm font-medium text-gray-700 mb-1">
                            NIDN / NIP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nidn" 
                               name="nidn"
                               value="{{ old('nidn') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('nidn') border-red-500 @enderror"
                               placeholder="Contoh: 0123456789" 
                               required>
                        @error('nidn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">NIDN akan digunakan sebagai username untuk login</p>
                    </div>

                    <!-- Program Studi -->
                    <div>
                        <label for="prodi" class="block text-sm font-medium text-gray-700 mb-1">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <select id="prodi" 
                                name="prodi"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('prodi') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Program Studi</option>
                            <option value="Teknik Informatika" {{ old('prodi') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                            <option value="Teknik Geomatika" {{ old('prodi') == 'Teknik Geomatika' ? 'selected' : '' }}>Teknik Geomatika</option>
                            <option value="Teknologi Rekayasa Multimedia" {{ old('prodi') == 'Teknologi Rekayasa Multimedia' ? 'selected' : '' }}>Teknologi Rekayasa Multimedia</option>
                            <option value="Animasi" {{ old('prodi') == 'Animasi' ? 'selected' : '' }}>Animasi</option>
                            <option value="Rekayasa Keamanan Siber" {{ old('prodi') == 'Rekayasa Keamanan Siber' ? 'selected' : '' }}>Rekayasa Keamanan Siber</option>
                            <option value="Teknik Rekayasa Perangkat Lunak" {{ old('prodi') == 'Teknik Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Teknik Rekayasa Perangkat Lunak</option>
                            <option value="Teknologi Permainan" {{ old('prodi') == 'Teknologi Permainan' ? 'selected' : '' }}>Teknologi Permainan</option>
                            <option value="S2 Magister Terapan Teknik Komputer" {{ old('prodi') == 'S2 Magister Terapan Teknik Komputer' ? 'selected' : '' }}>S2 Magister Terapan Teknik Komputer</option>
                        </select>
                        @error('prodi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">
                            Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select id="jabatan" 
                                name="jabatan"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('jabatan') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Jabatan</option>
                            <option value="Kepala Jurusan" {{ old('jabatan') == 'Kepala Jurusan' ? 'selected' : '' }}>Kepala Jurusan</option>
                            <option value="Sekretaris Jurusan" {{ old('jabatan') == 'Sekretaris Jurusan' ? 'selected' : '' }}>Sekretaris Jurusan</option>
                            <option value="Kepala Program Studi" {{ old('jabatan') == 'Kepala Program Studi' ? 'selected' : '' }}>Kepala Program Studi</option>
                            <option value="Dosen" {{ old('jabatan') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="Laboran" {{ old('jabatan') == 'Laboran' ? 'selected' : '' }}>Laboran</option>
                        </select>
                        @error('jabatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Struktural: max 12 SKS | Dosen/Laboran: max 16 SKS</p>
                    </div>

                    <!-- Password -->
                    <div x-data="{ showPassword: false }">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" 
                                   id="password" 
                                   name="password"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('password') border-red-500 @enderror"
                                   placeholder="Masukkan Password" 
                                   required>
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

                    <!-- Foto (Optional) -->
                    <div x-data="{ fileName: '', preview: null }">
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">
                            Foto Profil <span class="text-gray-400">(Opsional)</span>
                        </label>
                        <div class="flex items-start gap-4">
                            <!-- Preview -->
                            <div class="flex-shrink-0">
                                <div class="w-20 h-20 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50">
                                    <template x-if="preview">
                                        <img :src="preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!preview">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    Pilih Foto
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

                <!-- Footer / Action Buttons -->
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button"
                            @click="openModal = false"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                        Batalkan
                    </button>
                    <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Data Dosen
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>