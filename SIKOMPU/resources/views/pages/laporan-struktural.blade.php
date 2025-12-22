@extends('layouts.app')

@section('title', 'Laporan')
@section('page_title', 'Laporan')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false, deleteTarget: '' }" @close-modal.window="openModal = false">
    {{-- Header "Pusat Laporan" --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pusat Laporan</h2>
            <p class="text-sm text-gray-500">Generate dan unduh berbagai jenis laporan sistem</p>
        </div>
        <div class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full shadow-sm">
            Semester Ganjil 2024/2025
        </div>
    </div>

    {{-- Card Laporan Akademik (Horizontal) --}}
    <div class="bg-white p-6 shadow-xl rounded-xl border border-gray-100">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

            {{-- Kiri: Informasi --}}
            <div class="space-y-3 flex-1">
                <div class="flex items-center space-x-3 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Rekap Final Pengampu</h3>
                </div>

                <p class="text-sm text-gray-500">
                    Laporan lengkap daftar pengampu mata kuliah per semester
                </p>

                <span class="text-xs text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Estimasi 5 menit
                </span>
            </div>

            {{-- Kanan: Aksi --}}
            <div class="flex flex-col sm:flex-row items-stretch gap-3 w-full lg:w-auto">
                <select id="semester-select"
                    class="border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="Ganjil 2024/2025">Ganjil 2024/2025</option>
                    <option value="Genap 2024/2025">Genap 2024/2025</option>
                    <!-- Tambah opsi lain jika perlu -->
                </select>

                <a id="pdf-link" href="{{ route('laporan.rekap-pengampu.pdf', ['semester' => 'Ganjil 2024/2025']) }}"
                    target="_blank"
                    class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-md">
                    PDF
                </a>

                <a id="excel-link" href="{{ route('laporan.rekap-pengampu.excel', ['semester' => 'Ganjil 2024/2025']) }}"
                    class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-md">
                    Excel
                </a>
            </div>

        </div>
    </div>
</main>

<script>
    document.getElementById('semester-select').addEventListener('change', function() {
        const selectedSemester = this.value;
        const pdfLink = document.getElementById('pdf-link');
        const excelLink = document.getElementById('excel-link');
        
        // Update href dengan semester baru
        pdfLink.href = '{{ route("laporan.rekap-pengampu.pdf") }}?semester=' + encodeURIComponent(selectedSemester);
        excelLink.href = '{{ route("laporan.rekap-pengampu.excel") }}?semester=' + encodeURIComponent(selectedSemester);
    });
</script>
@endsection