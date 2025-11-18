@props([
  'id',
  'type' => 'text',
  'label',
  'placeholder' => '',
  'icon' => null,
])

<div class="w-full">
  {{-- Label --}}
  <label for="{{ $id }}" class="block text-sm font-semibold text-gray-700 mb-2 tracking-wide">
    {{ $label }}
  </label>

  {{-- Input Container --}}
  <div class="relative group">
    {{-- Icon (optional) --}}
    @if ($icon)
    @if ($icon)
    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 
            group-focus-within:text-blue-500 transition-all duration-300 ease-in-out transform group-focus-within:scale-110">
      {{ $icon }}
    </span>
@endif

    @endif

    {{-- Input Field --}}
    <input
      type="{{ $type }}"
      name="{{ $id }}"
      id="{{ $id }}"
      placeholder="{{ $placeholder ?: $label }}"
      {{ $attributes->merge([
        'class' =>
          'peer w-full rounded-xl border border-gray-300 bg-white text-gray-900 
          placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 
          transition-all duration-300 shadow-sm hover:shadow-md
          '.($icon ? 'pl-10 pr-5' : 'px-4').' py-3 text-sm sm:text-base'
      ]) }}
      required
    />

    
  </div>
</div>
