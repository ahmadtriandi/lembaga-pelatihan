@extends('layouts.admin')

@section('title', $testimonial->exists ? 'Ubah testimoni' : 'Tambah testimoni')

@section('content')
<form class="card" method="POST" enctype="multipart/form-data" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}">
  @csrf
  @if($testimonial->exists) @method('PUT') @endif
  <div class="form-grid">
    <label class="f">Nama alumni
      <input name="name" value="{{ old('name', $testimonial->name) }}" required>
      @error('name')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label class="f">Perusahaan
      <input name="company" value="{{ old('company', $testimonial->company) }}">
    </label>
    <label class="f">Program yang diikuti
      <input name="program" value="{{ old('program', $testimonial->program) }}">
    </label>
    <label class="f">Rating
      <select name="rating">
        @for($i = 5; $i >= 1; $i--)
          <option value="{{ $i }}" @selected(old('rating', $testimonial->rating) == $i)>{{ $i }} bintang</option>
        @endfor
      </select>
    </label>
    <label class="f full">Isi testimoni
      <textarea name="content" maxlength="1000" required>{{ old('content', $testimonial->content) }}</textarea>
      @error('content')<span class="err">{{ $message }}</span>@enderror
    </label>
    @include('admin.partials.image-field', ['name' => 'photo', 'label' => 'Foto (opsional)', 'current' => $testimonial->photo])
    <label class="check" style="align-self:start;margin-top:24px">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial->is_active))> Tampilkan di website
    </label>
  </div>
  <div class="form-foot">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn light" href="{{ route('admin.testimonials.index') }}">Batal</a>
  </div>
</form>
@endsection
