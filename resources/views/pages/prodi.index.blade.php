@extends('layouts.app')
@section('title','Manajemen Program Studi')

@section('content')
<div class="card p-3">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h5 class="mb-0 fw-bold">Manajemen Program Studi</h5>
      <small class="text-muted">Daftar seluruh program studi aktif</small>
    </div>
    <a href="{{ route('prodi.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-circle"></i> Tambah Prodi
    </a>
  </div>
  <hr>

  <table class="table table-striped align-middle">
    <thead class="table-light">
      <tr>
        <th>No</th>
        <th>Kode Prodi</th>
        <th>Nama Prodi</th>
        <th>Jenjang</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($prodis as $i => $p)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $p->kode }}</td>
        <td>{{ $p->nama }}</td>
        <td>{{ $p->jenjang }}</td>
        <td>
          <a href="{{ route('prodi.edit',$p) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
          <form method="POST" action="{{ route('prodi.destroy',$p) }}" style="display:inline;">
            @csrf @method('delete')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus prodi ini?')">Hapus</button>
          </form>
        </td>
      </tr>
      @endforeach

      @if($prodis->isEmpty())
      <tr><td colspan="5" class="text-center text-muted">Belum ada data program studi.</td></tr>
      @endif
    </tbody>
  </table>
</div>
@endsection
