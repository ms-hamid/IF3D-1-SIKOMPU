@extends('layouts.app')
@section('title','Laporan')

@section('content')
<div class="card p-3">
  <h5>Pusat Laporan</h5>
  <form method="POST" action="{{ route('laporan.generate') }}">
    @csrf
    <div class="row g-3">
      <div class="col-md-4"><label>Tahun Ajaran</label><select name="tahun" class="form-control"><option>2024/2025</option></select></div>
      <div class="col-md-4"><label>Semester</label><select name="semester" class="form-control"><option>Ganjil</option></select></div>
      <div class="col-md-4"><label>Program Studi</label><select name="prodi_id" class="form-control"><option value="">Semua Program Studi</option>@foreach($prodis as $p)<option value="{{ $p->id }}">{{ $p->nama }}</option>@endforeach</select></div>
    </div>
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary">Generate & Download PDF</button>
      <button name="format" value="excel" class="btn btn-outline-secondary">Generate & Download Excel</button>
    </div>
  </form>

  <hr>
  <h6>Laporan Terbaru</h6>
  <table class="table">
    <thead><tr><th>Nama Laporan</th><th>Periode</th><th>Dibuat</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      <!-- contoh -->
      <tr><td>Rekap Final Pengampu</td><td>Ganjil 2024/2025</td><td>15 Des 2024</td><td>Selesai</td><td><a class="btn btn-sm btn-outline-primary">Unduh</a></td></tr>
    </tbody>
  </table>
</div>
@endsection
