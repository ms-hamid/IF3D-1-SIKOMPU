@extends('layouts.app')

@section('title', 'Verifikasi Prediksi AI')

@section('content')
<div class="p-8 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Verifikasi Prediksi AI</h1>
            <p class="text-gray-500 mt-1">Konfirmasi hasil aktual untuk mengukur akurasi sistem AI</p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('ai.refresh') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-sm transition">
                    <i class="fa-solid fa-sync mr-2"></i> Sync dari Hasil Rekomendasi
                </button>
            </form>
            <a href="{{ route('ai.performa') }}" class="px-4 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-700 shadow-sm transition">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if($predictions->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <i class="fa-solid fa-check-circle text-green-500 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Semua Prediksi Sudah Diverifikasi</h3>
        <p class="text-gray-600">Tidak ada prediksi yang menunggu verifikasi saat ini</p>
    </div>
    @else

    <!-- Tabel Verifikasi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    Daftar Prediksi Menunggu Verifikasi
                    <span class="ml-2 text-sm font-normal text-gray-500">({{ $predictions->total() }} total)</span>
                </h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dosen
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prediksi AI
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Skor Confidence
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mata Kuliah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Prediksi
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hasil Aktual
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($predictions as $pred)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-semibold">
                                        {{ substr($pred->dosen->nama_lengkap, 0, 2) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $pred->dosen->nama_lengkap }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $pred->dosen->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pred->predicted_status == 'diterima')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fa-solid fa-check mr-1"></i> Diterima
                            </span>
                            @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fa-solid fa-times mr-1"></i> Ditolak
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($pred->confidence_score, 2) }}</div>
                                <div class="ml-2 w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min($pred->confidence_score, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $pred->features_used['kode_matkul'] ?? '-' }}
                            </div>
                            @if(isset($pred->features_used['ranking']))
                            <div class="text-xs text-gray-500">
                                Ranking #{{ $pred->features_used['ranking'] }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pred->predicted_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <form action="{{ route('ai.verify', $pred->id) }}" method="POST" class="inline-flex gap-2">
                                @csrf
                                <button type="submit" name="actual_status" value="diterima" 
                                        class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs font-medium transition">
                                    <i class="fa-solid fa-check"></i> Terima
                                </button>
                                <button type="submit" name="actual_status" value="ditolak"
                                        class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-xs font-medium transition">
                                    <i class="fa-solid fa-times"></i> Tolak
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $predictions->links() }}
        </div>
    </div>

    @endif

</div>
@endsection