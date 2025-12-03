@extends('layouts.app')

@section('title', 'AI Metrics')

@section('content')
<div class="p-8 space-y-10">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">AI Metrics</h1>
            <p class="text-gray-500 mt-1">Analisis akurasi dan kualitas prediksi sistem AI terhadap dosen</p>
        </div>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-sm transition">
            <i class="fa-solid fa-arrow-rotate-right mr-2"></i> Refresh Data
        </button>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-4 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label' => 'Akurasi', 'value' => '94%', 'icon' => 'fa-bullseye', 'color' => 'bg-green-100 text-green-700'],
                ['label' => 'F1-Score', 'value' => '0.92', 'icon' => 'fa-chart-line', 'color' => 'bg-blue-100 text-blue-700'],
                ['label' => 'Precision', 'value' => '0.90', 'icon' => 'fa-crosshairs', 'color' => 'bg-yellow-100 text-yellow-700'],
                ['label' => 'Recall', 'value' => '0.94', 'icon' => 'fa-rotate-left', 'color' => 'bg-purple-100 text-purple-700'],
            ];
        @endphp

        @foreach ($stats as $s)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">{{ $s['label'] }}</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $s['value'] }}</h3>
                </div>
                <div class="{{ $s['color'] }} p-3 rounded-xl text-lg">
                    <i class="fa-solid {{ $s['icon'] }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

   <!-- Confusion Matrix -->
{{-- 3. FILTER & PENCARIAN --}}
<div class="bg-white p-6 shadow-lg rounded-xl border border-gray-100 mt-6">
    <h4 class="text-xl font-bold text-gray-800 mb-4">confusion metrics</h4>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
        <span class="text-sm text-gray-400">Evaluasi hasil prediksi AI</span>
    </div>

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
                    <td class="border border-gray-200 p-4 rounded-lg bg-green-100 text-green-700 font-semibold text-lg">45</td>
                    <td class="border border-gray-200 p-4 rounded-lg bg-red-50 text-red-600 text-lg">5</td>
                </tr>

                <tr class="hover:bg-gray-50 transition">
                    <td class="border border-gray-200 p-4 rounded-lg font-medium bg-gray-50">Ditolak</td>
                    <td class="border border-gray-200 p-4 rounded-lg bg-red-50 text-red-600 text-lg">3</td>
                    <td class="border border-gray-200 p-4 rounded-lg bg-green-100 text-green-700 font-semibold text-lg">47</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</script>
@endsection
