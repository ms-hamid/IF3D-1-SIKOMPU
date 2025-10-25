@props([
    'placeholder' => 'Cari...',
    'width' => 'w-1/4'  {{-- default lebar 25% --}}
])

<div class="relative {{ $width }}">
    {{-- Icon search --}}
    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
        <i class="fa-solid fa-magnifying-glass"></i>
    </span>

    {{-- Input --}}
    <input type="text" 
           {{ $attributes->merge(['class' => 'w-full border border-gray-300 rounded-lg py-2 pr-3 pl-10 text-sm focus:ring-2 focus:ring-indigo-500 outline-none', 'placeholder' => $placeholder]) }}>
</div>
