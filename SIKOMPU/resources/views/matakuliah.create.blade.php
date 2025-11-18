@extends('layouts.app')
@section('title','Tambah Mata Kuliah')

@section('content')
<div class="card p-4">
  <h5 class="fw-bold mb-3">Tambah Mata Kuliah Baru</h5>
  <form method="POST" action="{{ route('matakuliah.store') }}">
    @csrf
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Kode Mata Kuliah</label>
        <input type="text" name="kode" class="form-control" placeholder="contoh: MKU123" required>
      </div>
      <div class="col-md-8">
        <label class="form-label">Nama Mata Kuliah</label>
        <input type="text" name="nama" class="form-control" placeholder="contoh: Pemrograman Web" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">SKS</label>
        <input type="number" name="sks" class="form-control" min="1" max="6" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Semester</label>
        <select name="semester" class="form-select" required>
          <option value="">-- Pilih --</option>
          @for($i=1;$i<=8;$i++)
          <option value="{{ $i }}">Semester {{ $i }}</option>
          @endfor
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Program Studi</label>
        <select name="prodi_id" class="form-select" required>
          <option value="">-- Pilih Prodi --</option>
          @foreach($prodis as $prodi)
          <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="mt-4 d-flex justify-content-end gap-2">
      <a href="{{ route('matakuliah.index') }}" class="btn btn-outline-secondary">Batal</a>
      <button class="btn btn-primary">Simpan Mata Kuliah</button>
    </div>
  </form>
</div>
@endsection
