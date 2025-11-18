@extends('layouts.app')
@section('title','Manajemen Mata Kuliah')

@section('content')
<div class="card p-3">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h5 class="mb-0 fw-bold">Manajemen Mata Kuliah</h5>
      <small class="text-muted">Daftar seluruh mata kuliah aktif</small>
    </div>
    <a href="{{ route('matakuliah.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-circle"></i> Tambah Mata Kuliah
    </a>
  </div>
  <hr>

  <table class="table table-striped align-middle">
    <thead class="table-light">
      <tr>
        <th>No</th>
        <th>Kode MK</th>
        <th>Nama Mata Kuliah</th>
        <th>SKS</th>
        <th>Semester</th>
        <th>Program Studi</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($matakuliahs as $i => $mk)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $mk->kode }}</td>
        <td>{{ $mk->nama }}</td>
        <td>{{ $mk->sks }}</td>
        <td>{{ $mk->semester }}</td>
        <td>{{ $mk->prodi->nama ?? '-' }}</td>
        <td>
          <a href="{{ route('matakuliah.edit',$mk) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
          <form method="POST" action="{{ route('matakuliah.destroy',$mk) }}" style="display:inline;">
            @csrf @method('delete')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus mata kuliah ini?')">Hapus</button>
          </form>
        </td>
      </tr>
      @endforeach

      @if($matakuliahs->isEmpty())
      <tr><td colspan="7" class="text-center text-muted">Belum ada data mata kuliah.</td></tr>
      @endif
    </tbody>
  </table>
</div>
@endsection
