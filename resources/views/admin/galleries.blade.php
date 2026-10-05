@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
<div class="grid2">
  <form class="card" method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data">
    <h2>Unggah foto</h2>
    @csrf
    <div class="form-grid">
      <label class="f full">Pilih foto <small>Bisa pilih beberapa sekaligus (maks. 20, masing-masing 4 MB)</small>
        <input type="file" name="images[]" accept="image/*" multiple required>
        @error('images')<span class="err">{{ $message }}</span>@enderror
        @error('images.*')<span class="err">{{ $message }}</span>@enderror
      </label>
      <label class="f full">Keterangan <small>Opsional, contoh: Kelas PPPA Maret 2026</small>
        <input name="title" value="{{ old('title') }}">
      </label>
    </div>
    <div class="form-foot"><button class="btn" type="submit">Unggah foto</button></div>
  </form>

  <form class="card" method="POST" action="{{ route('admin.galleries.video') }}">
    <h2>Tambah video YouTube</h2>
    @csrf
    <div class="form-grid">
      <label class="f full">Tautan video
        <input name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=..." required>
        <small>Boleh tautan biasa, youtu.be, atau Shorts.</small>
        @error('video_url')<span class="err">{{ $message }}</span>@enderror
      </label>
      <label class="f full">Keterangan <small>Opsional</small>
        <input name="title" value="{{ old('title') }}">
      </label>
    </div>
    <div class="form-foot"><button class="btn" type="submit">Tambah video</button></div>
  </form>
</div>

<div class="card">
  <h2>Isi galeri ({{ $galleries->total() }})</h2>
  <p style="color:var(--muted);margin-bottom:12px">Angka pada kotak urutan menentukan posisi tampil di website. Angka kecil tampil lebih dulu.</p>
  @if($galleries->isEmpty())
    <p class="empty">Belum ada isi. Bagian galeri di website akan muncul setelah Anda menambahkan foto atau video.</p>
  @else
    <div class="gallery">
      @foreach($galleries as $g)
        <figure>
          @if($g->thumb_url)
            <img src="{{ $g->thumb_url }}" alt="{{ $g->title }}" loading="lazy">
          @endif
          @if($g->isVideo())<span class="tag-video">Video</span>@endif
          <figcaption>
            <span>{{ $g->title }}</span>
            <form method="POST" action="{{ route('admin.galleries.sort', $g) }}" style="display:flex;gap:4px">
              @csrf @method('PATCH')
              <input type="number" name="sort_order" value="{{ $g->sort_order }}" min="0" max="9999"
                     style="width:58px;padding:2px 4px" aria-label="Urutan">
              <button type="submit" title="Simpan urutan">OK</button>
            </form>
            <form method="POST" action="{{ route('admin.galleries.destroy', $g) }}" data-confirm="Hapus item ini dari galeri?">
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
