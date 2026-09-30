@extends('layouts.admin')

@section('title', $program->exists ? 'Ubah program' : 'Tambah program')

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $program->exists ? route('admin.programs.update', $program) : route('admin.programs.store') }}">
  @csrf
  @if($program->exists) @method('PUT') @endif

  <div class="card">
    <h2>Informasi utama</h2>
    <div class="form-grid">
      <label class="f full">Nama program
        <input name="name" value="{{ old('name', $program->name) }}" required>
        @error('name')<span class="err">{{ $message }}</span>@enderror
      </label>
      <label class="f">Bidang
        <select name="category_id">
          <option value="">— Tanpa bidang —</option>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(old('category_id', $program->category_id) == $c->id)>{{ $c->name }}</option>
          @endforeach
        </select>
      </label>
      <label class="f">Level <small>Contoh: Manajer, Operator, Auditor</small>
        <input name="level" value="{{ old('level', $program->level) }}">
      </label>
      <label class="f">Durasi <small>Contoh: 3 hari (2 pelatihan & 1 sertifikasi)</small>
        <input name="duration" value="{{ old('duration', $program->duration) }}">
      </label>
      <label class="f">Biaya (Rp) <small>Angka saja tanpa titik. Kosongkan untuk "Hubungi admin".</small>
        <input type="number" min="0" name="price" value="{{ old('price', $program->price) }}">
        @error('price')<span class="err">{{ $message }}</span>@enderror
      </label>
      <label class="f">Urutan tampil
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $program->sort_order ?? 0) }}">
      </label>
      <label class="check" style="align-self:end">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $program->is_active))> Tampilkan di website
      </label>
      <div class="full">@include('admin.partials.image-field', ['name' => 'image', 'label' => 'Gambar program (opsional)', 'current' => $program->image])</div>
    </div>
  </div>

  <div class="card">
    <h2>Detail program</h2>
    <p style="color:var(--muted);margin-bottom:12px">Tulis satu poin per baris. Setiap baris akan tampil sebagai butir daftar.</p>
    <div class="form-grid">
      @foreach(['units' => 'Unit kompetensi', 'qualifications' => 'Kualifikasi peserta', 'requirements' => 'Persyaratan', 'facilities' => 'Fasilitas'] as $field => $label)
        <label class="f">{{ $label }}
          <textarea name="{{ $field }}" rows="8">{{ old($field, $program->{$field}) }}</textarea>
        </label>
      @endforeach
    </div>
  </div>

  <div class="form-foot">
    <button class="btn" type="submit">Simpan program</button>
    <a class="btn light" href="{{ route('admin.programs.index') }}">Batal</a>
  </div>
</form>
@endsection
