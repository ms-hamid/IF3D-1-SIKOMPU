<div
    x-show="openDetailModal"
    x-cloak
    @click.self="openDetailModal = false"
    class="fixed inset-0 z-50 flex items-center justify-center px-3 sm:px-4"
>

    {{-- Backdrop --}}
    <div
        x-show="openDetailModal"
        x-transition.opacity
        class="absolute inset-0 bg-slate-800/50 backdrop-blur-sm"
    ></div>

    {{-- Modal --}}
    <div
        x-show="openDetailModal"
        x-transition
        @click.stop
        class="relative w-full max-w-full sm:max-w-lg md:max-w-xl bg-white rounded-xl shadow-xl overflow-hidden"
    >

        {{-- Header --}}
        <div class="bg-blue-700 px-4 sm:px-5 py-3 flex items-center justify-between">
            <h3 class="text-white font-semibold text-base sm:text-lg">
                Hasil Akhir Penilaian Dosen
            </h3>
            <button @click="openDetailModal = false" class="text-white/80 hover:text-white text-lg">
                ✕
            </button>
        </div>

        {{-- Body --}}
        <div class="p-4 sm:p-5">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 sm:p-5 space-y-3 text-base sm:text-sm md:text-base">
                <p class="flex justify-between">
                    <span>Self-Assessment</span>
                    <span class="font-semibold text-green-700">{{ $skorSelfAssessment }}</span>
                </p>
                <p class="flex justify-between">
                    <span>Pendidikan</span>
                    <span class="font-semibold text-green-700">{{ $skorPendidikan }}</span>
                </p>
                <p class="flex justify-between">
                    <span>Sertifikat</span>
                    <span class="font-semibold text-green-700">{{ $skorSertifikat }}</span>
                </p>
                <p class="flex justify-between">
                    <span>Penelitian</span>
                    <span class="font-semibold text-green-700">{{ $skorPenelitian }}</span>
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-4 sm:px-5 py-4 flex justify-end items-center border-t bg-gray-50">
            <span class="bg-green-600 text-white text-sm sm:text-base font-semibold px-4 sm:px-5 py-2 rounded-full">
                Total Akhir : {{ $skorAkhir }} (LULUS)
            </span>
        </div>
    </div>
</div>
