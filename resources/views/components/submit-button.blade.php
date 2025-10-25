@props([
    'label' => 'Submit',        {{-- Teks tombol --}}
    'color' => 'bg-indigo-600', {{-- Warna bg default --}}
    'hover' => 'hover:bg-indigo-700',  {{-- Hover default --}}
])

<button type="submit" {{ $attributes->merge(['class' => "flex items-center gap-2 text-white px-4 py-2 rounded-lg transition $color $hover"]) }}>
    <i class="fa-solid fa-paper-plane"></i>
    {{ $label }}
</button>
