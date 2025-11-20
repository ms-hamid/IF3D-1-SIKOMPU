@extends('layouts.app')

@section('title', 'Manajemen Program Studi')

@section('content')

<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false, openModalProdi: false, selectedAction: '' }" @close-modal.window="openModal = false">

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-graduation-cap text-blue-600 mr-2"></i> Manajemen Program Studi
        </h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola data program studi dan jenjang yang tersedia di sistem.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        {{-- Tombol Tambah --}}
        <button @click="openModalProdi = true" 
           class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-sm font-medium shadow-md transition duration-150 flex items-center">
            <i class="fas fa-plus mr-1.5"></i> Tambah Data Baru
        </button>
        {{-- Tombol Import --}}
        <button 
           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium shadow-md transition duration-150 flex items-center">
            <i class="fas fa-file-import mr-1.5"></i> Import Data
        </button>

        {{-- Dropdown Ekspor Template --}}
        <div x-data="{ open: false }" @click.away="open = false" class="relative">
            <button @click="open = !open" 
              class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium flex items-center gap-1 border border-gray-300 transition duration-150">
                Ekspor Template
                <i class="fas fa-chevron-down w-3 h-3 ml-1 transition-transform" :class="{'rotate-180': open}"></i>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-10">
                <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-file-excel mr-2"></i> Excel (.xlsx)</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-file-csv mr-2"></i> CSV (.csv)</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col sm:flex-row gap-3 mb-5">
  <input type="text" placeholder="Cari Program Studi..."
         class="w-full sm:w-1/2 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-150 shadow-sm"
         style="max-width: 350px;">
  <select class="w-full sm:w-1/4 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-150 shadow-sm"
          style="max-width: 250px;">
          <option>Semua Prodi</option>
          <option>Teknik Informatika</option>
          <option>Teknik Geomatika</option>
          <option>Teknologi Rekayasa Multimedia</option>
          <option>Animasi</option>
          <option>Rekayasa Keamanan Siber</option>
          <option>Teknik Rekayasa Perangkat Lunak</option>
          <option>Teknologi Permainan</option>
          <option>S2 Magister Terapan Teknik Komputer</option>
  </select>
</div>

<div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-200">

  <div class="overflow-x-auto">
      {{-- Kunci ada di 'table-fixed' untuk memastikan kolom 100% lebar --}}
      <table class="w-full table-fixed text-sm text-left text-gray-700">

          <thead class="bg-blue-600 text-white border-b border-blue-700">
              <tr>
                  <th scope="col" class="w-[5%] px-6 py-3 font-bold text-center uppercase tracking-wider">No</th>
                  <th scope="col" class="w-[35%] px-6 py-3 font-bold text-left uppercase tracking-wider">Nama Program Studi</th>
                  <th scope="col" class="w-[10%] px-6 py-3 font-bold text-center uppercase tracking-wider">Kode Prodi</th>
                  <th scope="col" class="w-[10%] px-6 py-3 font-bold text-center uppercase tracking-wider">Jenjang</th>
                  <th scope="col" class="w-[30%] px-6 py-3 font-bold text-left uppercase tracking-wider">Jurusan</th>
                  <th scope="col" class="w-[10%] px-6 py-3 font-bold text-center uppercase tracking-wider">Aksi</th>
              </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">

              {{-- PHP: Data Dummy Program Studi (diasumsikan ini berasal dari controller) --}}
              @php
                  $prodis = [
                      ['no' => 1, 'nama' => 'Teknik Informatika', 'kode' => 'TI', 'jenjang' => 'D3', 'jurusan' => 'Teknologi Informasi'],
                      ['no' => 2, 'nama' => 'Teknik Multimedia & Jaringan', 'kode' => 'TMJ', 'jenjang' => 'D4', 'jurusan' => 'Teknologi Informasi'],
                      ['no' => 3, 'nama' => 'Akuntansi Manajerial', 'kode' => 'AMJ', 'jenjang' => 'D4', 'jurusan' => 'Bisnis dan Manajemen'],
                      ['no' => 4, 'nama' => 'Manajemen Bisnis Internasional', 'kode' => 'MBI', 'jenjang' => 'D4', 'jurusan' => 'Bisnis dan Manajemen'],
                      ['no' => 5, 'nama' => 'Teknik Mesin', 'kode' => 'TM', 'jenjang' => 'D3', 'jurusan' => 'Teknik Mesin'],
                      ['no' => 6, 'nama' => 'Sistem Informasi', 'kode' => 'SI', 'jenjang' => 'D4', 'jurusan' => 'Teknologi Informasi'],
                      ['no' => 7, 'nama' => 'Teknik Elektronika', 'kode' => 'TEK', 'jenjang' => 'D3', 'jurusan' => 'Teknik Elektro'],
                      ['no' => 8, 'nama' => 'Teknik Instrumentasi', 'kode' => 'TIK', 'jenjang' => 'D4', 'jurusan' => 'Teknik Elektro'],
                  ];
              @endphp

              @foreach ($prodis as $prodi)
                  <tr class="hover:bg-blue-50 transition duration-150">
                      {{-- NO (RATA TENGAH) --}}
                      <td class="px-6 py-3 text-center text-gray-500">{{ $prodi['no'] }}</td>

                      {{-- Nama Program Studi (RATA KIRI) --}}
                      <td class="px-6 py-3 font-semibold text-gray-800 text-left">{{ $prodi['nama'] }}</td>

                      {{-- Kode Prodi (RATA TENGAH) --}}
                      <td class="px-6 py-3 text-center">
                          <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full border border-gray-300">
                              {{ $prodi['kode'] }}
                          </span>
                      </td>

                      {{-- Jenjang (RATA TENGAH) --}}
                      <td class="px-6 py-3 text-center">
                          <span class="text-xs font-bold px-3 py-1 rounded-full shadow-sm
                              {{ $prodi['jenjang'] == 'D4' ? 'bg-indigo-100 text-indigo-800' : 'bg-orange-100 text-orange-800' }}">
                              {{ $prodi['jenjang'] }}
                          </span>
                      </td>

                      {{-- Jurusan (RATA KIRI) --}}
                      <td class="px-6 py-3 text-gray-600 text-left">{{ $prodi['jurusan'] }}</td>

                      {{-- Aksi (RATA TENGAH) - Menggunakan 'justify-center' pada flex --}}
                      <td class="px-6 py-3 flex items-center justify-center gap-3">
                          <a href="#" title="Edit" class="p-1.5 text-blue-600 hover:text-blue-800 bg-blue-50 rounded-full transition duration-150 hover:bg-blue-100">
                              <i class="fas fa-edit text-sm"></i>
                          </a>
                          <button title="Hapus" @click="openModal = true; selectedAction = 'delete-{{ $prodi['no'] }}'"
                                  class="p-1.5 text-red-600 hover:text-red-800 bg-red-50 rounded-full transition duration-150 hover:bg-red-100">
                              <i class="fas fa-trash-alt text-sm"></i>
                          </button>
                      </td>
                  </tr>
              @endforeach

              <tr>
                  <td colspan="6" class="px-6 py-3 text-xs text-gray-600 bg-gray-100 border-t border-gray-200 font-medium">
                      Menampilkan 1 sampai 8 dari total 8 data Program Studi.
                  </td>
              </tr>
          </tbody>
      </table>
  </div>
</div>

<div x-cloak x-show="openModal"
   class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-filter backdrop-blur-sm">
  <div @click.away="openModal = false"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
       x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
       x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
       class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 space-y-5">

      <div class="flex items-start space-x-4">
          <div class="flex-shrink-0 p-3 bg-red-100 rounded-full">
              <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
          </div>
          <div>
              <h3 class="text-xl font-bold text-gray-900 mb-1">Konfirmasi Hapus Data</h3>
              <p class="text-sm text-gray-600">
                  Anda yakin ingin menghapus data Program Studi ini? Tindakan ini **tidak dapat dibatalkan**.
              </p>
          </div>
      </div>

      <div class="flex justify-end space-x-3 pt-2">
          <button @click="openModal = false"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition shadow-sm">
              Batal
          </button>
          <button @click="console.log('Menghapus data ID:', selectedAction.split('-')[1]); openModal = false;"
                  class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition shadow-md">
              Ya, Hapus
          </button>
      </div>
  </div>
</div>

<div x-cloak x-show="openModalProdi"
     class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-filter backdrop-blur-sm">

    <div @click.away="openModalProdi = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="bg-white rounded-xl shadow-2xl max-w-lg w-full p-6 space-y-4">

        <h2 class="text-xl font-bold text-gray-900 border-b pb-3 mb-3">
            <i class="fas fa-plus-circle text-blue-600 mr-2"></i> Tambah Program Studi Baru
        </h2>

        <form action="{{ route('prodi.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="nama_prodi" class="block text-sm font-medium text-gray-700 mb-1">Nama Program Studi</label>
                <input type="text" id="nama_prodi" name="nama_prodi" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <div>
                <label for="kode_prodi" class="block text-sm font-medium text-gray-700 mb-1">Kode Program Studi</label>
                <input type="text" id="kode_prodi" name="kode_prodi" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <div>
                <label for="jenjang" class="block text-sm font-medium text-gray-700 mb-1">Jenjang</label>
                <select id="jenjang" name="jenjang" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" @click="openModalProdi = false"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Batal
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition font-medium shadow-md">
                    <i class="fas fa-save mr-1"></i> Simpan Data
                </button>
            </div>
        </form>

    </div>
</div>


</main>
@endsection