@extends('layouts.admin')

@section('title', $category->exists ? 'Ubah bidang' : 'Tambah bidang')

@section('content')
<form class="card" method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
  @csrf
  @if($category->exists) @method('PUT') @endif
  <div class="form-grid">
    <label class="f full">Nama bidang
      <input name="name" value="{{ old('name', $category->name) }}" required>
      @error('name')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label class="f">Warna penanda
      <input type="color" name="color" value="{{ old('color', $category->color) }}">
    </label>
    <label class="f">Urutan tampil <small>Angka kecil tampil lebih dulu</small>
      <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
    </label>
  </div>
  <div class="form-foot">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn light" href="{{ route('admin.categories.index') }}">Batal</a>
  </div>
</form>
@endsection
