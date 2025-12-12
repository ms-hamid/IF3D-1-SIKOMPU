@extends('layouts.app')

@section('title', 'Dashboard Struktural')
@section('page_title', 'Dashboard Struktural')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }" @close-modal.window="openModal = false">

  {{-- Banner (Anggap ini X-Component) --}}
  <x-dashboard.banner />

  {{-- Greeting Section --}}
  <section class="border-b border-gray-300 pb-3">
    <div class="flex items-center space-x-3">
      <div class="bg-green-100 p-2 rounded-lg">
        <i class="fa-solid fa-chart-line text-green-600"></i>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-gray-800">
          Selamat Datang, {{ $user->nama_lengkap }}
        </h3>
        <p class="text-gray-500 text-sm flex items-center mt-1">
          <i class="fa-regular fa-calendar text-gray-400 mr-1"></i>
          {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
        </p>
      </div>
    </div>
  </section>
  
  {{-- Statistik ringkas (3 Kolom) --}}
  <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-6 mb-4">
    <div class="items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 text-left hover:bg-gray-100 transition">
      <p class="text-gray-500 text-sm font-medium">Total Pengguna Aktif</p>
      <h3 class="text-3xl font-bold text-gray-800">{{ $totalPengguna }}</h3> 
      <p class="text-xs text-green-600">+2 dari semester lalu</p>
    </div>
    <div class="items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 text-left hover:bg-gray-100 transition">
      <p class="text-gray-500 text-sm font-medium">Total Dosen</p>
      <h3 class="text-3xl font-bold text-gray-800">{{ $totalDosen }}</h3> 
      <p class="text-xs text-gray-600">Dosen Terdaftar</p>
    </div>
    <div class="items-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 text-left hover:bg-gray-100 transition">
      <p class="text-gray-500 text-sm font-medium">Total Mata Kuliah</p>
      <h3 class="text-3xl font-bold text-gray-800">{{ $totalMataKuliah }}</h3> 
      <p class="text-xs text-gray-600">Mata Kuliah Terdaftar</p>
    </div>
  </div>


  {{-- Alert Peringatan --}}
  <div class="flex items-center p-4 text-white bg-red-500 border border-red-600 rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
    <i class="fa-solid fa-triangle-exclamation text-xl mr-3"></i>
    <p class="text-sm">
      <strong>Peringatan Beban Mengajar</strong> — Terdapat <b>7 dosen</b> dengan beban mengajar melebihi batas maksimal (16 SKS).
      <a href="#" class="text-white underline hover:text-red-100 ml-1 rounded-lg transition">Lihat detail →</a>
    </p>
  </div>

    {{-- KOLOM KIRI BESAR (Aktivitas & Aksi Cepat - Mengambil 2 dari 3 Kolom) --}}
    <div class="lg:col-span-2 space-y-6"> 
        
        {{-- BARIS AKTIVITAS & AKSI CEPAT (Sejajar) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> 

            {{-- Aktivitas Terbaru --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:bg-gray-50 transition">
                <div class="flex justify-between items-center mb-1">
                    <h2 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h2>
                    <a href="#" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
                </div>
                
                {{-- Kontrol Tinggi Daftar Aktivitas --}}
                <div class="max-h-80 overflow-y-auto pr-2"> <ul class="space-y-4">
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
                        <li class="flex items-start space-x-3">
                            <i class="fa-solid fa-circle-check text-green-500 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-700">Pembagian tugas koordinator disetujui oleh Kepala Jurusan</p>
                                <span class="text-xs text-gray-400">3 hari yang lalu</span>
                            </div>
                        </li>
                        {{-- Tambahkan item dummy lagi agar tingginya seimbang --}}
                        <li class="flex items-start space-x-3">
                            <i class="fa-solid fa-user-check text-green-500 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-700">Dr. Ahmad Wijaya berhasil login ke sistem SIKOMPU</p>
                                <span class="text-xs text-gray-400">3 hari yang lalu</span>
                            </div>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fa-solid fa-bell text-gray-500 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-700">Peringatan pengisian self-assessment dikirim ke 50 dosen</p>
                                <span class="text-xs text-gray-400">3 hari yang lalu</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Aksi Cepat --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:bg-blue-500 transition">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
                <div class="space-y-4">
                    <button class="w-full flex items-center justify-between bg-gray-100 text-gray-800 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                        <span><i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Generate Rekomendasi</span>
                        <i class="fa-solid fa-arrow-right text-gray-500"></i>
                    </button>

                    <button class="w-full flex items-center justify-between bg-gray-100 text-gray-800 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                        <span><i class="fa-solid fa-user-plus mr-2"></i> Tambah Dosen</span>
                        <i class="fa-solid fa-arrow-right text-gray-500"></i>
                    </button>

                    <button class="w-full flex items-center justify-between bg-gray-100 text-gray-800 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                        <span><i class="fa-solid fa-book-medical mr-2"></i> Tambah Mata Kuliah</span>
                        <i class="fa-solid fa-arrow-right text-gray-500"></i>
                    </button>

                    <button class="w-full flex items-center justify-between bg-gray-100 text-gray-800 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                        <span><i class="fa-solid fa-file-export mr-2"></i> Ekspor Laporan</span>
                        <i class="fa-solid fa-arrow-right text-gray-500"></i>
                    </button>
                </div>
            </div>

        </div>
        
  {{-- AKHIR BARIS UTAMA --}}
  
      {{-- Bar Chart: Distribusi SKS per Dosen --}}
      <div class="bg-white p-5 shadow-sm rounded-xl border border-gray-100">
          <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-semibold text-gray-800">Distribusi SKS per Dosen</h3>
              <select class="border border-gray-300 text-sm rounded-lg px-3 py-1 focus:ring-blue-500 focus:border-blue-500">
                  <option>Semester Ganjil 2024/2025</option>
                  <option selected>Semester Genap 2024/2025</option>
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
    {{-- AKHIR KOLOM KANAN --}}
  </div>
  {{-- AKHIR BARIS UTAMA --}}
  
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Bar Chart SKS
new Chart(document.getElementById('chartSKS'), {
type: 'bar',
data: {
labels: ['Dosen B', 'Dosen C', 'Dosen D', 'Dosen E'], 
datasets: [{
  label: 'Jumlah SKS',
  data: [8, 14, 9, 15], 
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
</script>
</main>

{{-- Modal Pemberitahuan Profil --}}
<x-notification-modal :show="$showModal" />
@endsection
