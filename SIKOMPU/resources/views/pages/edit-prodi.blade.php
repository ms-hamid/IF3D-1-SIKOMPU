@extends('layouts.app')

@section('title', 'Edit Program Studi')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-600">
        <a href="{{ route('prodi.index') }}" class="hover:text-blue-600 transition">
            <i class="fas fa-graduation-cap mr-1"></i>Manajemen Prodi
        </a>
        <span>/</span>
        <span class="text-gray-900 font-medium">Edit Program Studi</span>
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
            <h2 class="text-lg font-semibold text-gray-800">Edit Program Studi</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi program studi {{ $prodi->nama_prodi }}</p>
        </div>

        <form action="{{ route('prodi.update', $prodi->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Kode Prodi --}}
                <div>
                    <label for="kode_prodi" class="block text-sm font-medium text-gray-700 mb-1">
                        Kode Prodi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="kode_prodi" 
                           name="kode_prodi"
                           value="{{ old('kode_prodi', $prodi->kode_prodi) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('kode_prodi') border-red-500 @enderror"
                           placeholder="Contoh: IF" 
                           required>
                    @error('kode_prodi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenjang --}}
                <div>
                    <label for="jenjang" class="block text-sm font-medium text-gray-700 mb-1">
                        Jenjang <span class="text-red-500">*</span>
                    </label>
                    <select id="jenjang" 
                            name="jenjang"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            required>
                        <option value="">Pilih Jenjang</option>
                        <option value="D3" {{ old('jenjang', $prodi->jenjang) == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="D4" {{ old('jenjang', $prodi->jenjang) == 'D4' ? 'selected' : '' }}>D4</option>
                        <option value="S1" {{ old('jenjang', $prodi->jenjang) == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('jenjang', $prodi->jenjang) == 'S2' ? 'selected' : '' }}>S2</option>
                    </select>
                </div>

                {{-- Nama Prodi --}}
                <div class="md:col-span-2">
                    <label for="nama_prodi" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Program Studi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_prodi" 
                           name="nama_prodi"
                           value="{{ old('nama_prodi', $prodi->nama_prodi) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('nama_prodi') border-red-500 @enderror"
                           placeholder="Contoh: Teknik Informatika" 
                           required>
                    @error('nama_prodi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('prodi.index') }}" 
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