@extends('layouts.app')
@section('title','Dashboard')

@section('content')
<div class="container-fluid">

  {{-- Header dengan Logo --}}
  <div class="d-flex align-items-center mb-4">
    <img src="{{ asset('images/logo.sikompu.png') }}" alt="Logo SiKomPu" style="width:60px; height:auto; margin-right:10px;">
    <div>
      <h4 class="mb-0">Sistem Penentuan Koordinator Pengampu (SiKomPu)</h4>
      <small class="text-muted">Dashboard Utama Dosen & Laboran</small>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-8">
      <div class="card p-3">
        <div class="d-flex justify-content-between">
          <h5>Dashboard Utama</h5>
          <small class="text-muted">Selamat datang, {{ auth()->user()->name ?? 'User' }}</small>
        </div>
        <hr>

        {{-- Statistik Ringkas --}}
        <div class="row g-3">
          <div class="col-md-3">
            <div class="card card-stat p-3 text-center">
              <small>Total Pengguna Aktif</small>
              <h4 class="text-primary mt-1">42</h4>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-stat p-3 text-center">
              <small>Total Mata Kuliah</small>
              <h4 class="text-success mt-1">128</h4>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-stat p-3 text-center">
              <small>Sertifikat Terdaftar</small>
              <h4 class="text-info mt-1">90</h4>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-stat p-3 text-center">
              <small>Rata-Rata Self-Assessment</small>
              <h4 class="text-warning mt-1">80%</h4>
            </div>
          </div>
        </div>

        {{-- Aktivitas Terbaru --}}
        <div class="mt-4">
          <h6>Aktivitas Terbaru</h6>
          <ul class="list-group">
            <li class="list-group-item">
              <strong>Dr. Sarah Putri</strong> telah mengupdate self-assessment 
              <small class="text-muted float-end">2 jam yang lalu</small>
            </li>
            <li class="list-group-item">
              <strong>Prof. Budi Santoso</strong> mengupload 3 sertifikat baru
              <small class="text-muted float-end">5 jam yang lalu</small>
            </li>
            <li class="list-group-item">
              <strong>Sistem</strong> berhasil menghasilkan rekomendasi koordinator pengampu
              <small class="text-muted float-end">1 hari yang lalu</small>
            </li>
          </ul>
        </div>

      </div>
    </div>

    {{-- Kolom kanan --}}
    <div class="col-md-4">
      <div class="card p-3">
        <h6>Peringatan Beban Mengajar</h6>
        <p>Terdapat <strong>7</strong> dosen dengan beban melebihi maksimal (16 SKS)</p>
        <a href="{{ route('dosen.index') }}" class="btn btn-sm btn-outline-primary">Lihat detail</a>
      </div>

      <div class="card p-3 mt-3">
        <h6>Aksi Cepat</h6>
        <a href="{{ route('generate.index') }}" class="btn btn-sm btn-primary w-100 mb-2">
          <i class="bi bi-lightning-charge-fill"></i> Generate Rekomendasi
        </a>
        <a href="{{ route('dosen.create') }}" class="btn btn-sm btn-outline-secondary w-100 mb-2">
          <i class="bi bi-person-plus"></i> Tambah Dosen
        </a>
        <a href="{{ route('matakuliah.create') }}" class="btn btn-sm btn-outline-secondary w-100">
          <i class="bi bi-journal-plus"></i> Tambah Mata Kuliah
        </a>
      </div>
    </div>
  </div>
</div>
@endsection

