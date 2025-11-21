@extends('layouts.app')

@section('title', 'Ganti Password')
@section('page_title', 'Ganti Password')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ 
    showToast: false, 
    toastMessage: '',
    toastType: 'success', // success atau error

    // Fungsi untuk menampilkan toast
    showNotification(message, type = 'success') {
        this.toastMessage = message;
        this.toastType = type;
        this.showToast = true;
        setTimeout(() => this.showToast = false, 3000); // Sembunyikan setelah 3 detik
    }
}">

    {{-- Container Tengah, Maksimum Lebar XL --}}
    <div class="max-w-xl mx-auto"> 

        {{-- Card Utama --}}
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200">
            
            {{-- Header Card --}}
            <h1 class="text-xl font-bold text-gray-800 mb-2">Ganti Password</h1>
            <p class="text-gray-500 text-sm mb-6 border-b pb-4">
                Perbarui kata sandi Anda untuk menjaga keamanan akun.
            </p>

            <form action="{{ url('/ganti_password') }}" method="POST" class="space-y-6"
                  @submit.prevent="
                    // Simulasi proses ganti password berhasil
                    // Ganti ini dengan logika pengiriman form Anda
                    showNotification('Kata sandi berhasil diperbarui!', 'success'); 
                    // Jika gagal, gunakan: showNotification('Gagal menyimpan. Password lama salah.', 'error');
                  ">
                @csrf

                {{-- Input Password Lama dan Baru (Grid 2 Kolom) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    {{-- Input Password Lama --}}
                    <div>
                        <label for="password_lama" class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                        <input 
                            type="password" 
                            name="password_lama" 
                            id="password_lama" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm 
                            focus:ring-blue-600 focus:border-blue-600 shadow-sm transition" 
                            placeholder="Masukkan password lama"
                            required
                        >
                    </div>
                    
                    {{-- Input Password Baru --}}
                    <div>
                        <label for="password_baru" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input 
                            type="password" 
                            name="password_baru" 
                            id="password_baru" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm 
                            focus:ring-blue-600 focus:border-blue-600 shadow-sm transition" 
                            placeholder="Masukkan password baru"
                            required
                        >
                    </div>
                </div>

                {{-- Input Konfirmasi Password Baru (Satu Baris Penuh) --}}
                <div>
                    <label for="konfirmasi_password_baru" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input 
                        type="password" 
                        name="konfirmasi_password_baru" 
                        id="konfirmasi_password_baru" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm 
                        focus:ring-blue-600 focus:border-blue-600 shadow-sm transition" 
                        placeholder="Ulangi password baru"
                        required
                    >
                </div>

                {{-- Tombol Simpan --}}
                <div class="pt-4">
                    <button 
                        type="submit" 
                        class="w-full flex justify-center items-center gap-2 px-5 py-2.5 bg-blue-800 hover:bg-blue-700 rounded-lg text-white font-medium text-base transition shadow-md">
                        <i class="fa-solid fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- POP-UP NOTIFIKASI (TOAST) --}}
    <div 
        x-show="showToast"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-4 right-4 z-50 p-4 rounded-lg shadow-xl max-w-sm w-full transition transform"
        :class="{ 
            'bg-green-600': toastType === 'success',
            'bg-red-600': toastType === 'error'
        }">
        <div class="flex items-center space-x-3">
            <i class="text-white text-xl" 
                :class="{ 
                    'fa-solid fa-check-circle': toastType === 'success',
                    'fa-solid fa-circle-exclamation': toastType === 'error'
                }"></i>
            <p class="text-white text-sm font-medium" x-text="toastMessage"></p>
        </div>
    </div>
    
</main>
@endsection
