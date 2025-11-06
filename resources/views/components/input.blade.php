@props([
  'id',
  'type' => 'text',
  'label',
  'placeholder' => '',
  'icon' => null,
])

<div class="w-full">
  <div class="relative group">
    {{-- Icon (optional) --}}
    @if ($icon)
      <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 
                   group-focus-within:text-blue-500 transition-colors duration-300">
        {{ $icon }}
      </span>
    @endif

    {{-- Input Field --}}
    <input
      type="{{ $type }}"
      name="{{ $id }}"
      id="{{ $id }}"
      placeholder=" "
      {{ $attributes->merge([
        'class' =>
          'peer w-full rounded-lg border border-gray-300 bg-white 
          text-gray-900 placeholder-transparent focus:outline-none 
          focus:border-blue-500 focus:ring-2 focus:ring-blue-300 
          transition-all duration-300
          '.($icon ? 'pl-10 pr-3' : 'px-4').' py-3 text-sm sm:text-base'
      ]) }}
      required
    />

    {{-- Floating Label --}}
    <label for="{{ $id }}"
      class="absolute text-gray-500 text-sm 
             left-{{ $icon ? '10' : '4' }} top-3.5 px-1 bg-white
             transition-all duration-300 ease-in-out 
             peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-gray-400 
             peer-placeholder-shown:text-sm
             peer-focus:top-0 peer-focus:-translate-y-1/2 peer-focus:text-xs peer-focus:text-blue-600
             peer-valid:top-0 peer-valid:-translate-y-1/2 peer-valid:text-xs peer-valid:text-blue-600">
      {{ $label }}
    </label>

    {{-- Blue Animated Underline --}}
    <span class="absolute inset-x-0 bottom-0 h-[2px] bg-gradient-to-r from-blue-400 to-blue-600 
                 scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded"></span>
  </div>
</div>
