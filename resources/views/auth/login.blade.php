@extends('layouts.guest')

@section('content')
  <!-- Logo -->
  <x-logo />

  <!-- Form Login -->
  <form action="#" method="POST" class="space-y-5">
    @csrf

    <!-- Username -->
    <div>
      <label for="username" class="block text-gray-700 font-medium mb-1">Username</label>
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <!-- Ikon user -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5.121 17.804A9.004 9.004 0 0112 15c2.485 0 4.735.995 6.364 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </span>
        <input 
          type="text" 
          name="username" 
          id="username" 
          placeholder="Masukkan username"
          class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 shadow-sm"
          required
        >
      </div>
    </div>

    <!-- Password -->
    <div>
      <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <!-- Ikon gembok -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 11V9a5 5 0 10-10 0v2" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 11h14v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 14a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
            </svg>
        </span>
        <input 
          type="password" 
          name="password" 
          id="password" 
          placeholder="Masukkan password"
          class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 shadow-sm"
          required
        >
      </div>
    </div>

    <!-- Tombol -->
    <button 
      type="submit"
      class="w-full bg-gradient-to-br from-[#1E3A8A] to-[#1E40AF] text-white font-semibold py-2.5 rounded-lg hover:opacity-90 transition"
    >
      Masuk Sistem
    </button>
  </form>

  <!-- Footer -->
  <p class="text-xs text-gray-400 text-center mt-6">
    © {{ date('Y') }} Politeknik Negeri Batam. All rights reserved.
  </p>
@endsection
