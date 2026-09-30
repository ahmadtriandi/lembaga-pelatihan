@extends('layouts.site')

@php
    $wa = $site['whatsapp_1'] ?? '';
    $waLink = fn ($text = null) => 'https://api.whatsapp.com/send?phone=' . $wa . ($text ? '&text=' . rawurlencode($text) : '');
    $lines = fn ($text) => array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $text))));
    $stats = [
        ['stat_alumni', 'Alumni pelatihan'], ['stat_experts', 'Tenaga ahli'],
        ['stat_programs', 'Skema sertifikasi'], ['stat_rating', 'Penilaian peserta'],
    ];
@endphp

@section('content')
<section class="hero">
  <svg class="contour" viewBox="0 0 1200 600" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <g fill="none" stroke="#fff" stroke-width="1.2">
      @for($y = 130; $y <= 480; $y += 50)
        <path d="M-50 {{ $y }}c180-60 300 40 480-20s260-180 460-140 260 120 360 80"/>
      @endfor
    </g>
  </svg>
  <div class="wrap">
    <div>
      <p class="welcome">{{ $site['site_tagline'] ?? '' }}</p>
      <h1>{{ $site['hero_title'] ?? '' }}</h1>
      <p>{{ $site['hero_text'] ?? '' }}</p>
      <div class="ctas">
        <a class="btn btn-stamp" href="#program">Pilih program</a>
        @if($wa)<a class="btn btn-ghost" href="{{ $waLink('Halo, saya ingin konsultasi pelatihan.') }}">Konsultasi via WhatsApp</a>@endif
      </div>
    </div>
    @if(!empty($site['hero_image']))
      <div class="hero-img"><img src="{{ asset('storage/' . $site['hero_image']) }}" alt=""></div>
    @else
      <div class="seal-wrap" aria-hidden="true">
        <svg class="seal" viewBox="0 0 300 300">
          <defs><path id="ring" d="M150 150m-118 0a118 118 0 1 1 236 0a118 118 0 1 1-236 0"/></defs>
          <circle cx="150" cy="150" r="140" fill="none" stroke="#E8B930" stroke-width="3" stroke-dasharray="4 7"/>
          <circle cx="150" cy="150" r="98" fill="none" stroke="#E8B930" stroke-width="2"/>
          <text fill="#E8B930" font-family="Bricolage Grotesque, sans-serif" font-size="17" font-weight="700" letter-spacing="3">
            <textPath href="#ring">SERTIFIKASI KOMPETENSI • LINGKUNGAN • ENERGI • </textPath>
          </text>
        </svg>
        <div class="seal-core"><div><b>{{ $programs->count() }}</b><span>program<br>tersedia</span></div></div>
      </div>
    @endif
  </div>
</section>

<section class="pillars" aria-label="Keunggulan">
  <div class="wrap">
    <div class="grid">
      <div class="pillar">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="4"/><path d="M2 21c0-4 3-6 7-6s7 2 7 6M17 11l2 2 4-4"/></svg></div>
        <h3>Tim Profesional</h3>
        <p>Instruktur berpengalaman dan tersertifikasi untuk memastikan pelatihan berkualitas.</p>
      </div>
      <div class="pillar">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h12l4 4v12H4z"/><path d="M8 12h8M8 16h5"/></svg></div>
        <h3>Materi Komprehensif</h3>
        <p>Kurikulum pembelajaran kami dirancang sesuai standar nasional dan internasional.</p>
      </div>
      <div class="pillar">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="9" r="6"/><path d="M8.5 14 7 22l5-3 5 3-1.5-8"/></svg></div>
        <h3>Program Bersertifikat</h3>
        <p>Program pelatihan kami resmi dan bekerja sama dengan Kemnaker dan BNSP.</p>
      </div>
    </div>
  </div>
</section>

<section class="about" id="tentang">
  <div class="wrap">
    <div class="about-visual">
      @if(!empty($site['about_image']))
        <img src="{{ asset('storage/' . $site['about_image']) }}" alt="Kegiatan {{ $site['site_name'] ?? '' }}">
      @else
        <svg viewBox="0 0 400 500" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
          <rect width="400" height="500" fill="#3F7D58"/><circle cx="300" cy="110" r="60" fill="#E8B930"/>
          <path d="M0 330 120 220l90 70 80-60 110 90v180H0z" fill="#0F3D3E"/>
        </svg>
      @endif
    </div>
    <div>
      <h2>Tentang kami</h2>
      @foreach(preg_split('/\n\s*\n/', trim($site['about_text'] ?? '')) as $i => $para)
        <p class="{{ $i === 0 ? 'lead' : '' }}">{{ $para }}</p>
      @endforeach
      <div class="vm">
        @if(!empty($site['vision']))
          <div><h3>Visi</h3><p style="color:var(--muted)">{{ $site['vision'] }}</p></div>
        @endif
        @if(!empty($site['mission']))
          <div><h3>Misi</h3><ul>@foreach($lines($site['mission']) as $m)<li>{{ $m }}</li>@endforeach</ul></div>
        @endif
      </div>
      <a class="btn btn-moss" style="margin-top:1.6rem" href="#daftar">Daftar pelatihan</a>
    </div>
  </div>
</section>

<section class="programs" id="program">
  <div class="wrap">
    <div class="prog-head">
      <div>
        <h2>Pilihan program</h2>
        <p class="lead">Buka setiap program untuk melihat unit kompetensi, syarat peserta, dan fasilitas.</p>
      </div>
    </div>
    <div class="filters" role="group" aria-label="Filter bidang">
      <button type="button" data-cat="all" aria-pressed="true">Semua</button>
      @foreach($categories as $cat)
        <button type="button" data-cat="{{ $cat->slug }}" aria-pressed="false">{{ $cat->name }}</button>
      @endforeach
    </div>
    <div class="prog-list">
      @forelse($programs as $p)
        <article class="prog" data-cat="{{ $p->category?->slug }}" style="--c:{{ $p->category?->color ?? '#3F7D58' }}">
          @if($p->image)<img class="prog-img" src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" loading="lazy">@endif
          <div class="prog-top">
            <div class="prog-meta">
              <span class="lvl">{{ $p->level ? 'Level ' . $p->level : '' }}{{ $p->category ? ' · ' . $p->category->name : '' }}</span>
              <span>{{ $p->duration }}</span>
            </div>
            <h3>{{ $p->name }}</h3>
          </div>
          @foreach(['units' => 'Unit kompetensi', 'qualifications' => 'Kualifikasi peserta', 'requirements' => 'Persyaratan', 'facilities' => 'Fasilitas'] as $field => $label)
            @if($p->lines($field))
              <details><summary>{{ $label }}</summary><ul>@foreach($p->lines($field) as $item)<li>{{ $item }}</li>@endforeach</ul></details>
            @endif
          @endforeach
          <div class="prog-foot">
            <span class="price">{{ $p->price_label }}</span>
            <a class="btn btn-moss" href="#daftar" data-pick-program="{{ $p->id }}">Daftar</a>
          </div>
        </article>
      @empty
        <p>Belum ada program. Tambahkan dari dashboard.</p>
      @endforelse
    </div>
  </div>
</section>

<section class="steps">
  <div class="wrap">
    <h2>Alur pendaftaran</h2>
    <p class="lead">Dari konsultasi sampai sertifikat terbit.</p>
    <ol>
      <li><h3>Isi formulir</h3><p>Pilih program dan tinggalkan nomor WhatsApp Anda di formulir di bawah.</p></li>
      <li><h3>Kirim berkas</h3><p>Admin menghubungi Anda untuk meminta ijazah, KTP, CV, dan bukti pekerjaan.</p></li>
      <li><h3>Ikuti pelatihan</h3><p>Kelas tatap muka atau online, sesuai jadwal yang dipilih.</p></li>
      <li><h3>Uji kompetensi</h3><p>Asesmen oleh asesor, lalu sertifikat diterbitkan.</p></li>
    </ol>
  </div>
</section>

@if($galleries->isNotEmpty())
<section id="galeri" style="padding-top:0">
  <div class="wrap">
    <h2>Galeri kegiatan</h2>
    <p class="lead">Momen dari kelas pelatihan dan uji kompetensi kami.</p>
    <div class="gal">
      @foreach($galleries as $g)
        <figure>
          <a href="{{ asset('storage/' . $g->image) }}" target="_blank"><img src="{{ asset('storage/' . $g->image) }}" alt="{{ $g->title ?? 'Dokumentasi kegiatan' }}" loading="lazy"></a>
          @if($g->title)<figcaption>{{ $g->title }}</figcaption>@endif
        </figure>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="stats" aria-label="Pencapaian">
  <div class="wrap">
    @foreach($stats as $stat)
      @php $label = $stat[1]; $val = $site[$stat[0]] ?? ''; $isNum = preg_match('/^(\d+)(\D*)$/', $val, $m); @endphp
      <div class="stat">
        @if($isNum)<b data-count="{{ $m[1] }}" data-suffix="{{ $m[2] }}">{{ $val }}</b>@else<b>{{ $val }}</b>@endif
        <span>{{ $label }}</span>
      </div>
    @endforeach
  </div>
</section>

@if($clients->isNotEmpty())
<section>
  <div class="wrap">
    <h2>Klien kami</h2>
    <p class="lead">Perusahaan yang telah mengirim personelnya berlatih bersama kami.</p>
    <div class="clients-row">
      @foreach($clients as $c)
        <div class="client" title="{{ $c->name }}">
          @if($c->logo)<img src="{{ asset('storage/' . $c->logo) }}" alt="{{ $c->name }}" loading="lazy">@else{{ $c->name }}@endif
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if($testimonials->isNotEmpty())
<section class="testi">
  <div class="wrap">
    <h2>Kata alumni</h2>
    <p class="lead">Pengalaman peserta yang sudah mengikuti pelatihan kami.</p>
    <div class="testi-track">
      @foreach($testimonials as $t)
        <figure class="quote">
          <div class="stars" aria-label="{{ $t->rating }} dari 5">{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</div>
          <p>{{ $t->content }}</p>
          <figcaption class="who">
            <span class="avatar">
              @if($t->photo)<img src="{{ asset('storage/' . $t->photo) }}" alt="">@else{{ \Illuminate\Support\Str::of($t->name)->explode(' ')->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}@endif
            </span>
            <span><b>{{ $t->name }}</b><small>{{ collect([$t->company, $t->program])->filter()->implode(' · ') }}</small></span>
          </figcaption>
        </figure>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="register" id="daftar">
  <div class="wrap">
    <div>
      <h2>Daftar pelatihan</h2>
      <p class="lead">Isi data singkat di sini. Admin akan menghubungi Anda lewat WhatsApp untuk jadwal dan kelengkapan berkas.</p>
      @if($wa)<p style="margin-top:1rem">Lebih suka langsung chat? <a href="{{ $waLink('Halo, saya ingin mendaftar pelatihan.') }}">Hubungi admin via WhatsApp</a>.</p>@endif
    </div>
    <div>
      @if(session('registered'))
        <div class="alert-ok" role="status">{{ session('registered') }}</div>
      @endif
      <form class="form" method="POST" action="{{ route('register') }}">
        @csrf
        <label class="full">Program
          <select name="program_id" id="program_id">
            <option value="">Belum tahu, ingin konsultasi</option>
            @foreach($programs as $p)
              <option value="{{ $p->id }}" @selected(old('program_id') == $p->id)>{{ $p->name }}</option>
            @endforeach
          </select>
        </label>
        <label>Nama lengkap
          <input name="name" value="{{ old('name') }}" required autocomplete="name">
          @error('name')<span class="err">{{ $message }}</span>@enderror
        </label>
        <label>Nomor WhatsApp
          <input name="phone" value="{{ old('phone') }}" required inputmode="tel" autocomplete="tel" placeholder="08xxxxxxxxxx">
          @error('phone')<span class="err">{{ $message }}</span>@enderror
        </label>
        <label>Email
          <input type="email" name="email" value="{{ old('email') }}" autocomplete="email">
          @error('email')<span class="err">{{ $message }}</span>@enderror
        </label>
        <label>Perusahaan / instansi
          <input name="company" value="{{ old('company') }}" autocomplete="organization">
        </label>
        <label class="full">Jenis kelas
          <select name="class_type">
            <option value="offline" @selected(old('class_type') === 'offline')>Offline (tatap muka)</option>
            <option value="online" @selected(old('class_type') === 'online')>Online</option>
          </select>
        </label>
        <label class="full">Pesan (opsional)
          <textarea name="message" placeholder="Contoh: kami ingin mendaftarkan 5 orang untuk bulan depan">{{ old('message') }}</textarea>
        </label>
        <div class="full"><button class="btn btn-moss" type="submit">Kirim pendaftaran</button></div>
      </form>
    </div>
  </div>
</section>

@if($posts->isNotEmpty())
<section>
  <div class="wrap">
    <h2>Artikel terbaru</h2>
    <p class="lead">Informasi seputar regulasi, sertifikasi, dan pengembangan kompetensi.</p>
    @include('site.partials.post-grid', ['items' => $posts])
    <p style="margin-top:22px"><a class="btn btn-ghost" href="{{ route('posts') }}">Lihat semua artikel</a></p>
  </div>
</section>
@endif

<section class="cta" style="{{ $posts->isEmpty() ? 'padding-top:88px' : '' }}">
  <div class="wrap">
    <div class="cta-box">
      <h2>Konsultasikan kebutuhan pelatihan tim Anda hari ini.</h2>
      @if($wa)<a class="btn btn-stamp" href="{{ $waLink('Halo, saya ingin konsultasi pelatihan.') }}">Chat admin sekarang</a>@endif
    </div>
  </div>
</section>
@endsection
