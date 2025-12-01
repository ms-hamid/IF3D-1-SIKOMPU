@extends('layouts.guest')

@section('content')
    <!-- Logo -->
    <x-logo />

    <!-- Tampilkan error jika ada -->
    @if ($errors->any())
        <div class="w-full max-w-sm mx-auto mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tampilkan success message jika ada -->
    @if (session('success'))
        <div class="w-full max-w-sm mx-auto mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Form Login -->
    <form action="{{ route('login.post') }}" method="POST" class="w-full max-w-sm mx-auto space-y-4">
        @csrf

        <x-input 
            id="username" 
            name="username"
            label="Username" 
            placeholder="Masukkan NIDN"
            value="{{ old('username') }}"
        >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A9.004 9.004 0 0112 15c2.485 0 4.735.995 6.364 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </x-slot:icon>
        </x-input>

        <x-input 
            id="password" 
            name="password"
            type="password" 
            label="Password" 
            placeholder="Masukkan password"
        >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 11V9a5 5 0 10-10 0v2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 11h14v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
            </x-slot:icon>
        </x-input>

        <!-- Tombol -->
        <x-button class="w-full">Masuk Sistem</x-button>
    </form>

    <!-- Footer -->
    <x-auth-footer />
@endsection
