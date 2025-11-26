<div class="bg-gradient-to-br from-[#1E3A8A] to-[#1E40AF] text-white rounded-2xl p-5 flex justify-between items-center mb-6">
  <div class="max-w-xl">
    <h3 class="font-semibold text-lg">Sistem Penentuan Koordinator & Pengampu Dosen</h3>
    <p class="text-sm text-blue-100 mb-3">
      Kelola dan optimalkan distribusi beban mengajar dosen dengan algoritma cerdas
    </p>
    
    @if(Auth::user()->isStruktural())
      {{-- Tombol untuk Struktural --}}
      <a href="{{ route('hasil.rekomendasi') }}" class="inline-block bg-white text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-50 transition">
        Generate Rekomendasi Semester Ini
      </a>
    @else
      {{-- Tombol untuk Dosen/Laboran --}}
      <a href="{{ route('self-assessment.index') }}" class="inline-block bg-white text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-50 transition">
        Isi Self-Assessment
      </a>
    @endif
  </div>

  <!-- Gambar hanya tampil di tablet dan desktop -->
  <img 
    src="{{ asset('images/div.png') }}" 
    alt="Robot Icon" 
    class="hidden md:block w-16 h-16 object-contain"
  >
</div>