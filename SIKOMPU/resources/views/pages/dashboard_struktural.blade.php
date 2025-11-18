@extends('layouts.app')

@section('title', 'Dashboard Struktural')

@section('content')
{{-- Tambahkan kelas opacity-0, transition-opacity, dan duration-1000 pada <main> --}}
<main id="main-content" class="flex-1 px-3 sm:px-6 py-4 space-y-6 opacity-0 transition-opacity duration-1000">

  {{-- Banner --}}
  <x-dashboard.banner />

  {{-- Greeting --}}
  <section class="border-b border-gray-300 pb-3">
    <div class="flex items-center space-x-3">
      <div class="bg-green-100 p-2 rounded-lg">
        <i class="fa-solid fa-chart-line text-green-600"></i>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-gray-800">
          Selamat Datang, Dr. Ahmad Wijaya
        </h3>
        <p class="text-gray-500 text-sm flex items-center mt-1">
          <i class="fa-regular fa-calendar text-gray-400 mr-1"></i>
          Minggu, 28 September 2025
        </p>
      </div>
    </div>
  </section>
 {{-- Statistik ringkas --}}
 <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-2 mb-4">
  <div class="items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 text-left rounded-lg hover:bg-gray-100  transition">
    <p class="text-gray-500 text-sm font-medium">Total Pengguna Aktif</p>
    <h3 class="text-3xl font-bold text-gray-800">{{ $totalPengguna ?? '48' }}</h3> 
    <p class="text-xs text-green-600">+2 dari semester lalu</p>
  </div>
  <div class="items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 text-left rounded-lg hover:bg-gray-100  transition">
    <p class="text-gray-500 text-sm font-medium">Total Dosen</p>
    <h3 class="text-3xl font-bold text-gray-800">{{ $totalDosen ?? '128' }}</h3> 
    <p class="text-xs text-gray-600">Dosen Terdaftar</p>
  </div>
  <div class="items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 text-left rounded-lg hover:bg-gray-100  transition">
    <p class="text-gray-500 text-sm font-medium">Total Mata Kuliah</p>
    <h3 class="text-3xl font-bold text-gray-800">{{ $Total ?? '90' }}</h3> 
    <p class="text-xs text-gray-600">Mata Kuliah Terdaftar</p>
  </div>
</div>

  {{-- Baris kedua: Sertifikat & Penelitian --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2 w-full">
    {{-- Sertifikat Terdaftar --}}
    <div class="flex justify-between items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 rounded-lg hover:bg-gray-100  transition">
      <div>
        <p class="text-gray-500 text-sm font-medium flex items-center gap-2">
          <i class="fa-solid fa-medal text-yellow-500"></i> Sertifikat Terdaftar
        </p>
        <h3 class="text-3xl font-bold text-gray-800">{{ $totalSertifikat ?? '4' }}</h3>
        <p class="text-xs text-green-600">↑ Sertifikat kompetensi</p>
      </div>
    </div>

    {{-- Penelitian Terdaftar --}}
    <div class="flex justify-between items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 rounded-lg hover:bg-gray-100  transition">
      <div>
        <p class="text-gray-500 text-sm font-medium flex items-center gap-2">
          <i class="fa-solid fa-book-open text-indigo-600"></i> Penelitian Terdaftar
        </p>
        <h3 class="text-3xl font-bold text-gray-800">{{ $totalPenelitian ?? '3' }}</h3>
        <p class="text-xs text-green-600">↑ Karya penelitian</p>
      </div>
    </div>
  </div>
</section>


{{-- Alert Peringatan --}}
<div class="flex items-center p-4 text-white bg-red-500 border border-red-600 rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
  <i class="fa-solid fa-triangle-exclamation text-xl mr-3"></i>
  <p class="text-sm">
    <strong>Peringatan Beban Mengajar</strong> — Terdapat <b>7 dosen</b> dengan beban mengajar melebihi batas maksimal (16 SKS).
    <a href="#" class="text-white underline hover:text-dark red-100 ml-1 rounded-lg hover:bg-gray-300  transition">Lihat detail →</a>
  </p>
</div>
    {{-- BARIS 2: 2 KOLOM --}}
     <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2 gap-2 mb-2">
    {{-- Aktivitas Terbaru --}}
    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-xl shadow-sm p-5 rounded-lg hover:bg-gray-100  transition">
      <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h2>
        <a href="#" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
      </div>
      <ul class="space-y-4">
        <li class="flex items-start space-x-3">
          <i class="fa-solid fa-circle-check text-green-500 mt-1"></i>
          <div>
            <p class="text-sm text-gray-700">Dr. Sarah Putri telah mengupdate self-assessment untuk 12 mata kuliah</p>
            <span class="text-xs text-gray-400">2 jam yang lalu</span>
          </div>
        </li>
        <li class="flex items-start space-x-3">
          <i class="fa-solid fa-certificate text-yellow-500 mt-1"></i>
          <div>
            <p class="text-sm text-gray-700">Prof. Budi Santoso mengupload 3 sertifikat kompetensi baru</p>
            <span class="text-xs text-gray-400">5 jam yang lalu</span>
          </div>
        </li>
        <li class="flex items-start space-x-3">
          <i class="fa-solid fa-bolt text-blue-500 mt-1"></i>
          <div>
            <p class="text-sm text-gray-700">Rekomendasi koordinator berhasil di-generate untuk 45 mata kuliah</p>
            <span class="text-xs text-gray-400">1 hari yang lalu</span>
          </div>
        </li>
        <li class="flex items-start space-x-3">
          <i class="fa-solid fa-triangle-exclamation text-red-500 mt-1"></i>
          <div>
            <p class="text-sm text-gray-700">Sistem mendeteksi 7 dosen dengan beban melebihi batas maksimal</p>
            <span class="text-xs text-gray-400">2 hari yang lalu</span>
          </div>
        </li>
      </ul>
    </div>
    {{-- Aksi Cepat --}}
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-4">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
      <div class="space-y-4">
        <button class="w-full flex items-center justify-between bg-blue-700 text-white px-4 py-2.5 rounded-lg hover:bg-blue-300 transition">
          <span><i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Generate Rekomendasi</span>
          <i class="fa-solid fa-arrow-right"></i>
        </button>

        <button class="w-full flex items-center justify-between bg-gray-100 text-gray-800 px-4 py-2.5 rounded-lg hover:bg-gray-300  transition">
          <span><i class="fa-solid fa-user-plus mr-2"></i> Tambah Dosen</span>
          <i class="fa-solid fa-arrow-right text-gray-500"></i>
        </button>

        <button class="w-full flex items-center justify-between bg-gray-100 text-gray-800 px-4 py-2.5 rounded-lg hover:bg-gray-300 transition">
          <span><i class="fa-solid fa-book-medical mr-2"></i> Tambah Mata Kuliah</span>
          <i class="fa-solid fa-arrow-right text-gray-500"></i>
        </button>

        <button class="w-full flex items-center justify-between bg-gray-100 text-gray-800 px-4 py-2.5 rounded-lg hover:bg-gray-300 transition">
          <span><i class="fa-solid fa-file-export mr-2"></i> Ekspor Laporan</span>
          <i class="fa-solid fa-arrow-right text-gray-500"></i>
        </button>
      </div>
    </div>
  </div>
</div>

  </div>
</div>

{{-- KOLOM KANAN (Dua Chart) --}}
<div class="lg:col-span-1 space-y-6">
        
  {{-- Bar Chart: Distribusi SKS per Dosen --}}
  <div class="bg-white p-5 shadow-sm rounded-xl border border-gray-100">
      <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-800">Distribusi SKS per Dosen</h3>
          <select class="border border-gray-300 text-sm rounded-lg px-3 py-1 focus:ring-blue-500 focus:border-blue-500">
              <option>Semester Ganjil 2024/2025</option>
              <option>Semester Genap 2024/2025</option>
          </select>
      </div>
      <div class="h-60">
          <canvas id="chartSKS"></canvas>
      </div>
  </div>

  {{-- Doughnut Chart: Rata - Rata Dosen Self Assesment --}}
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
      <h3 class="text-lg font-semibold text-gray-800 mb-3 text-left">Rata - Rata Dosen Self Assesment</h3>
      <div class="flex flex-col items-center">
          <div class="relative h-40 w-40 mt-4">
              <canvas id="chartPengisian"></canvas>
              <div class="absolute inset-0 flex items-center justify-center text-blue-700 font-bold text-3xl">80%</div>
          </div>
          <div class="flex justify-center gap-6 mt-4 text-sm text-gray-600">
              <div class="flex items-center"><span class="w-3 h-3 bg-blue-700 rounded-full mr-2"></span> Sudah Mengisi</div>
              <div class="flex items-center"><span class="w-3 h-3 bg-gray-300 rounded-full mr-2"></span> Belum Mengisi</div>
          </div>
      </div>
  </div>
</div>
</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Bar Chart SKS
new Chart(document.getElementById('chartSKS'), {
type: 'bar',
data: {
labels: ['Dosen A', 'Dosen B', 'Dosen C', 'Dosen D', 'Dosen E'],
datasets: [{
  label: 'Jumlah SKS',
  data: [10, 8, 14, 9, 15],
  backgroundColor: '#1E40AF', 
  borderRadius: 6,
  barThickness: 30
}]
},
options: {
responsive: true,
maintainAspectRatio: false,
plugins: { legend: { display: false } },
scales: {
  y: { beginAtZero: true, ticks: { stepSize: 3 }, grid: { color: '#F3F4F6' } },
  x: { grid: { display: false } }
}
}
});

// Doughnut Chart Pengisian
new Chart(document.getElementById('chartPengisian'), {
type: 'doughnut',
data: {
labels: ['Sudah Mengisi', 'Belum Mengisi'],
datasets: [{
  data: [80, 20],
  backgroundColor: ['#1E40AF', '#D1D5DB'], 
  borderWidth: 0
}]
},
options: {
cutout: '75%',
plugins: { legend: { display: false } },
maintainAspectRatio: false
}
});

// ========== Kode JavaScript untuk Transisi Timbul (Fade-In) ==========
document.addEventListener('DOMContentLoaded', () => {
const mainContent = document.getElementById('main-content');
if (mainContent) {
    mainContent.classList.remove('opacity-0');
    mainContent.classList.add('opacity-100');
}
});
</script>


</main>
@endsection
