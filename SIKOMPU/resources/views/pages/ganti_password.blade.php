@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')
<main class="flex-1 p-6 space-y-8">

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">Ganti Password</h1>
      <p class="text-gray-500 text-sm mt-1">Perbarui kata sandi akun Anda untuk menjaga keamanan akun.</p>
    </div>
  </div>

  {{-- Card Ganti Password --}}
  <div class="bg-gray-100 border rounded shadow-sm p-8 w-full">
    <form class="w-full">
      {{-- Grid dua kolom di layar besar --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Password Lama --}}
        <div>
          <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
            Password Lama
          </label>
          <input
            type="password"
            id="current_password"
            name="current_password"
            placeholder="Masukkan password lama"
            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          />
        </div>

        {{-- Password Baru --}}
        <div>
          <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
            Password Baru
          </label>
          <input
            type="password"
            id="new_password"
            name="new_password"
            placeholder="Masukkan password baru"
            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          />
        </div>

        {{-- Konfirmasi Password Baru --}}
        <div class="md:col-span-2">
          <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
            Konfirmasi Password Baru
          </label>
          <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            placeholder="Ulangi password baru"
            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          />
        </div>
      </div>

      {{-- Tombol Simpan --}}
      <div class="mt-6 md:col-span-2">
          <button
              type="submit"
              class="w-full bg-[#1E40AF] text-white px-4 py-3 rounded-xl hover:bg-blue-700 transition-all text-sm font-medium"
          >
              Simpan Perubahan
          </button>
      </div>
    </form>
  </div>

</main>
@endsection
