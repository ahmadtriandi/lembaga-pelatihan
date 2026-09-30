@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
<form class="card" method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data">
  <h2>Unggah foto</h2>
  @csrf
  <div class="form-grid">
    <label class="f">Pilih foto <small>Bisa pilih beberapa sekaligus (maks. 20, masing-masing 4 MB)</small>
      <input type="file" name="images[]" accept="image/*" multiple required>
      @error('images')<span class="err">{{ $message }}</span>@enderror
      @error('images.*')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label class="f">Keterangan <small>Opsional, contoh: Kelas PPPA Maret 2026</small>
      <input name="title" value="{{ old('title') }}">
    </label>
  </div>
  <div class="form-foot"><button class="btn" type="submit">Unggah</button></div>
</form>

<div class="card">
  <h2>Foto tersimpan ({{ $galleries->total() }})</h2>
  @if($galleries->isEmpty())
    <p class="empty">Belum ada foto. Bagian galeri di website akan muncul setelah Anda mengunggah foto.</p>
  @else
    <div class="gallery">
      @foreach($galleries as $g)
        <figure>
          <img src="{{ asset('storage/' . $g->image) }}" alt="{{ $g->title }}" loading="lazy">
          <figcaption>
            <span>{{ $g->title }}</span>
            <form method="POST" action="{{ route('admin.galleries.destroy', $g) }}" data-confirm="Hapus foto ini?">
              @csrf @method('DELETE')<button type="submit">Hapus</button>
            </form>
          </figcaption>
        </figure>
      @endforeach
    </div>
    {{ $galleries->links() }}
  @endif
</div>
@endsection
