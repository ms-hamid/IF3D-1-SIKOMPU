@extends('layouts.app')

@section('title', 'Manajemen Dosen')
@section('page_title', 'Manajemen Dosen')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }" @close-modal.window="openModal = false">

  {{-- Card: Daftar Dosen --}}
  <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
      <div>
        <h2 class="text-lg font-semibold text-gray-800">Daftar Dosen/Laboran</h2>
        <p class="text-sm text-gray-500">Kelola data dosen dan laboran dalam sistem</p>
      </div>
      <button @click="openModal = true"
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-md transition">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Data Dosen Baru
      </button>
      <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openModal = false"></div>
      
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      
          <div x-transition:enter="ease-out duration-300"
               x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
               x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
               x-transition:leave="ease-in duration-200"
               x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
               x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
               class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full"> <div class="bg-white px-6 py-4 border-b border-gray-200">
              <h3 class="text-xl font-bold text-gray-800" id="modal-title">
                Tambah Data Dosen Baru
              </h3>
            </div>
      
            <div class="bg-white px-6 pt-6 pb-4 sm:p-6">
              <form action="#" method="POST" id="add-dosen-form">
                @csrf
                <div class="space-y-6">
                  
                  {{-- Nama Lengkap --}}
                  <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 sm:col-span-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" placeholder="Masukkan Nama Lengkap"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm sm:col-span-2">
                  </div>
      
                  {{-- NIDN / NIP --}}
                  <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                    <label for="nidn_nip" class="block text-sm font-medium text-gray-700 sm:col-span-1">NIDN/ NIP</label>
                    <input type="text" name="nidn_nip" id="nidn_nip" placeholder="NIDN / NIP"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm sm:col-span-2">
                  </div>
      
                  {{-- Program Studi --}}
                  <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                    <label for="prodi" class="block text-sm font-medium text-gray-700 sm:col-span-1">Program Studi</label>
                    <select name="prodi" id="prodi"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm sm:col-span-2">
                        <option>Semua Prodi</option>
          <option>Teknik Informatika</option>
          <option>Teknik Geomatika</option>
          <option>Teknologi Rekayasa Multimedia</option>
          <option>Animasi</option>
          <option>Rekayasa Keamanan Siber</option>
          <option>Teknik Rekayasa Perangkat Lunak</option>
          <option>Teknologi Permainan</option>
          <option>S2 Magister Terapan Teknik Komputer</option>
                        {{-- Tambahkan Opsi Prodi Lainnya --}}
                    </select>
                  </div>
      
                  {{-- Jabatan Akademik (Radio Button) --}}
                  <div class="sm:grid sm:grid-cols-3 sm:gap-1 sm:items-center">
                    <label class="block text-sm font-medium text-gray-700 sm:col-span-1">Jabatan Akademik</label>
                    <div class="mt-1 space-x-2 sm:col-span-3">
                      <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-blue-600" name="jabatan_akademik" value="struktural" checked>
                        <span class="ml-2 text-sm text-gray-700">Dosen Struktural</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-blue-600" name="jabatan_akademik" value="biasa">
                        <span class="ml-2 text-sm text-gray-700">Dosen Biasa</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-blue-600" name="jabatan_akademik" value="laboran">
                        <span class="ml-2 text-sm text-gray-700">Dosen Laboran</span>
                      </label>
                    </div>
                  </div>
      
                  {{-- Username (Login) --}}
                  <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                    <label for="username" class="block text-sm font-medium text-gray-700 sm:col-span-1">Username (Login)</label>
                    <input type="text" name="username" id="username"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm sm:col-span-2">
                  </div>
      
                  {{-- Password --}}
                  <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                    <label for="password" class="block text-sm font-medium text-gray-700 sm:col-span-1">Password</label>
                    <div class="mt-1 sm:col-span-2 flex flex-col space-y-2">
                      <input type="password" name="password" id="password"
                             class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                      <div class="w-full flex justify-end">
                          <button type="button" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Reset Password</button>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </form>
            </div>
      
            <div class="px-6 py-4 flex justify-between">
              <button type="submit" form="add-dosen-form"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fa-solid fa-user-plus mr-2"></i> Simpan Data Dosen
              </button>
              <button type="button" @click="openModal = false"
                class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900" style="border: none; background: none;">
                Batalkan
              </button>
            </div>
      
          </div>
        </div>
      </div>
    </div>
    <div class="p-4">
        {{-- Search + Filter + Total Dosen --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4">
          <div class="flex w-full sm:w-2/3 space-x-3">
            
            {{-- Input Pencarian dengan Icon --}}
            <div class="relative w-full sm:w-2/3">
              <input type="text" name="cari" placeholder="Cari dosen..." autofocus
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none"
              >
              {{-- Ikon Search --}}
              <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
            </div>
            
            {{-- Filter Prodi (Select) --}}
            <select class="w-full sm:w-1/3 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
              <option>Semua Prodi</option>
          <option>Teknik Informatika</option>
          <option>Teknik Geomatika</option>
          <option>Teknologi Rekayasa Multimedia</option>
          <option>Animasi</option>
          <option>Rekayasa Keamanan Siber</option>
          <option>Teknik Rekayasa Perangkat Lunak</option>
          <option>Teknologi Permainan</option>
          <option>S2 Magister Terapan Teknik Komputer</option>
              {{-- ... Opsi Prodi lainnya ... --}}
            </select>
            
          </div>
          
          {{-- Total Dosen --}}
          <p class="text-sm text-gray-500 mt-2 sm:mt-0">
            Total: 24 dosen
          </p>
        </div>

      {{-- Table --}}
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse table-fixed">
          <thead class="bg-gray-50 text-gray-700">
            <tr>
              <th class="w-1/12 px-4 py-2 font-medium">No</th>
              <th class="w-3/12 px-4 py-2 font-medium">Nama Dosen</th>
              <th class="w-2/12 px-4 py-2 font-medium">NIDN/NIP</th>
              <th class="w-2/12 px-4 py-2 font-medium">Prodi</th>
              <th class="w-2/12 px-4 py-2 font-medium">Beban Mengajar</th>
              <th class="w-1/12 px-4 py-2 font-medium text-center">Status</th>
              <th class="w-1/12 px-4 py-2 font-medium text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            {{-- Contoh Data Dummy --}}
        <tbody>
          <tr class="border-t">
            <td class="px-4 py-2">1</td>
            <td class="px-4 py-2">
              <div class="flex items-center space-x-3">
                <img src="https://randomuser.me/api/portraits/men/1.jpg" class="w-8 h-8 rounded-full">
                <div>
                  <p class="font-medium">Dr. Ahmad Fauzi, M.T.</p>
                  <p class="text-xs text-gray-500">Dosen Tetap</p>
                </div>
              </div>
            </td>
            <td class="px-4 py-2">0123456789</td>
            <td class="px-4 py-2">Teknik Informatika</td>
            <td class="px-4 py-2">
              <div class="flex items-center space-x-2">
                <span>14/16 SKS</span>
                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-green-500 w-[87.5%]"></div>
                </div>
              </div>
            </td>
            <td class="px-4 py-2 text-center">
              <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Aktif</span>
            </td>
            <td class="px-4 py-2 text-center space-x-2">
              <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fas fa-pen"></i></a>
              <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
            </td>
          </tr>

          <tr class="border-t">
            <td class="px-4 py-2">2</td>
            <td class="px-4 py-2">
              <div class="flex items-center space-x-3">
                <img src="https://randomuser.me/api/portraits/women/2.jpg" class="w-8 h-8 rounded-full">
                <div>
                  <p class="font-medium">Sari Indah, M.Kom.</p>
                  <p class="text-xs text-gray-500">Dosen Struktural</p>
                </div>
              </div>
            </td>
            <td class="px-4 py-2">0123456790</td>
            <td class="px-4 py-2">Sistem Informasi</td>
            <td class="px-4 py-2">
              <div class="flex items-center space-x-2">
                <span>8/12 SKS</span>
                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-green-500 w-2/3"></div>
                </div>
              </div>
            </td>
            <td class="px-4 py-2 text-center">
              <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Aktif</span>
            </td>
            <td class="px-4 py-2 text-center space-x-2">
              <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fas fa-pen"></i></a>
              <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
            </td>
          </tr>

          <tr class="border-t">
            <td class="px-4 py-2">3</td>
            <td class="px-4 py-2">
              <div class="flex items-center space-x-3">
                <img src="https://randomuser.me/api/portraits/men/3.jpg" class="w-8 h-8 rounded-full">
                <div>
                  <p class="font-medium">Budi Santoso, M.T.</p>
                  <p class="text-xs text-gray-500">Laboran</p>
                </div>
              </div>
            </td>
            <td class="px-4 py-2">0123456791</td>
            <td class="px-4 py-2">Teknik Elektro</td>
            <td class="px-4 py-2">
              <div class="flex items-center space-x-2">
                <span>12/16 SKS</span>
                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-green-500 w-3/4"></div>
                </div>
              </div>
            </td>
            <td class="px-4 py-2 text-center">
              <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Aktif</span>
            </td>
            <td class="px-4 py-2 text-center space-x-2">
              <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fas fa-pen"></i></a>
              <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>


      {{-- Pagination --}}
      <div class="flex justify-between items-center mt-4 text-sm text-gray-500">
        <p>Menampilkan 1–3 dari 24 data</p>
        <div class="flex items-center space-x-1">
          <button class="px-2 py-1 border border-gray-300 rounded-md">&lt;</button>
          <button class="px-3 py-1 bg-blue-600 text-white rounded-md">1</button>
          <button class="px-3 py-1 border border-gray-300 rounded-md">2</button>
          <button class="px-3 py-1 border border-gray-300 rounded-md">3</button>
          <button class="px-2 py-1 border border-gray-300 rounded-md">&gt;</button>
        </div>
      </div>
    </div>
  </div>

      {{-- Include Form Tambah Dosen --}}
      @include('components.dosen')
</main>
@endsection
