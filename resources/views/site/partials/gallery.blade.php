{{-- Galeri: satu baris foto, satu baris video. Panah geser muncul otomatis kalau isinya tidak muat. --}}
@php
    $fotos = $galleries->where('type', '!=', 'video')->filter(fn ($g) => $g->thumb_url)->values();
    $videos = $galleries->where('type', 'video')->filter(fn ($g) => $g->thumb_url)->values();
@endphp

@if($fotos->isNotEmpty() || $videos->isNotEmpty())
<section id="galeri" class="gallery-section">
  <div class="wrap">
    <h2>Galeri kegiatan</h2>
    <p class="lead">Momen dari kelas pelatihan dan uji kompetensi kami.</p>

    @foreach([['Foto', $fotos], ['Video', $videos]] as $row)
      @php $judul = $row[0]; $items = $row[1]; @endphp
      @if($items->isNotEmpty())
        <div class="gal-row">
          <div class="gal-head">
            <h3>{{ $judul }}</h3>
            <div class="gal-nav" hidden>
              <button type="button" class="gal-prev" aria-label="Geser {{ $judul }} ke kiri">&#8249;</button>
              <button type="button" class="gal-next" aria-label="Geser {{ $judul }} ke kanan">&#8250;</button>
            </div>
          </div>

          <div class="gal-track" tabindex="0" role="region" aria-label="Baris {{ $judul }}, geser untuk melihat lainnya">
            @foreach($items as $g)
              <figure class="gal-item {{ $g->isVideo() ? 'is-video' : '' }}"
                      data-type="{{ $g->type }}"
                      data-src="{{ $g->isVideo() ? $g->embed_url : $g->thumb_url }}"
                      data-title="{{ $g->title }}">
                <button type="button" class="gal-open" aria-label="{{ $g->isVideo() ? 'Putar video' : 'Perbesar foto' }}{{ $g->title ? ': ' . $g->title : '' }}">
                  <img src="{{ $g->thumb_url }}" alt="{{ $g->title ?: 'Dokumentasi kegiatan' }}" loading="lazy">
                  @if($g->isVideo())
                    <span class="play" aria-hidden="true">
                      <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                    </span>
                  @endif
                </button>
                @if($g->title)<figcaption>{{ $g->title }}</figcaption>@endif
              </figure>
            @endforeach
          </div>
        </div>
      @endif
    @endforeach
  </div>
</section>

<div class="gal-modal" hidden>
  <button type="button" class="gal-close" aria-label="Tutup">&times;</button>
  <div class="gal-modal-body"></div>
</div>
@endif
