@php
    $siteName = $site['site_name'] ?? config('app.name');
    $wa = $site['whatsapp_1'] ?? '';
    $waLink = fn ($text = null, $num = null) => 'https://api.whatsapp.com/send?phone=' . ($num ?? $wa) . ($text ? '&text=' . rawurlencode($text) : '');
    $socials = collect(['instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'facebook' => 'Facebook', 'tiktok' => 'TikTok', 'youtube' => 'YouTube'])
        ->filter(fn ($label, $key) => ! empty($site[$key]));
    $home = request()->routeIs('home') ? '' : route('home');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', $siteName . ' — ' . ($site['site_tagline'] ?? ''))</title>
<meta name="description" content="@yield('description', $site['meta_description'] ?? '')">
<meta property="og:title" content="@yield('title', $siteName)">
<meta property="og:description" content="@yield('description', $site['meta_description'] ?? '')">
@if(!empty($site['logo']))<link rel="icon" href="{{ asset('storage/' . $site['logo']) }}">@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}">
{{-- Terapkan tema pilihan pengunjung sebelum halaman tampil (tanpa kedip). Tanpa pilihan: ikut pengaturan perangkat. --}}
<script>try { const t = localStorage.getItem('theme'); if (t === 'light' || t === 'dark') document.documentElement.dataset.theme = t; } catch (e) {}</script>
</head>
<body id="top">

<div class="topbar">
  <div class="wrap">
    <div class="grp">
      @if($wa)<a href="{{ $waLink() }}">WA: {{ $wa }}</a>@endif
      @if(!empty($site['whatsapp_2']))<a href="{{ $waLink(null, $site['whatsapp_2']) }}">WA: {{ $site['whatsapp_2'] }}</a>@endif
      @if(!empty($site['email']))<a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a>@endif
    </div>
    <div class="grp">
      @foreach($socials as $key => $label)
        <a href="{{ $site[$key] }}" target="_blank" rel="noopener">{{ $label }}</a>
      @endforeach
    </div>
  </div>
</div>

<header class="nav">
  <div class="wrap">
    <a class="brand" href="{{ route('home') }}">
      @if(!empty($site['logo']))
        <img src="{{ asset('storage/' . $site['logo']) }}" alt="{{ $siteName }}">
      @else
        <svg width="38" height="38" viewBox="0 0 40 40" aria-hidden="true"><circle cx="20" cy="20" r="19" fill="#2B3990"/><path d="M12 26c6-1 12-6 14-14-7 1-13 6-14 14Zm0 0 7-7" stroke="#F6F8FC" stroke-width="2.2" fill="none" stroke-linecap="round"/></svg>
        <span>{{ $siteName }}<small>{{ $site['site_tagline'] ?? '' }}</small></span>
      @endif
    </a>
    <ul class="menu" id="menu">
      <li><a href="{{ $home }}#tentang">Tentang</a></li>
      <li><a href="{{ $home }}#program">Program</a></li>
      <li><a href="{{ $home }}#fasilitas">Fasilitas</a></li>
      <li><a href="{{ $home }}#jadwal">Jadwal</a></li>
      <li><a href="{{ $home }}#galeri">Galeri</a></li>
      <li><a href="{{ route('posts') }}">Artikel</a></li>
      <li><a href="#kontak">Kontak</a></li>
      <li><a class="btn btn-moss" href="{{ $home }}#daftar">Daftar sekarang</a></li>
    </ul>
    <div class="nav-tools">
      <button type="button" class="theme-toggle" aria-label="Ganti tema terang/gelap">
        <svg class="moon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
        <svg class="sun" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
      </button>
      <button class="burger" aria-label="Buka menu" aria-expanded="false" aria-controls="menu">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>
    </div>
  </div>
</header>

<main>
@yield('content')
</main>

<footer id="kontak">
  <div class="wrap">
    <div class="foot">
      <div>
        <a class="brand" href="{{ route('home') }}" style="color:inherit">{{ $siteName }}</a>
        <p style="opacity:.85;margin-top:.6rem;max-width:36ch">Hubungi tim kami untuk memulai pelatihan sesuai kebutuhan Anda.</p>
        <ul style="margin-top:1rem;display:flex;gap:1rem;flex-wrap:wrap">
          @foreach($socials as $key => $label)
            <li><a href="{{ $site[$key] }}" target="_blank" rel="noopener">{{ $label }}</a></li>
          @endforeach
        </ul>
      </div>
      <div>
        <h3>Kontak</h3>
        <ul>
          @if($wa)<li><a href="{{ $waLink() }}">WhatsApp: {{ $wa }}</a></li>@endif
          @if(!empty($site['whatsapp_2']))<li><a href="{{ $waLink(null, $site['whatsapp_2']) }}">WhatsApp: {{ $site['whatsapp_2'] }}</a></li>@endif
          @if(!empty($site['email']))<li><a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></li>@endif
        </ul>
      </div>
      <div>
        <h3>Alamat</h3>
        @if(!empty($site['maps_link']))
          <a href="{{ $site['maps_link'] }}" target="_blank" rel="noopener" style="white-space:pre-line">{{ $site['address'] ?? '' }}</a>
        @else
          <p style="opacity:.85;white-space:pre-line">{{ $site['address'] ?? '' }}</p>
        @endif
        @if(!empty($site['maps_embed']))
          <div class="map has-embed"><iframe src="{{ $site['maps_embed'] }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta lokasi"></iframe></div>
        @endif
      </div>
    </div>
    <div class="copy"><span>© {{ date('Y') }} {{ $siteName }}. Hak cipta dilindungi.</span><a href="#top">Kembali ke atas</a></div>
  </div>
</footer>

@if($wa)
<div class="wa-float">
  <a href="{{ $waLink('Halo, saya ingin bertanya tentang pelatihan.') }}" aria-label="Chat WhatsApp admin">
    <svg width="30" height="30" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm5.4 14.2c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.3-.7-2.8-1.1-4.5-3.9-4.7-4.1-.1-.2-1.1-1.5-1.1-2.8s.7-2 1-2.3c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 2c.1.2.1.4 0 .5l-.3.5-.4.4c-.1.1-.3.3-.1.6.2.3.8 1.2 1.6 2 1.1 1 2 1.3 2.3 1.4.3.1.4.1.6-.1l.8-1c.2-.3.4-.2.7-.1l1.9.9c.3.1.4.2.5.3.1.2.1.7-.1 1.3Z"/></svg>
  </a>
</div>
@endif

<script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
