@extends('layouts.admin')

@section('title', $post->exists ? 'Ubah artikel' : 'Tulis artikel')

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}">
  @csrf
  @if($post->exists) @method('PUT') @endif
  <div class="grid2">
    <div class="card">
      <div class="form-grid">
        <label class="f full">Judul
          <input name="title" value="{{ old('title', $post->title) }}" required>
          @error('title')<span class="err">{{ $message }}</span>@enderror
        </label>
        <label class="f full">Slug URL <small>Kosongkan agar dibuat otomatis dari judul</small>
          <input name="slug" value="{{ old('slug', $post->slug) }}">
          @error('slug')<span class="err">{{ $message }}</span>@enderror
        </label>
        <label class="f full">Ringkasan <small>Maks. 300 karakter, dipakai juga untuk deskripsi Google</small>
          <textarea name="excerpt" maxlength="300" style="min-height:80px">{{ old('excerpt', $post->excerpt) }}</textarea>
        </label>
        <label class="f full">Isi artikel <small>Mendukung HTML: &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;&lt;li&gt;, &lt;strong&gt;, &lt;a&gt;, &lt;img&gt;</small>
          <textarea name="body" class="tall">{{ old('body', $post->body) }}</textarea>
        </label>
      </div>
    </div>
    <div>
      <div class="card">
        <h2>Publikasi</h2>
        <label class="f">Tanggal terbit
          <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
          <small>Tanggal di masa depan = terjadwal</small>
        </label>
        <label class="check" style="margin-top:12px">
          <input type="checkbox" name="draft" value="1" @checked(old('draft', $post->exists && ! $post->published_at))> Simpan sebagai draf
        </label>
      </div>
      <div class="card">
        @include('admin.partials.image-field', ['name' => 'image', 'label' => 'Gambar sampul', 'current' => $post->image])
      </div>
      <div class="form-foot">
        <button class="btn" type="submit">Simpan artikel</button>
        <a class="btn light" href="{{ route('admin.posts.index') }}">Batal</a>
      </div>
    </div>
  </div>
</form>
@endsection
