@extends('layouts.admin')

@section('title', 'Fasilitas')

@section('content')
<form class="card" method="POST" action="{{ route('admin.facilities.store') }}" enctype="multipart/form-data">
  <h2>Tambah fasilitas / aksesoris</h2>
  @csrf
  <div class="form-grid">
    <label class="f full">Jenis <small>Fasilitas tampil di galeri atas, aksesoris di galeri bawahnya</small>
      <select name="type">
        @foreach(\App\Models\Facility::TYPES as $value => $label)
          <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
        @endforeach
      </select>
      @error('type')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label class="f">Nama <small>Contoh: Sertifikat BNSP, Modul pelatihan, Tumbler, Topi</small>
      <input name="title" value="{{ old('title') }}" required maxlength="80">
      @error('title')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label class="f">Foto <small>Maks. 4 MB</small>
      <input type="file" name="image" accept="image/*" required>
      @error('image')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label class="f">Keterangan singkat <small>Opsional</small>
      <input name="description" value="{{ old('description') }}" maxlength="200">
      @error('description')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label class="f">Urutan <small>Angka kecil tampil lebih dulu</small>
      <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
    </label>
  </div>
  <div class="form-foot"><button class="btn" type="submit">Simpan</button></div>
</form>

@foreach(\App\Models\Facility::TYPES as $type => $label)
@php $items = $facilities->get($type, collect()); @endphp
<div class="card">
  <h2>{{ $label }} tersimpan ({{ $items->count() }})</h2>
  @if($items->isEmpty())
    <p class="empty">Belum ada {{ strtolower($label) }}. Galeri {{ strtolower($label) }} di website akan muncul setelah Anda menambahkannya.</p>
  @else
    <div class="gallery">
      @foreach($items as $f)
        <figure>
          <img src="{{ asset('storage/' . $f->image) }}" alt="{{ $f->title }}" loading="lazy">
          <figcaption>
            <span>{{ $f->sort_order }}. {{ $f->title }}</span>
            <form method="POST" action="{{ route('admin.facilities.destroy', $f) }}" data-confirm="Hapus {{ strtolower($label) }} ini?">
              @csrf @method('DELETE')<button type="submit">Hapus</button>
            </form>
          </figcaption>
        </figure>
      @endforeach
    </div>
  @endif
</div>
@endforeach
@endsection
