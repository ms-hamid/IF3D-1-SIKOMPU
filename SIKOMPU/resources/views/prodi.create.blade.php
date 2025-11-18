@extends('layouts.app')
@section('title','Tambah Program Studi')

@section('content')
<div class="card p-4">
  <h5 class="fw-bold mb-3">Tambah Program Studi Baru</h5>
  <form method="POST" action="{{ route('prodi.store') }}">
    @csrf
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Kode Prodi</label>
        <input type="text" name="kode" class="form-control" placeholder="contoh: TI01" required>
      </div>
      <div class="col-md-8">
        <label class="form-label">Nama Program Studi</label>
        <input type="text" name="nama" class="form-control" placeholder="contoh: Teknik Informatika" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Jenjang</label>
        <select name="jenjang" class="form-select" required>
          <option value="">-- Pilih Jenjang --</option>
          <option>D3</option>
          <option>D4</option>
          <option>S1</option>
          <option>S2</option>
        </select>
      </div>
    </div>
    <div class="mt-4 d-flex justify-content-end gap-2">
      <a href="{{ route('prodi.index') }}" class="btn btn-outline-secondary">Batal</a>
      <button class="btn btn-primary">Simpan Prodi</button>
    </div>
  </form>
</div>
@endsection
