@extends('layouts.app')

@section('title', 'AI Metrics')

@section('content')
<div class="p-8 space-y-10">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">AI Metrics Dashboard</h1>
            <p class="text-gray-500 mt-1">Monitor akurasi dan performa sistem rekomendasi AI</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ai.verifikasi') }}" class="px-4 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 shadow-sm transition">
                <i class="fa-solid fa-check-circle mr-2"></i> Verifikasi Prediksi
            </a>
            <form action="{{ route('ai.refresh') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-sm transition">
                    <i class="fa-solid fa-arrow-rotate-right mr-2"></i> Refresh Data
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <!-- Status Prediksi -->
    <div class="grid grid-cols-3 gap-4">
        @php
            $statusCards = [
                ['label' => 'Total Prediksi', 'value' => $stats['total'], 'icon' => 'fa-database', 'color' => 'bg-blue-100 text-blue-700'],
                ['label' => 'Sudah Diverifikasi', 'value' => $stats['verified'], 'icon' => 'fa-check-circle', 'color' => 'bg-green-100 text-green-700'],
                ['label' => 'Menunggu Verifikasi', 'value' => $stats['pending'], 'icon' => 'fa-clock', 'color' => 'bg-orange-100 text-orange-700'],
            ];
        @endphp

        @foreach ($statusCards as $card)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">{{ $card['label'] }}</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $card['value'] }}</h3>
                </div>
                <div class="{{ $card['color'] }} p-3 rounded-xl text-xl">
                    <i class="fa-solid {{ $card['icon'] }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($stats['verified'] > 0)
    <!-- Metrics Akurasi (Hanya tampil jika ada data terverifikasi) -->
    <div class="grid grid-cols-4 gap-4">
        @php
            $metricsCards = [
                ['label' => 'Akurasi', 'value' => $metrics['accuracy'] . '%', 'icon' => 'fa-bullseye', 'color' => 'bg-green-100 text-green-700'],
                ['label' => 'F1-Score', 'value' => $metrics['f1_score'], 'icon' => 'fa-chart-line', 'color' => 'bg-blue-100 text-blue-700'],
                ['label' => 'Precision', 'value' => $metrics['precision'], 'icon' => 'fa-crosshairs', 'color' => 'bg-yellow-100 text-yellow-700'],
                ['label' => 'Recall', 'value' => $metrics['recall'], 'icon' => 'fa-rotate-left', 'color' => 'bg-purple-100 text-purple-700'],
            ];
        @endphp

        @foreach ($metricsCards as $m)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">{{ $m['label'] }}</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $m['value'] }}</h3>
                </div>
                <div class="{{ $m['color'] }} p-3 rounded-xl text-lg">
                    <i class="fa-solid {{ $m['icon'] }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Confusion Matrix -->
    <div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100">
        <h4 class="text-xl font-bold text-gray-800 mb-4">Confusion Matrix</h4>
        <p class="text-sm text-gray-400 mb-6">Evaluasi hasil prediksi AI berdasarkan data terverifikasi</p>

        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-2 text-center">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-200 p-4 rounded-lg text-sm font-medium text-gray-600 w-1/4">
                            Actual \ Predicted
                        </th>
                        <th class="border border-gray-200 p-4 rounded-lg text-sm font-medium text-gray-600">
                            Diterima
                        </th>
                        <th class="border border-gray-200 p-4 rounded-lg text-sm font-medium text-gray-600">
                            Ditolak
                        </th>
                    </tr>
                </thead>

                <tbody class="text-gray-700 text-base">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="border border-gray-200 p-4 rounded-lg font-medium bg-gray-50">Diterima</td>
                        <td class="border border-gray-200 p-4 rounded-lg bg-green-100 text-green-700 font-semibold text-lg">
                            {{ $confusionMatrix['tp'] }}
                            <div class="text-xs text-gray-500 mt-1">True Positive</div>
                        </td>
                        <td class="border border-gray-200 p-4 rounded-lg bg-red-50 text-red-600 text-lg">
                            {{ $confusionMatrix['fn'] }}
                            <div class="text-xs text-gray-500 mt-1">False Negative</div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition">
                        <td class="border border-gray-200 p-4 rounded-lg font-medium bg-gray-50">Ditolak</td>
                        <td class="border border-gray-200 p-4 rounded-lg bg-red-50 text-red-600 text-lg">
                            {{ $confusionMatrix['fp'] }}
                            <div class="text-xs text-gray-500 mt-1">False Positive</div>
                        </td>
                        <td class="border border-gray-200 p-4 rounded-lg bg-green-100 text-green-700 font-semibold text-lg">
                            {{ $confusionMatrix['tn'] }}
                            <div class="text-xs text-gray-500 mt-1">True Negative</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Keterangan -->
        <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <span class="font-semibold text-green-700">✓ True Positive (TP):</span>
                <span class="text-gray-700"> AI prediksi diterima, aktual diterima</span>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <span class="font-semibold text-green-700">✓ True Negative (TN):</span>
                <span class="text-gray-700"> AI prediksi ditolak, aktual ditolak</span>
            </div>
            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                <span class="font-semibold text-red-700">✗ False Positive (FP):</span>
                <span class="text-gray-700"> AI prediksi diterima, tapi aktual ditolak</span>
            </div>
            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                <span class="font-semibold text-red-700">✗ False Negative (FN):</span>
                <span class="text-gray-700"> AI prediksi ditolak, tapi aktual diterima</span>
            </div>
        </div>
    </div>

    @else
    <!-- Pesan jika belum ada data terverifikasi -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
        <i class="fa-solid fa-exclamation-triangle text-yellow-500 text-4xl mb-3"></i>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Data Terverifikasi</h3>
        <p class="text-gray-600 mb-4">Metrics akan ditampilkan setelah Anda memverifikasi hasil prediksi AI</p>
        <a href="{{ route('ai.verifikasi') }}" class="inline-block px-6 py-3 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition">
            <i class="fa-solid fa-check-circle mr-2"></i> Mulai Verifikasi
        </a>
    </div>
    @endif

</div>
@endsection