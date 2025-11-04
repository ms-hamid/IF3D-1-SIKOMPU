@props(['variant' => 'primary'])

<button
  {{ $attributes->merge([
    'class' => $variant === 'primary'
      ? 'w-full bg-gradient-to-br from-[#1E3A8A] to-[#1E40AF] text-white font-semibold py-2.5 rounded-lg
         hover:from-[#1D4ED8] hover:to-[#2563EB]
         focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2
         transition-colors duration-300 ease-in-out text-sm sm:text-base'
      : 'w-full bg-gray-200 text-gray-800 font-semibold py-2.5 rounded-lg
         hover:bg-gray-300
         focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2
         transition-colors duration-300 ease-in-out text-sm sm:text-base',
  ]) }}
>
  {{ $slot }}
</button>
