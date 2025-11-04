@props(['id', 'type' => 'text', 'label', 'placeholder' => ''])

<div class="w-full relative">
  <div class="relative">
    @if (isset($icon))
      <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 transition-colors duration-300 peer-focus:text-blue-500 pointer-events-none">
        {{ $icon }}
      </span>
    @endif

    <input
      type="{{ $type }}"
      name="{{ $id }}"
      id="{{ $id }}"
      placeholder="{{ $label }}"
      {{ $attributes->merge([
        'class' =>
          'peer w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 shadow-sm text-sm sm:text-base transition-all duration-300',
      ]) }}
      required
    >

    {{-- Label hanya muncul saat input kosong --}}
    <label for="{{ $id }}"
      class="absolute left-10 top-2 text-gray-400 text-sm transition-all duration-300 ease-in-out
             peer-placeholder-shown:opacity-100 peer-focus:opacity-0 peer-valid:opacity-0 opacity-0
             bg-white px-1 rounded pointer-events-none">
      {{ $label }}
    </label>
  </div>
</div>
