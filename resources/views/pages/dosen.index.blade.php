@extends('layouts.app')
@section('title','Manajemen Dosen')

@section('content')
<div class="card p-3">
  <div class="d-flex justify-content-between align-items-center">
    <h5>Daftar Dosen/Laboran</h5>
    <a href="{{ route('dosen.create') }}" class="btn btn-primary btn-sm">Tambah Dosen Baru</a>
  </div>
  <hr>
  <table class="table">
    <thead><tr><th>No</th><th>Nama</th><th>NIDN/NIP</th><th>Prodi</th><th>Beban</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      @foreach($dosens as $i => $d)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $d->nama }}</td>
        <td>{{ $d->nidn }}</td>
        <td>{{ $d->prodi->nama ?? '-' }}</td>
        <td>{{ $d->beban }} / {{ $d->maks_sks }} SKS</td>
        <td>{{ $d->status }}</td>
        <td>
          <a href="{{ route('dosen.edit',$d) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
          <form method="POST" action="{{ route('dosen.destroy',$d) }}" style="display:inline">@csrf @method('delete')<button class="btn btn-sm btn-danger">Hapus</button></form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
