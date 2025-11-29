@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-5xl mx-auto py-6 px-4">
    
    {{-- Alert Success --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <i class="fa-solid fa-circle-check mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
    @endif

    {{-- Header Card --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-20 h-20 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-3xl overflow-hidden">
                @if($user->foto)
                    <img src="{{ Storage::url($user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                @endif
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->nama_lengkap }}</h2>
                <p class="text-gray-600">{{ $user->jabatan }}</p>
                <p class="text-sm text-gray-500">NIDN: {{ $user->nidn }}</p>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div x-data="{ activeTab: 'profil' }" class="bg-white rounded-lg shadow-sm border border-gray-200">
        
        {{-- Tab Headers --}}
        <div class="border-b border-gray-200">
            <div class="flex">
                <button 
                    @click="activeTab = 'profil'"
                    :class="activeTab === 'profil' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition">
                    <i class="fa-solid fa-user mr-2"></i>
                    Edit Profil
                </button>
                <button 
                    @click="activeTab = 'password'"
                    :class="activeTab === 'password' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition">
                    <i class="fa-solid fa-key mr-2"></i>
                    Ganti Password
                </button>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="p-6">
            
            {{-- TAB 1: Edit Profil --}}
            <div x-show="activeTab === 'profil'" x-transition>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Profil</h3>
                
                <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input 
                                type="text" 
                                name="nama_lengkap" 
                                value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_lengkap') border-red-500 @enderror"
                                required>
                            @error('nama_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIDN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIDN</label>
                            <input 
                                type="text" 
                                name="nidn" 
                                value="{{ old('nidn', $user->nidn) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nidn') border-red-500 @enderror"
                                required>
                            @error('nidn')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jabatan (Read Only) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                            <input 
                                type="text" 
                                value="{{ $user->jabatan }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                readonly>
                            <p class="text-xs text-gray-500 mt-1">Tidak dapat diubah</p>
                        </div>

                        {{-- Prodi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                            <input 
                                type="text" 
                                name="prodi" 
                                value="{{ old('prodi', $user->prodi) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prodi') border-red-500 @enderror">
                            @error('prodi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Foto Profil --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                        <input 
                            type="file" 
                            name="foto" 
                            accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('foto') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                        @error('foto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Button Submit --}}
                    <div class="mt-6">
                        <button 
                            type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fa-solid fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- TAB 2: Ganti Password --}}
            <div x-show="activeTab === 'password'" x-transition>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Password</h3>
                
                <form action="{{ route('ganti_password.update') }}" method="POST" class="max-w-xl">
                    @csrf

                    {{-- Password Lama --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password Lama <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password_lama" 
                                id="password_lama"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password_lama') border-red-500 @enderror"
                                required>
                            <button 
                                type="button"
                                onclick="togglePassword('password_lama')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="fa-solid fa-eye" id="icon_password_lama"></i>
                            </button>
                        </div>
                        @error('password_lama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password_baru" 
                                id="password_baru"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password_baru') border-red-500 @enderror"
                                required>
                            <button 
                                type="button"
                                onclick="togglePassword('password_baru')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="fa-solid fa-eye" id="icon_password_baru"></i>
                            </button>
                        </div>
                        @error('password_baru')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password_baru_confirmation" 
                                id="password_baru_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <button 
                                type="button"
                                onclick="togglePassword('password_baru_confirmation')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="fa-solid fa-eye" id="icon_password_baru_confirmation"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Alert Info --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <i class="fa-solid fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Tips Password Aman:</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Minimal 8 karakter</li>
                                    <li>Kombinasi huruf besar, kecil, angka & simbol</li>
                                    <li>Jangan gunakan info pribadi yang mudah ditebak</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Button Submit --}}
                    <div>
                        <button 
                            type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fa-solid fa-check mr-2"></i>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('icon_' + fieldId);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection