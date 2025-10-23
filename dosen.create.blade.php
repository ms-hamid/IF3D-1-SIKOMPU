@extends('layouts.app')
@section('title','Tambah Dosen')

@section('content')
<div class="card p-3">
  <h5>Tambah Dosen Baru</h5>
  <form method="POST" action="{{ route('dosen.store') }}">
    @csrf
    <div class="mb-3"><label>Nama Lengkap</label><input name="nama" class="form-control"></div>
    <div class="mb-3"><label>NIDN / NIP</label><input name="nidn" class="form-control"></div>
    <div class="mb-3"><label>Jabatan Akademik</label><input name="jabatan" class="form-control"></div>
    <div class="mb-3"><label>Program Studi</label><select name="prodi_id" class="form-control">@foreach($prodis as $p)<option value="{{ $p->id }}">{{ $p->nama }}</option>@endforeach</select></div>
    <div class="mb-3"><label>Username (Login)</label><input name="username" class="form-control"></div>
    <div class="mb-3"><label>Password</label><input name="password" class="form-control" type="password"></div>
    <button class="btn btn-primary">Simpan Data Dosen</button>
  </form>
</div>
@endsection
