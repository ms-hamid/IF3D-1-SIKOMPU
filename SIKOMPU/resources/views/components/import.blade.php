@extends('layouts.app')

@section('title', 'Import Matakuliah')

@section('content')
<main class="flex-1 px-3 sm:px-6 py-4 space-y-6">
    
    <div class="max-w-2xl mx-auto">
        
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Import Data Matakuliah</h1>
            <p class="text-gray-600 text-sm mt-1">Upload file Excel untuk menambahkan matakuliah dari semua prodi sekaligus</p>
        </div>
        
        {{-- Alert Success --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-300 rounded-md p-4 mb-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-600"></i>
                <span class="text-green-700 text-sm font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif
        
        {{-- Alert Error --}}
        @if(session('error'))
        <div class="bg-red-50 border border-red-300 rounded-md p-4 mb-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-xmark text-red-600"></i>
                <span class="text-red-700 text-sm font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif
        
        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-300 rounded-md p-4 mb-4">
            <div class="flex items-start gap-2">
                <i class="fa-solid fa-triangle-exclamation text-red-600 mt-0.5"></i>
                <div>
                    <p class="text-red-700 text-sm font-medium mb-2">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        
        {{-- Panduan --}}
        <div class="bg-blue-50 border border-blue-300 rounded-md p-4 mb-6">
            <h6 class="text-blue-700 font-semibold mb-2 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-circle-info"></i> Panduan Import
            </h6>
            <ul class="text-blue-800 text-sm space-y-1 ml-6 list-disc">
                <li>File Excel harus memiliki <strong>multiple sheets</strong> sesuai kode prodi (IF, TRM, TRPL, GM, AN, RKS, TP, dll)</li>
                <li>Setiap sheet harus memiliki kolom: <strong>No.</strong>, <strong>Aspek</strong> (Kode - Nama Matakuliah)</li>
                <li>Format Aspek: <code class="bg-blue-100 px-1 rounded">IF101 - Pengantar Proyek Perangkat Lunak</code></li>
                <li>Data matakuliah dimulai dari <strong>baris ke-29</strong></li>
                <li>Sistem akan otomatis skip matakuliah yang sudah ada</li>
            </ul>
        </div>
        
        {{-- Form Upload --}}
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <form action="{{ route('self-assessment.import') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2 text-sm">
                        File Excel <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="flex items-center gap-3">
                        <label class="flex-1 cursor-pointer" id="dropZone">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-indigo-400 transition" id="dropArea">
                                <i class="fa-solid fa-file-excel text-5xl text-green-600 mb-3"></i>
                                <p class="text-gray-600 text-sm mb-1">
                                    <span class="text-indigo-600 font-medium">Klik untuk upload</span> 
                                    atau drag & drop
                                </p>
                                <p class="text-gray-400 text-xs">XLSX atau XLS (Maks. 10MB)</p>
                            </div>
                            <input type="file" name="file" accept=".xlsx,.xls" class="hidden" required id="fileInput">
                        </label>
                    </div>
                    
                    <p class="text-gray-500 text-xs mt-2" id="fileName"></p>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-md hover:bg-indigo-700 transition font-medium text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-upload"></i> Upload & Import
                    </button>
                    <a href="{{ route('self-assessment.index') }}" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2.5 rounded-md hover:bg-gray-300 transition font-medium text-sm text-center flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
        
    </div>
    
</main>

<script>
// File input handling
const fileInput = document.getElementById('fileInput');
const fileName = document.getElementById('fileName');
const dropZone = document.getElementById('dropZone');
const dropArea = document.getElementById('dropArea');

// Show selected filename
fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        fileName.textContent = '📄 File dipilih: ' + file.name;
        fileName.classList.add('text-green-600', 'font-medium');
        dropArea.classList.add('border-green-500', 'bg-green-50');
    }
});

// Drag & Drop functionality
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropArea.classList.add('border-indigo-500', 'bg-indigo-50');
    });
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropArea.classList.remove('border-indigo-500', 'bg-indigo-50');
    });
});

dropZone.addEventListener('drop', function(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    fileInput.files = files;
    
    // Trigger change event
    const event = new Event('change', { bubbles: true });
    fileInput.dispatchEvent(event);
});
</script>
@endsection