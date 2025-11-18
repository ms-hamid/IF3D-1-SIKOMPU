@props([
    'placeholder' => 'Cari...',
    'width' => 'w-full sm:w-1/3 md:w-1/4'  {{-- Responsif --}}
])

<div class="relative {{ $width }}">
    {{-- Icon Search --}}
    <span
        class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 transition-colors duration-300 
               peer-focus:text-indigo-600">
        <i class="fa-solid fa-magnifying-glass text-[13px]"></i>
    </span>

    {{-- Input --}}
    <input 
        type="text" 
        {{ $attributes->merge([
            'class' => '
                peer w-full pl-9 pr-3 py-1.5 text-[13px] rounded-lg border border-gray-300 
                text-gray-700 placeholder-gray-400 bg-white shadow-sm 
                focus:border-indigo-500 focus:ring-2 focus:ring-indigo-300 focus:ring-offset-0
                outline-none transition-all duration-300 ease-in-out
            ',
            'placeholder' => $placeholder,
        ]) }}
    >
</div>
