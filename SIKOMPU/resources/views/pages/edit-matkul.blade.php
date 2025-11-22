@extends('layouts.app')

@section('title', 'Edit Mata Kuliah')

@section('content')
<main class="flex-1 p-4 sm:p-6">

    {{-- Breadcrumb --}}
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('matakuliah.index') }}" class="text-gray-600 hover:text-blue-600 inline-flex items-center text-sm font-medium">
                    <i class="fa-solid fa-book mr-2"></i>
                    Mata Kuliah
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-2"></i>
                    <span class="text-gray-500 text-sm font-medium">Edit Mata Kuliah</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
            
            {{-- Header --}}
            <h2 class="text-xl font-semibold text-gray-800 mb-6">
                Formulir Edit Mata Kuliah
            </h2>

            {{-- Alert Error --}}
            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <i class="fa-solid fa-circle-exclamation text-red-500 mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-red-800 mb-2">Terdapat kesalahan pada form:</p>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            {{-- Form Body --}}
            <form action="{{ route('matakuliah.update', $mataKuliah->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Nama Mata Kuliah --}}
                <div>
                    <label class="text-sm text-gray-700">Nama Mata Kuliah</label>
                    <input 
                        type="text" 
                        name="nama_mk"
                        value="{{ old('nama_mk', $mataKuliah->nama_mk) }}"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    />
                </div>

                {{-- Kode Mata Kuliah --}}
                <div>
                    <label class="text-sm text-gray-700">Kode Mata Kuliah</label>
                    <input 
                        type="text" 
                        name="kode_mk"
                        value="{{ old('kode_mk', $mataKuliah->kode_mk) }}"
                        maxlength="20"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    />
                </div>

                {{-- Jumlah SKS --}}
                <div>
                    <label class="text-sm text-gray-700">Jumlah SKS</label>
                    <input 
                        type="number" 
                        name="sks"
                        value="{{ old('sks', $mataKuliah->sks) }}"
                        min="1"
                        max="6"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    />
                </div>

                {{-- Jumlah Sesi --}}
                <div>
                    <label class="text-sm text-gray-700">Jumlah Sesi</label>
                    <input 
                        type="number" 
                        name="sesi"
                        value="{{ old('sesi', $mataKuliah->sesi) }}"
                        min="1"
                        max="20"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    />
                </div>

                {{-- Program Studi --}}
                <div>
                    <label class="text-sm text-gray-700">Program Studi Pemilik</label>
                    <select 
                        name="prodi_id" 
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach($prodiList as $p)
                        <option value="{{ $p->id }}" {{ old('prodi_id', $mataKuliah->prodi_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_prodi }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Semester --}}
                <div>
                    <label class="text-sm text-gray-700">Semester</label>
                    <select 
                        name="semester" 
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                        <option value="">Ganjil / Genap</option>
                        @for($i=1; $i<=8; $i++)
                        <option value="{{ $i }}" {{ old('semester', $mataKuliah->semester) == $i ? 'selected' : '' }}>
                            Semester {{ $i }}
                        </option>
                        @endfor
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-between pt-4">
                    <a 
                        href="{{ route('matakuliah.index') }}"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition">
                        Batalkan
                    </a>

                    <button 
                        type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Update Mata Kuliah
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>
@endsection