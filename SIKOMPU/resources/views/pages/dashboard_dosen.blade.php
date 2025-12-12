@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('page_title', 'Dashboard Dosen')

@section('content')
<main class="flex-1 p-4 sm:p-6 space-y-6" x-data="{ openModal: false }" @close-modal.window="openModal = false">

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
          Selamat Datang, {{ $user->nama_lengkap }}
        </h3>
        <p class="text-gray-500 text-sm flex items-center mt-1">
          <i class="fa-regular fa-calendar text-gray-400 mr-1"></i>
          {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
        </p>
      </div>
    </div>
  </section>

  {{-- Card Data Diri --}}
  <section class="bg-white rounded-xl shadow-sm p-6 flex flex-col lg:flex-row items-start gap-8">
    {{-- Foto Dosen --}}
    <div class="w-48 h-48 sm:w-56 sm:h-56 lg:w-64 lg:h-64 mx-auto lg:mx-0">
      @if($user->foto)
        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Dosen"
             class="rounded-xl w-full h-full object-cover">
      @else
        <div class="rounded-xl w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
          <span class="text-white text-6xl font-bold">
            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
          </span>
        </div>
      @endif
    </div>

    {{-- Data Diri --}}
    <div class="flex-1">
      <h2 class="text-lg font-semibold text-gray-700 uppercase tracking-wide mb-2">Data Diri Dosen</h2>
      <hr class="border-gray-300 mb-3">

      <table class="text-sm text-gray-700 w-full max-w-lg">
        <tr>
          <td class="pr-3 py-1 font-medium">Nama</td>
          <td class="pr-2">:</td>
          <td>{{ $user->nama_lengkap }}</td>
        </tr>
        <tr>
          <td class="pr-3 py-1 font-medium">NIDN</td>
          <td class="pr-2">:</td>
          <td>{{ $user->nidn }}</td>
        </tr>
        <tr>
          <td class="pr-3 py-1 font-medium">Program Studi</td>
          <td class="pr-2">:</td>
          <td>{{ $user->prodi ?? 'Belum diatur' }}</td>
        </tr>
        <tr>
          <td class="pr-3 py-1 font-medium">Jabatan</td>
          <td class="pr-2">:</td>
          <td>{{ $user->jabatan }}</td>
        </tr>
        <tr>
          <td class="pr-3 py-1 font-medium">Status</td>
          <td class="pr-2">:</td>
          <td>
            @if($persentaseSelfAssessment >= 80)
              <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">
                Sudah Isi Self-Assessment
              </span>
            @elseif($persentaseSelfAssessment > 0)
              <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-medium">
                Belum Lengkap ({{ $persentaseSelfAssessment }}%)
              </span>
            @else
              <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">
                Belum Isi Self-Assessment
              </span>
            @endif
          </td>
        </tr>
      </table>
    </div>
  </section>

  {{-- Aktivitas Terbaru --}}
  <section class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm flex flex-col lg:flex-row gap-6">

    {{-- Diagram Self-Assessment --}}
    <div class="flex flex-col items-center justify-center flex-1 
                border-b lg:border-b-0 lg:border-r border-gray-200 
                pb-6 lg:pb-0 lg:pr-6 text-center">

      <h4 class="font-semibold text-gray-700 mb-4 text-base sm:text-lg">
        Progress Self-Assessment
      </h4>

      {{-- SVG Diagram yang responsif --}}
      <div class="relative w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40">
        <svg viewBox="0 0 160 160" class="w-full h-full">
          <!-- Latar belakang lingkaran -->
          <circle cx="80" cy="80" r="70" stroke="#e5e7eb" stroke-width="10" fill="none" />
          <!-- Progress lingkaran (dinamis berdasarkan persentase) -->
          <circle 
            cx="80" cy="80" r="70" 
            stroke="#2563eb" stroke-width="10" fill="none"
            stroke-dasharray="440" 
            stroke-dashoffset="{{ 440 - (440 * $persentaseSelfAssessment / 100) }}"
            stroke-linecap="round" 
            transform="rotate(-90 80 80)" />
        </svg>

        <div class="absolute inset-0 flex items-center justify-center">
          <span class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800">
            {{ $persentaseSelfAssessment }}%
          </span>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row justify-center items-center mt-3 
                  text-xs sm:text-sm text-gray-500 gap-1 sm:gap-4">
        <span class="flex items-center">
          <span class="w-3 h-3 bg-blue-600 rounded-full mr-1"></span> 
          Sudah Mengisi ({{ $sudahIsi }})
        </span>
        <span class="flex items-center">
          <span class="w-3 h-3 bg-gray-300 rounded-full mr-1"></span> 
          Belum Mengisi ({{ $belumIsi }})
        </span>
      </div>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="flex-1">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 text-center sm:text-left">
        <h4 class="text-base sm:text-lg font-semibold text-gray-800">Aktivitas Terbaru Anda</h4>
        <a href="#" class="text-blue-600 text-sm hover:underline mt-1 sm:mt-0">Lihat semua</a>
      </div>

      @if($aktivitasTerbaru->count() > 0)
        <ul class="space-y-4">
          @foreach($aktivitasTerbaru as $aktivitas)
            <li class="relative border-l-4 border-{{ $aktivitas['border_color'] }} bg-{{ $aktivitas['bg_color'] }} rounded-xl p-4 hover:shadow-md transition-all duration-200 ease-in-out">
              <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
                <h5 class="font-semibold text-gray-800">{{ $aktivitas['title'] }}</h5>
                <span class="bg-{{ $aktivitas['status_color'] }}-100 text-{{ $aktivitas['status_color'] }}-700 text-xs px-2 py-1 rounded-md font-medium mt-1 sm:mt-0">
                  {{ $aktivitas['status'] }}
                </span>
              </div>
              <p class="text-sm text-gray-600 mt-1">{{ $aktivitas['description'] }}</p>
              <p class="text-xs text-gray-400 mt-2 text-right">
                {{ $aktivitas['created_at']->diffForHumans() }}
              </p>
            </li>
          @endforeach
        </ul>
      @else
        <div class="text-center py-8 text-gray-400">
          <i class="fa-solid fa-inbox text-4xl mb-2"></i>
          <p class="text-sm">Belum ada aktivitas</p>
        </div>
      @endif
    </div>
  </section>
</main>

{{-- Modal Pemberitahuan Profil --}}
<x-notification-modal :show="$showModal" />
@endsection