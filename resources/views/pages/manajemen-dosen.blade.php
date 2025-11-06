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
          <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-md transition">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Dosen Baru
          </button>
        </div>
    
        <div class="p-4">
          {{-- Search + Filter --}}
          <div class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <input type="text" placeholder="Cari dosen..." 
                   class="w-full sm:w-1/3 border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
            <select class="w-full sm:w-1/4 border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
              <option>Semua Prodi</option>
              <option>Teknik Informatika</option>
              <option>Teknik Geomatika</option>
              <option>Teknik Rekayasa Multimedia</option>
              <option>Animasi</option>
              <option>Rekayasa Keamanan Siber</option>
              <option>Teknik Rekayasa Perangkat Lunak</option>
              <option>Teknologi Permainan</option>
              <option>S2 Magister Terapan Teknik Komputer</option>

            </select>
          </div>
    
          {{-- Table --}}
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border-collapse">
              <thead class="bg-gray-50 text-gray-700">
                <tr>
                  <th class="px-4 py-2 font-medium">No</th>
                  <th class="px-4 py-2 font-medium">Nama Dosen</th>
                  <th class="px-4 py-2 font-medium">NIDN/NIP</th>
                  <th class="px-4 py-2 font-medium">Prodi</th>
                  <th class="px-4 py-2 font-medium">Beban Mengajar</th>
                  <th class="px-4 py-2 font-medium">Status</th>
                  <th class="px-4 py-2 font-medium text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="text-gray-700">
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
                        <div class="h-full bg-green-500 w-5/6"></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-2"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Aktif</span></td>
                  <td class="px-4 py-2 text-center space-x-2">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                      </a>
                      <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                      </button>
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
                        <div class="h-full bg-yellow-400 w-2/3"></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-2"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Aktif</span></td>
                  <td class="px-4 py-2 text-center space-x-2">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                      </a>
                      <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                      </button>
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
                        <div class="h-full bg-blue-500 w-3/4"></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-2"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Aktif</span></td>
                  <td class="px-4 py-2 text-center space-x-2">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-pen"></i>
                      </a>
                      <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                      </button>
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
    
    </main>
    @endsection
       
        
    
        

